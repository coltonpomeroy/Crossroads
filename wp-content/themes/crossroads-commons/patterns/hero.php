<?php
/**
 * Title: Hero
 * Slug: crossroads-commons/hero
 * Categories: crossroads
 */
$hero_img = esc_url( get_theme_file_uri( 'assets/images/hero-vision-day.png' ) );
?>
<!-- wp:cover {"url":"<?php echo $hero_img; ?>","dimRatio":0,"isDark":true,"className":"hero","align":"full"} -->
<div class="wp-block-cover alignfull is-dark hero"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span><img class="wp-block-cover__image-background" alt="Architectural vision of Crossroads Commons — aerial watercolor rendering showing the reimagined campus" src="<?php echo $hero_img; ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"className":"hero-script"} -->
<p class="hero-script">A place to flourish</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">Crossroads<br><span class="highlight">Commons</span></h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"tagline"} -->
<p class="tagline">Dedicating Crossroads Mall to the flourishing of children and families in Oklahoma City.</p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<div class="hero-buttons">
  <a href="#" class="btn btn-magenta">
    <span class="material-symbols-outlined" style="font-size:20px">explore</span>
    Explore the Vision
  </a>
  <a href="/contact/" class="btn btn-outline-light">
    <span class="material-symbols-outlined" style="font-size:20px">mail</span>
    Get in Touch
  </a>
</div>
<!-- /wp:html --></div></div>
<!-- /wp:cover -->
