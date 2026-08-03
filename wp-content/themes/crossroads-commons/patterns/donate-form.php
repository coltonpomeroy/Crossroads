<?php
/**
 * Title: Donate Form
 * Slug: crossroads-commons/donate-form
 * Categories: crossroads
 */

$donate_config     = function_exists( 'crossroads_commons_donation_config' ) ? crossroads_commons_donation_config() : array();
$donate_has_url    = static function ( $tier ) {
    return ! empty( $tier['url'] );
};
$donate_one_time   = array_values( array_filter( (array) ( $donate_config['one_time'] ?? array() ), $donate_has_url ) );
$donate_monthly    = array_values( array_filter( (array) ( $donate_config['monthly'] ?? array() ), $donate_has_url ) );
$donate_custom_url = $donate_config['custom'] ?? '';
$donate_ready      = $donate_one_time || $donate_monthly || $donate_custom_url;
?>
<!-- wp:group {"className":"donate-section","tagName":"section","align":"full","layout":{"type":"default"}} -->
<section class="wp-block-group alignfull donate-section"><!-- wp:html -->
<div class="donate-intro">
  <p class="script-callout">Every gift builds something</p>
  <h1>Help Build Crossroads Commons</h1>
  <p>Your gift funds the next phase of community-led revitalization in South OKC — gathering places, small businesses, and green space the whole neighborhood can share.</p>
</div>

<?php if ( ! $donate_ready ) : ?>
<div class="donate-form-card donate-form-card-empty">
  <p class="donate-empty-title">Online giving is coming soon.</p>
  <p class="donate-form-note">We're putting the finishing touches on secure online donations. In the meantime, <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">reach out</a> and we'll walk you through how to give today.</p>
</div>
<?php else : ?>
<div class="donate-form-card">
  <div class="donate-widget" data-freq="one_time">

    <?php if ( $donate_one_time && $donate_monthly ) : ?>
    <div class="donate-freq" role="group" aria-label="Giving frequency">
      <button type="button" class="donate-freq-btn is-active" data-freq="one_time" aria-pressed="true">One-time</button>
      <button type="button" class="donate-freq-btn" data-freq="monthly" aria-pressed="false">Monthly</button>
    </div>
    <?php endif; ?>

    <?php if ( $donate_one_time || $donate_custom_url ) : ?>
    <div class="donate-panel" data-panel="one_time">
      <p class="donate-panel-label">Choose an amount</p>
      <div class="donate-amounts">
        <?php foreach ( $donate_one_time as $tier ) : ?>
        <button type="button" class="donate-amount" aria-pressed="false"
                data-url="<?php echo esc_url( $tier['url'] ); ?>"
                data-amount="<?php echo esc_attr( $tier['amount'] ); ?>">
          <span class="donate-amount-value">$<?php echo esc_html( number_format( (float) $tier['amount'] ) ); ?></span>
          <?php if ( ! empty( $tier['label'] ) ) : ?>
          <span class="donate-amount-label"><?php echo esc_html( $tier['label'] ); ?></span>
          <?php endif; ?>
        </button>
        <?php endforeach; ?>

        <?php if ( $donate_custom_url ) : ?>
        <button type="button" class="donate-amount donate-amount-custom" aria-pressed="false"
                data-url="<?php echo esc_url( $donate_custom_url ); ?>" data-custom="1">
          <span class="donate-amount-value">Other amount</span>
          <span class="donate-amount-label">You choose</span>
        </button>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>

    <?php if ( $donate_monthly ) : ?>
    <div class="donate-panel" data-panel="monthly" hidden>
      <p class="donate-panel-label">Choose a monthly amount</p>
      <div class="donate-amounts">
        <?php foreach ( $donate_monthly as $tier ) : ?>
        <button type="button" class="donate-amount" aria-pressed="false"
                data-url="<?php echo esc_url( $tier['url'] ); ?>"
                data-amount="<?php echo esc_attr( $tier['amount'] ); ?>" data-monthly="1">
          <span class="donate-amount-value">$<?php echo esc_html( number_format( (float) $tier['amount'] ) ); ?><span class="donate-amount-per">/mo</span></span>
          <?php if ( ! empty( $tier['label'] ) ) : ?>
          <span class="donate-amount-label"><?php echo esc_html( $tier['label'] ); ?></span>
          <?php endif; ?>
        </button>
        <?php endforeach; ?>
      </div>
      <p class="donate-form-note donate-monthly-note">Looking to give a different amount each month? <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Get in touch</a> and we'll set it up with you.</p>
    </div>
    <?php endif; ?>

    <button type="button" class="donate-submit" disabled>Choose an amount</button>

    <p class="donate-secure">
      <span class="material-symbols-outlined" aria-hidden="true">lock</span>
      Secure checkout powered by Stripe. Card, Apple&nbsp;Pay, and Google&nbsp;Pay accepted.
    </p>
  </div>
</div>
<?php endif; ?>
<!-- /wp:html --></section>
<!-- /wp:group -->
