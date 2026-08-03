#!/usr/bin/env node
/**
 * Create the Crossroads Commons donation Payment Links in Stripe.
 *
 * Run this LOCALLY. The secret key is read from the environment and is never
 * written to disk, committed, or deployed — the website itself only ever holds
 * the resulting public https://buy.stripe.com/... URLs.
 *
 *   # Dry run — shows exactly what would be created, touches nothing
 *   STRIPE_SECRET_KEY=sk_test_... node scripts/create-stripe-payment-links.mjs
 *
 *   # Create the links in test mode, then write them into the theme config
 *   STRIPE_SECRET_KEY=sk_test_... node scripts/create-stripe-payment-links.mjs --create --write
 *
 *   # Create the real, live links (--live is required as a guard)
 *   STRIPE_SECRET_KEY=sk_live_... node scripts/create-stripe-payment-links.mjs --create --live --write
 *
 * Options:
 *   --create   Actually create things in Stripe (default is a dry run)
 *   --live     Required acknowledgement when the key is a live-mode key
 *   --write    Overwrite wp-content/themes/crossroads-commons/inc/donation-links.php
 *   --success  Redirect URL after payment
 *              (default https://crossroadscommons.com/thank-you/)
 *
 * Re-running is safe: products, prices, and payment links are matched by stable
 * ids / lookup keys / metadata, so an existing link is reused rather than
 * duplicated. Amounts below can be edited before the first run.
 */

import { writeFileSync } from 'node:fs';
import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

// ── Amount tiers ────────────────────────────────────────────────────────────
// Keep these in sync with inc/donation-links.php (this script rewrites it).
const ONE_TIME = [
  { amount: 25, label: 'Neighbor' },
  { amount: 50, label: 'Friend' },
  { amount: 100, label: 'Supporter' },
  { amount: 250, label: 'Builder' },
  { amount: 500, label: 'Cornerstone' },
];

const MONTHLY = [
  { amount: 10, label: '' },
  { amount: 25, label: 'Neighbor' },
  { amount: 50, label: 'Friend' },
  { amount: 100, label: 'Supporter' },
  { amount: 250, label: 'Builder' },
];

// Stripe caps customer-chosen amounts at $10,000 by default.
const CUSTOM = { minimum: 500, preset: 10000 };

const PRODUCTS = {
  one_time: {
    id: 'crossroads_donation_one_time',
    name: 'Donation to Crossroads Commons',
    description:
      'Supporting community-led revitalization in South Oklahoma City.',
  },
  monthly: {
    id: 'crossroads_donation_monthly',
    name: 'Monthly donation to Crossroads Commons',
    description:
      'A recurring gift supporting community-led revitalization in South Oklahoma City.',
  },
};

// ── CLI ─────────────────────────────────────────────────────────────────────
const argv = process.argv.slice(2);
const has = (flag) => argv.includes(flag);
const optValue = (name, fallback) => {
  const hit = argv.find((a) => a.startsWith(`${name}=`));
  return hit ? hit.slice(name.length + 1) : fallback;
};

const DRY_RUN = !has('--create');
const WRITE = has('--write');
const SUCCESS_URL = optValue('--success', 'https://crossroadscommons.com/thank-you/');

const KEY = process.env.STRIPE_SECRET_KEY;
if (!KEY) {
  fail(
    'STRIPE_SECRET_KEY is not set.\n' +
      'Grab it from https://dashboard.stripe.com/apikeys and run:\n' +
      '  STRIPE_SECRET_KEY=sk_... node scripts/create-stripe-payment-links.mjs'
  );
}
if (!/^(sk|rk)_(test|live)_/.test(KEY)) {
  fail('STRIPE_SECRET_KEY does not look like a Stripe secret key (expected sk_test_… or sk_live_…).');
}

const IS_LIVE = KEY.includes('_live_');
if (IS_LIVE && !DRY_RUN && !has('--live')) {
  fail(
    'That is a LIVE key and --create was passed, but --live was not.\n' +
      'This would create real, publicly payable links on the real account.\n' +
      'Re-run with --live if that is what you want.'
  );
}
if (Number(process.versions.node.split('.')[0]) < 18) {
  fail(`Node 18+ required (found ${process.version}).`);
}

