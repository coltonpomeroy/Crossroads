<?php
/**
 * Title: Thank You
 * Slug: crossroads-commons/thank-you
 * Categories: crossroads
 *
 * Landing page donors are redirected to after completing Stripe Checkout.
 * Set this as the "After payment" redirect URL on every Stripe Payment Link.
 */
?>
<!-- wp:group {"className":"thankyou-section","tagName":"section","align":"full","layout":{"type":"default"}} -->
<section class="wp-block-group alignfull thankyou-section"><!-- wp:html -->
<div class="thankyou-inner">
  <span class="thankyou-mark material-symbols-outlined" aria-hidden="true">favorite</span>
  <p class="script-callout">Thank you</p>
  <h1>Your gift is on its way to work.</h1>
  <p class="thankyou-lead">Stripe has emailed your receipt — keep it for your records. Every dollar goes toward gathering places, small businesses, and green space in South Oklahoma City.</p>
  <div class="thankyou-actions">
    <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="btn btn-magenta">See what we're building</a>
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-outline-dark">Back to home</a>
  </div>
</div>
<!-- /wp:html --></section>
<!-- /wp:group -->
