<?php
/**
 * Title: Hero
 * Slug: crossroads-commons/hero
 * Categories: crossroads
 */
$hero_img = esc_url( get_theme_file_uri( 'assets/images/hero-vision-day.png' ) );
?>
<!-- wp:html -->
<section class="hero">
  <div class="hero-bg">
    <img src="<?php echo $hero_img; ?>" alt="Architectural vision of Crossroads Commons — aerial watercolor rendering showing the reimagined campus">
  </div>
  <div class="hero-content">
    <p class="hero-script">A place to flourish</p>
    <h1>Crossroads<br><span class="highlight">Commons</span></h1>
    <p class="tagline">Dedicating Crossroads Mall to the flourishing of children and families in Oklahoma City.</p>
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
  </div>
</section>
<!-- /wp:html -->
