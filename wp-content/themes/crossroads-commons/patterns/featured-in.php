<?php
/**
 * Title: Featured In
 * Slug: crossroads-commons/featured-in
 * Categories: crossroads
 */
$logos_dir = esc_url( get_theme_file_uri( 'assets/images/logos' ) );
?>
<!-- wp:group {"className":"as-seen-in","tagName":"section","align":"full","layout":{"type":"default"}} -->
<section class="wp-block-group alignfull as-seen-in"><!-- wp:paragraph {"className":"as-seen-label"} -->
<p class="as-seen-label">As Featured In</p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<div class="logo-strip">
  <a href="https://www.koco.com/article/oklahoma-city-crossroads-mall-set-for-transformation/66743864" target="_blank" rel="noopener" class="logo-item">
    <img src="<?php echo $logos_dir; ?>/koco-gray.png" alt="KOCO 5">
  </a>
  <a href="https://www.oklahoman.com/story/business/real-estate/2025/02/18/crossroads-mall-okc-new-owners-hobby-lobby-plans/78867587007/" target="_blank" rel="noopener" class="logo-item">
    <img src="<?php echo $logos_dir; ?>/oklahoman-gray.png" alt="The Oklahoman">
  </a>
  <a href="https://okcfox.com/news/local/crossroads-mall-to-become-resource-hub-for-okc-community" target="_blank" rel="noopener" class="logo-item">
    <img src="<?php echo $logos_dir; ?>/fox25-gray.png" alt="FOX 25">
  </a>
  <a href="https://www.kgou.org/health/2025-09-18/crossroads-mall-has-sat-largely-empty-for-years-a-new-nonprofit-wants-to-turn-it-into-a-community-hub" target="_blank" rel="noopener" class="logo-item">
    <img src="<?php echo $logos_dir; ?>/kgou-gray.png" alt="KGOU - Your NPR Source">
  </a>
  <a href="https://kfor.com/news/local/news-owners-and-plans-for-crossroads-mall/" target="_blank" rel="noopener" class="logo-item">
    <img src="<?php echo $logos_dir; ?>/kfor-wide.svg" alt="KFOR">
  </a>
  <a href="https://www.yahoo.com/news/crossroads-mall-owners-redevelopment-plans-114028906.html" target="_blank" rel="noopener" class="logo-item">
    <img src="<?php echo $logos_dir; ?>/yahoo-gray.png" alt="Yahoo News">
  </a>
</div>
<!-- /wp:html --></section>
<!-- /wp:group -->
