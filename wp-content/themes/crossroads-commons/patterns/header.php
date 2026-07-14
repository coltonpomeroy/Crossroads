<?php
/**
 * Title: Header
 * Slug: crossroads-commons/header
 * Categories: crossroads
 * Block Types: core/template-part/header
 */
$logo_url = esc_url( get_theme_file_uri( 'assets/images/logos/cc-logo-horizontal-color.svg' ) );
// Only surface the Donate link once the Donate page is published.
$donate_live = function_exists( 'crossroads_commons_donate_page_live' ) && crossroads_commons_donate_page_live();
?>
<!-- wp:html -->
<nav class="site-nav">
  <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
    <img src="<?php echo $logo_url; ?>" alt="Crossroads Commons" class="nav-logo">
  </a>
  <div class="nav-links">
    <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a>
    <a href="<?php echo esc_url( home_url( '/media/' ) ); ?>">Media</a>
    <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Blog</a>
    <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"<?php echo $donate_live ? '' : ' class="nav-cta"'; ?>>Contact</a>
    <?php if ( $donate_live ) : ?>
    <a href="<?php echo esc_url( home_url( '/donate/' ) ); ?>" class="nav-cta">Donate</a>
    <?php endif; ?>
  </div>
  <button class="hamburger" aria-label="Menu">
    <span></span><span></span><span></span>
  </button>
</nav>
<div class="mobile-menu">
  <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a>
  <a href="<?php echo esc_url( home_url( '/media/' ) ); ?>">Media</a>
  <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Blog</a>
  <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"<?php echo $donate_live ? '' : ' class="mobile-cta"'; ?>>Contact</a>
  <?php if ( $donate_live ) : ?>
  <a href="<?php echo esc_url( home_url( '/donate/' ) ); ?>" class="mobile-cta">Donate</a>
  <?php endif; ?>
</div>
<!-- /wp:html -->
