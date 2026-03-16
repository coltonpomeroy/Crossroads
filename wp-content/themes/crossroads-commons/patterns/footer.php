<?php
/**
 * Title: Footer
 * Slug: crossroads-commons/footer
 * Categories: crossroads
 * Block Types: core/template-part/footer
 */
$logo_white = esc_url( get_theme_file_uri( 'assets/images/logos/cc-logo-horizontal-white.svg' ) );
?>
<!-- wp:html -->
<footer class="site-footer">
  <div class="footer-stripe">
    <span style="background: var(--sky-blue)"></span>
    <span style="background: var(--yellow)"></span>
    <span style="background: var(--magenta)"></span>
    <span style="background: var(--orange)"></span>
    <span style="background: var(--white)"></span>
  </div>
  <div class="footer-grid">
    <div class="footer-brand">
      <img src="<?php echo $logo_white; ?>" alt="Crossroads Commons">
      <p>Reimagining South OKC — a place shaped by the love of God for the flourishing of children and families.</p>
    </div>
    <div class="footer-col">
      <h4>Explore</h4>
      <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a>
      <a href="<?php echo esc_url( home_url( '/media/' ) ); ?>">Media Coverage</a>
      <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Blog</a>
    </div>
    <div class="footer-col">
      <h4>Connect</h4>
      <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact / Inquire</a>
      <a href="#">Instagram</a>
      <a href="#">Facebook</a>
    </div>
    <div class="footer-col">
      <h4>Visit</h4>
      <div class="visit-location">
        <span class="material-symbols-outlined">location_on</span>
        <span>Get Directions</span>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    &copy; <?php echo date( 'Y' ); ?> Crossroads Commons. All rights reserved.
  </div>
</footer>
<!-- /wp:html -->
