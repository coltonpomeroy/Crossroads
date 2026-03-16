<?php
/**
 * Title: Header
 * Slug: crossroads-commons/header
 * Categories: crossroads
 * Block Types: core/template-part/header
 */
$logo_url = esc_url( get_theme_file_uri( 'assets/images/logos/cc-logo-horizontal-color.svg' ) );
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
    <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="nav-cta">Contact</a>
  </div>
  <button class="hamburger" aria-label="Menu">
    <span></span><span></span><span></span>
  </button>
</nav>
<div class="mobile-menu">
  <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a>
  <a href="<?php echo esc_url( home_url( '/media/' ) ); ?>">Media</a>
  <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Blog</a>
  <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="mobile-cta">Contact</a>
</div>
<!-- /wp:html -->
