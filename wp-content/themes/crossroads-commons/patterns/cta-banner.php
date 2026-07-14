<?php
/**
 * Title: CTA Banner
 * Slug: crossroads-commons/cta-banner
 * Categories: crossroads
 */
$cta_donate_live = function_exists( 'crossroads_commons_donate_page_live' ) && crossroads_commons_donate_page_live();
?>
<!-- wp:group {"className":"cta-banner","tagName":"section","align":"full","layout":{"type":"default"}} -->
<section class="wp-block-group alignfull cta-banner"><!-- wp:group {"className":"inner","layout":{"type":"default"}} -->
<div class="wp-block-group inner"><!-- wp:paragraph {"className":"script-callout"} -->
<p class="script-callout">Be part of what's next</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Help Us Build Something Beautiful</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Crossroads Commons is taking shape — and there's a place for you in this story. Get involved as we reimagine South OKC together.</p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<div class="cta-buttons">
  <?php if ( $cta_donate_live ) : ?>
  <a href="/donate/" class="btn btn-white">Donate Today</a>
  <a href="/contact/" class="btn" style="background: transparent; color: var(--white); border: 2px solid rgba(255,255,255,0.5);">Get Involved</a>
  <?php else : ?>
  <a href="/contact/" class="btn btn-white">Inquire Today</a>
  <a href="/about/" class="btn" style="background: transparent; color: var(--white); border: 2px solid rgba(255,255,255,0.5);">Learn More</a>
  <?php endif; ?>
</div>
<!-- /wp:html --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