// ── Stripe REST helpers (no dependencies) ───────────────────────────────────
function encodeForm(value, prefix = '', out = []) {
  if (value === null || value === undefined) return out;
  if (Array.isArray(value)) {
    value.forEach((item, i) => encodeForm(item, `${prefix}[${i}]`, out));
  } else if (typeof value === 'object') {
    for (const [k, v] of Object.entries(value)) {
      encodeForm(v, prefix ? `${prefix}[${k}]` : k, out);
    }
  } else {
    out.push(`${encodeURIComponent(prefix)}=${encodeURIComponent(String(value))}`);
  }
  return out;
}

async function stripe(method, path, body, { idempotencyKey } = {}) {
  const headers = {
    Authorization: `Basic ${Buffer.from(`${KEY}:`).toString('base64')}`,
    'Stripe-Version': '2024-06-20',
  };
  if (body) headers['Content-Type'] = 'application/x-www-form-urlencoded';
  if (idempotencyKey) headers['Idempotency-Key'] = idempotencyKey;

  const res = await fetch(`https://api.stripe.com/v1/${path}`, {
    method,
    headers,
    body: body ? encodeForm(body).join('&') : undefined,
  });
  const json = await res.json().catch(() => ({}));

  if (!res.ok) {
    const err = json.error || {};
    // A 404 on a lookup is a legitimate "not found", not a failure.
    if (res.status === 404 && method === 'GET') return null;
    // fail() rather than throw: a top-level-await stack trace only buries the
    // Stripe message, which is the part that's actually actionable.
    fail(
      `Stripe ${method} /${path} → ${res.status} ${err.type || ''}\n  ${err.message || JSON.stringify(json)}`
    );
  }
  return json;
}

// ── Idempotent getOrCreate helpers ──────────────────────────────────────────
async function ensureProduct({ id, name, description }) {
  const existing = await stripe('GET', `products/${id}`);
  if (existing && !existing.deleted) {
    log(`  product ${id} — reusing`);
    return existing;
  }
  if (DRY_RUN) {
    log(`  product ${id} — WOULD CREATE "${name}"`);
    return { id };
  }
  const created = await stripe('POST', 'products', { id, name, description }, { idempotencyKey: `prod_${id}` });
  log(`  product ${id} — created`);
  return created;
}

async function ensurePrice(lookupKey, params) {
  const found = await stripe('GET', `prices?lookup_keys[0]=${encodeURIComponent(lookupKey)}&limit=1&active=true`);
  if (found?.data?.length) {
    log(`  price ${lookupKey} — reusing ${found.data[0].id}`);
    return found.data[0];
  }
  if (DRY_RUN) {
    log(`  price ${lookupKey} — WOULD CREATE`);
    return { id: `price_dryrun_${lookupKey}` };
  }
  const created = await stripe(
    'POST',
    'prices',
    { ...params, lookup_key: lookupKey },
    { idempotencyKey: `price_${lookupKey}` }
  );
  log(`  price ${lookupKey} — created ${created.id}`);
  return created;
}

// Payment Links have no stable id we can set, so they are matched on metadata.
let paymentLinkCache = null;
async function allPaymentLinks() {
  if (paymentLinkCache) return paymentLinkCache;
  const links = [];
  let startingAfter;
  do {
    const qs = new URLSearchParams({ limit: '100' });
    if (startingAfter) qs.set('starting_after', startingAfter);
    const page = await stripe('GET', `payment_links?${qs}`);
    links.push(...page.data);
    startingAfter = page.has_more ? page.data[page.data.length - 1].id : null;
  } while (startingAfter);
  paymentLinkCache = links;
  return links;
}

async function ensurePaymentLink(key, params) {
  const existing = (await allPaymentLinks()).find(
    (l) => l.metadata?.crossroads_key === key && l.active
  );
  if (existing) {
    log(`  link ${key} — reusing ${existing.url}`);
    return existing;
  }
  if (DRY_RUN) {
    log(`  link ${key} — WOULD CREATE`);
    return { url: '' };
  }
  const created = await stripe(
    'POST',
    'payment_links',
    {
      ...params,
      metadata: { crossroads_key: key },
      after_completion: { type: 'redirect', redirect: { url: SUCCESS_URL } },
    },
    { idempotencyKey: `link_${key}` }
  );
  log(`  link ${key} — created ${created.url}`);
  return created;
}

// ── Main ────────────────────────────────────────────────────────────────────
function log(msg) {
  process.stdout.write(`${msg}\n`);
}
function fail(msg) {
  process.stderr.write(`\n✗ ${msg}\n\n`);
  process.exit(1);
}

// Stripe errors are actionable on their own — a stack trace only buries them.
process.on('unhandledRejection', (err) => fail(err?.message || String(err)));

