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

    // Custom CSS — version uses file mod-time so every deploy busts cache
    $css_file = get_theme_file_path( 'assets/css/custom.css' );
    $css_ver  = file_exists( $css_file ) ? filemtime( $css_file ) : wp_get_theme()->get( 'Version' );
    wp_enqueue_style(
        'crossroads-custom',
        get_theme_file_uri( 'assets/css/custom.css' ),
        array( 'crossroads-google-fonts', 'crossroads-material-symbols' ),
        $css_ver
    );

    // Custom JS — version uses file mod-time so every deploy busts cache
    $js_file = get_theme_file_path( 'assets/js/custom.js' );
    $js_ver  = file_exists( $js_file ) ? filemtime( $js_file ) : wp_get_theme()->get( 'Version' );
    wp_enqueue_script(
        'crossroads-custom',
        get_theme_file_uri( 'assets/js/custom.js' ),
        array(),
        $js_ver,
        true
    );
}
add_action( 'wp_enqueue_scripts', 'crossroads_commons_enqueue_assets' );

/**
 * Inject MailerLite Universal popup script into <head>.
 */
function crossroads_commons_mailerlite() {
    ?>
    <!-- MailerLite Universal -->
    <script>
        (function(w,d,e,u,f,l,n){w[f]=w[f]||function(){(w[f].q=w[f].q||[])
        .push(arguments);},l=d.createElement(e),l.async=1,l.src=u,
        n=d.getElementsByTagName(e)[0],n.parentNode.insertBefore(l,n);})
        (window,document,'script','https://assets.mailerlite.com/js/universal.js','ml');
        ml('account', '2240989');
    </script>
    <!-- End MailerLite Universal -->
    <?php
}
add_action( 'wp_head', 'crossroads_commons_mailerlite' );

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

/**
 * One-time migration: render pattern content into pages so they are editable
 * in the standard block editor instead of being locked in templates.
 *
 * v2: Re-renders patterns as native WordPress blocks (wp:cover, wp:group,
 * wp:heading, wp:paragraph) so the editor shows visual, editable content
 * instead of raw HTML code blocks.
 */
function crossroads_commons_migrate_pattern_content() {
    if ( get_option( 'crossroads_content_migrated_v10' ) ) {
        return;
    }

    // Page slug => ordered list of pattern files to render into that page.
    $pages = array(
        'home' => array(
            'title'    => 'Home',
            'patterns' => array( 'hero', 'color-bar', 'featured-in', 'video-section', 'places-grid', 'cta-banner' ),
        ),
        'about' => array(
            'title'    => 'About',
            'patterns' => array( 'about-hero', 'color-bar', 'about-story', 'mission-vision', 'board-members', 'faq', 'cta-banner' ),
        ),
        'media' => array(
            'title'    => 'Media',
            'patterns' => array( 'media-hero', 'color-bar', 'media-coverage', 'cta-banner' ),
        ),
        'contact' => array(
            'title'    => 'Contact',
            'patterns' => array( 'contact-hero', 'color-bar', 'contact-content', 'cta-banner' ),
        ),
    );

    foreach ( $pages as $slug => $config ) {
        $page = get_page_by_path( $slug );

        // Render each pattern file in order.
        $content = '';
        foreach ( $config['patterns'] as $pattern_slug ) {
            $file = get_theme_file_path( "patterns/{$pattern_slug}.php" );
            if ( file_exists( $file ) ) {
                ob_start();
                include $file;
                $content .= ob_get_clean();
            }
        }

        if ( $page ) {
            wp_update_post( array(
                'ID'           => $page->ID,
                'post_content' => $content,
            ) );
        } else {
            wp_insert_post( array(
                'post_title'   => $config['title'],
                'post_name'    => $slug,
                'post_content' => $content,
                'post_status'  => 'publish',
                'post_type'    => 'page',
            ) );
        }
    }

    // Ensure a "Blog" page exists for the posts listing.
    $blog_page = get_page_by_path( 'blog' );
    if ( ! $blog_page ) {
        $blog_page_id = wp_insert_post( array(
            'post_title'   => 'Blog',
            'post_name'    => 'blog',
            'post_content' => '',
            'post_status'  => 'publish',
            'post_type'    => 'page',
        ) );
    } else {
        $blog_page_id = $blog_page->ID;
    }

    // Configure Reading Settings: static front page + separate posts page.
    $home_page = get_page_by_path( 'home' );
    if ( $home_page ) {
        update_option( 'show_on_front', 'page' );
        update_option( 'page_on_front', $home_page->ID );
    }
    if ( $blog_page_id ) {
        update_option( 'page_for_posts', $blog_page_id );
    }

    update_option( 'crossroads_content_migrated_v10', true );
}
add_action( 'admin_init', 'crossroads_commons_migrate_pattern_content' );
