<?php
/**
 * Crossroads Commons Theme Functions
 *
 * @package CrossroadsCommons
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue theme styles and scripts.
 */
function crossroads_commons_enqueue_assets() {
    // Google Fonts
    wp_enqueue_style(
        'crossroads-google-fonts',
        'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap',
        array(),
        null
    );

    // Material Symbols
    wp_enqueue_style(
        'crossroads-material-symbols',
        'https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0',
        array(),
        null
    );

    // Custom CSS
    wp_enqueue_style(
        'crossroads-custom',
        get_theme_file_uri( 'assets/css/custom.css' ),
        array( 'crossroads-google-fonts', 'crossroads-material-symbols' ),
        wp_get_theme()->get( 'Version' )
    );

    // Custom JS
    wp_enqueue_script(
        'crossroads-custom',
        get_theme_file_uri( 'assets/js/custom.js' ),
        array(),
        wp_get_theme()->get( 'Version' ),
        true
    );
}
add_action( 'wp_enqueue_scripts', 'crossroads_commons_enqueue_assets' );

/**
 * Register block pattern category and manually register new patterns.
 */
function crossroads_commons_register_patterns() {
    register_block_pattern_category( 'crossroads', array(
        'label' => __( 'Crossroads Commons', 'crossroads-commons' ),
    ) );

    // Manually register patterns that auto-discovery may miss
    $patterns = array(
        'media-hero',
        'media-coverage',
        'blog-hero',
        'contact-hero',
        'contact-content',
    );

    foreach ( $patterns as $pattern ) {
        $file = get_theme_file_path( "patterns/{$pattern}.php" );
        if ( ! file_exists( $file ) ) {
            continue;
        }
        $headers = get_file_data( $file, array(
            'title'      => 'Title',
            'slug'       => 'Slug',
            'categories' => 'Categories',
        ) );
        if ( empty( $headers['slug'] ) ) {
            continue;
        }
        ob_start();
        include $file;
        $content = ob_get_clean();
        register_block_pattern( $headers['slug'], array(
            'title'      => $headers['title'],
            'content'    => $content,
            'categories' => array_map( 'trim', explode( ',', $headers['categories'] ) ),
        ) );
    }
}
add_action( 'init', 'crossroads_commons_register_patterns' );

/**
 * Process shortcodes inside wp:html blocks so plugins like WPForms render properly.
 */
function crossroads_commons_render_shortcodes_in_html( $block_content, $block ) {
    if ( 'core/html' === $block['blockName'] && has_shortcode( $block_content, 'wpforms' ) ) {
        $block_content = do_shortcode( $block_content );
    }
    return $block_content;
}
add_filter( 'render_block', 'crossroads_commons_render_shortcodes_in_html', 10, 2 );
