<?php
/**
 * Title: Board Members
 * Slug: crossroads-commons/board-members
 * Categories: crossroads
 */
$board_dir = esc_url( get_theme_file_uri( 'assets/images/board' ) );
?>
<!-- wp:group {"className":"board","tagName":"section","align":"wide","layout":{"type":"default"}} -->
<section class="wp-block-group alignwide board"><!-- wp:paragraph {"className":"section-label"} -->
<p class="section-label">Leadership</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2,"className":"section-title"} -->
<h2 class="wp-block-heading section-title">Our Board</h2>
<!-- /wp:heading -->

<!-- wp:html -->
<div class="board-grid">
  <div class="board-member">
    <div class="avatar">
      <img src="<?php echo $board_dir; ?>/chris-brewster.png" alt="Chris Brewster">
    </div>
    <h4>Chris Brewster</h4>
  </div>
  <div class="board-member">
    <div class="avatar">
      <img src="<?php echo $board_dir; ?>/everado-borunda.jpg" alt="Everado Borunda" style="object-position: center 20%;">
    </div>
    <h4>Everado Borunda</h4>
  </div>
  <div class="board-member">
    <div class="avatar">
      <img src="<?php echo $board_dir; ?>/tyler-green.jpg" alt="Tyler Green" style="object-position: center 5%;">
    </div>
    <h4>Tyler Green</h4>
  </div>
  <div class="board-member">
    <div class="avatar">
      <img src="<?php echo $board_dir; ?>/reid-hebert.jpg" alt="Reid Hebert">
    </div>
    <h4>Reid Hebert</h4>
  </div>
  <div class="board-member">
    <div class="avatar">
      <img src="<?php echo $board_dir; ?>/blair-humphreys.jpg" alt="Blair Humphreys">
    </div>
    <h4>Blair Humphreys</h4>
  </div>
  <div class="board-member">
    <div class="avatar">
      <img src="<?php echo $board_dir; ?>/kim-swyden.jpeg" alt="Kim Swyden">
    </div>
    <h4>Kim Swyden</h4>
  </div>
</div>
<!-- /wp:html --></section>
<!-- /wp:group -->