log('');
log(`Mode:        ${DRY_RUN ? 'DRY RUN (nothing will be created)' : 'CREATE'}`);
log(`Stripe key:  ${IS_LIVE ? 'LIVE' : 'TEST'} mode`);
log(`Success URL: ${SUCCESS_URL}`);
log('');

const account = await stripe('GET', 'account');
log(`Account:     ${account.business_profile?.name || account.id}`);
if (!DRY_RUN && IS_LIVE && !account.charges_enabled) {
  fail('This account cannot accept live charges yet — finish Stripe onboarding first.');
}
log('');

log('One-time gifts');
const oneTimeProduct = await ensureProduct(PRODUCTS.one_time);
const oneTimeResults = [];
for (const tier of ONE_TIME) {
  const key = `one_time_${tier.amount}`;
  const price = await ensurePrice(`crossroads_${key}`, {
    product: oneTimeProduct.id,
    currency: 'usd',
    unit_amount: tier.amount * 100,
  });
  const link = await ensurePaymentLink(key, {
    line_items: [{ price: price.id, quantity: 1 }],
    submit_type: 'donate',
  });
  oneTimeResults.push({ ...tier, url: link.url });
}

log('');
log('One-time gift, donor-chosen amount');
const customPrice = await ensurePrice('crossroads_custom', {
  product: oneTimeProduct.id,
  currency: 'usd',
  custom_unit_amount: {
    enabled: true,
    minimum: CUSTOM.minimum,
    preset: CUSTOM.preset,
  },
});
const customLink = await ensurePaymentLink('custom', {
  line_items: [{ price: customPrice.id, quantity: 1 }],
  submit_type: 'donate',
});

log('');
log('Monthly gifts');
const monthlyProduct = await ensureProduct(PRODUCTS.monthly);
const monthlyResults = [];
for (const tier of MONTHLY) {
  const key = `monthly_${tier.amount}`;
  const price = await ensurePrice(`crossroads_${key}`, {
    product: monthlyProduct.id,
    currency: 'usd',
    unit_amount: tier.amount * 100,
    recurring: { interval: 'month' },
  });
  // submit_type is not permitted on links containing a recurring price.
  const link = await ensurePaymentLink(key, {
    line_items: [{ price: price.id, quantity: 1 }],
  });
  monthlyResults.push({ ...tier, url: link.url });
}

// ── Emit the theme config ───────────────────────────────────────────────────
const pad = (s, n) => String(s).padEnd(n);
const tierLine = (t) =>
  `        array( 'amount' => ${pad(`${t.amount},`, 4)} 'label' => ${pad(`'${t.label}',`, 14)} 'url' => '${t.url}' ),`;

const php = `<?php
/**
 * Stripe Payment Link configuration for the Donate page.
 *
 * GENERATED by scripts/create-stripe-payment-links.mjs — re-run that script to
 * regenerate. Safe to hand-edit; it only holds public buy.stripe.com URLs.
 *
 * Mode: ${IS_LIVE ? 'LIVE' : 'TEST'}
 *
 * Leave a 'url' empty to hide that tier. If EVERY url is empty, the Donate page
 * shows a "coming soon" notice and the Donate nav item / CTA stay hidden.
 *
 * Note: Stripe Payment Links only support customer-chosen ("pay what you want")
 * amounts on ONE-TIME payments, never on recurring ones. That's why there is a
 * 'custom' link for one-time giving but not for monthly.
 *
 * @package CrossroadsCommons
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

return array(

    // One-time gifts — fixed amounts.
    'one_time' => array(
${oneTimeResults.map(tierLine).join('\n')}
    ),

    // One-time gift of any amount — the donor types the amount on Stripe's page.
    'custom'   => '${customLink.url}',

    // Recurring monthly gifts — fixed amounts only (Stripe limitation).
    'monthly'  => array(
${monthlyResults.map(tierLine).join('\n')}
    ),
);
`;

log('');
log('─'.repeat(72));

const target = resolve(
  dirname(fileURLToPath(import.meta.url)),
  '../wp-content/themes/crossroads-commons/inc/donation-links.php'
);

if (WRITE && !DRY_RUN) {
  writeFileSync(target, php);
  log(`✓ Wrote ${target}`);
  log('  Review the diff, commit, and deploy.');
} else {
  log(php);
  if (DRY_RUN) {
    log('Dry run — no links were created, so the URLs above are blank.');
    log('Re-run with --create (plus --live for a live key) to create them.');
  } else {
    log('Paste the block above over inc/donation-links.php, or re-run with --write.');
  }
}
log('');
