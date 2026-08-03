<?php
/**
 * Stripe Payment Link configuration for the Donate page.
 *
 * Each amount tier maps to one Stripe Payment Link. Paste the URLs Stripe gives
 * you (they look like https://buy.stripe.com/xxxxxxxxxxxx) into the 'url' slots
 * below. Generate them all at once with:
 *
 *     STRIPE_SECRET_KEY=sk_live_... node scripts/create-stripe-payment-links.mjs --live
 *
 * ...which prints this exact array, filled in, ready to paste over the one here.
 *
 * Leave a 'url' empty to hide that tier. If EVERY url is empty, the Donate page
 * shows a "coming soon" notice and the Donate nav item / CTA stay hidden — so
 * this file is safe to deploy before the Stripe links exist.
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
        array( 'amount' => 25,  'label' => 'Neighbor',    'url' => '' ),
        array( 'amount' => 50,  'label' => 'Friend',      'url' => '' ),
        array( 'amount' => 100, 'label' => 'Supporter',   'url' => '' ),
        array( 'amount' => 250, 'label' => 'Builder',     'url' => '' ),
        array( 'amount' => 500, 'label' => 'Cornerstone', 'url' => '' ),
    ),

    // One-time gift of any amount — the donor types the amount on Stripe's page.
    'custom'   => '',

    // Recurring monthly gifts — fixed amounts only (Stripe limitation).
    'monthly'  => array(
        array( 'amount' => 10,  'label' => '',            'url' => '' ),
        array( 'amount' => 25,  'label' => 'Neighbor',    'url' => '' ),
        array( 'amount' => 50,  'label' => 'Friend',      'url' => '' ),
        array( 'amount' => 100, 'label' => 'Supporter',   'url' => '' ),
        array( 'amount' => 250, 'label' => 'Builder',     'url' => '' ),
    ),
);
