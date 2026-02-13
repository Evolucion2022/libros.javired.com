<?php
/**
 * Libros Starter Theme — functions.php
 *
 * WooCommerce support, asset loading, product landing page system.
 */

if (!defined('ABSPATH'))
    exit;

define('LIBROS_VERSION', '1.0.0');
define('LIBROS_DIR', get_template_directory());
define('LIBROS_URI', get_template_directory_uri());

/* ──────────────────────────────────────────────
   1. THEME SETUP
   ────────────────────────────────────────────── */
add_action('after_setup_theme', function () {
    // WooCommerce support
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-slider');
    add_theme_support('wc-product-gallery-lightbox');

    // Core support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);
    add_theme_support('custom-logo');

    // Image sizes
    add_image_size('hero-cover', 560, 740, false);
    add_image_size('bonus-thumb', 280, 370, false);

    // Nav menus
    register_nav_menus([
        'primary' => __('Menú Principal', 'libros-starter'),
        'footer' => __('Menú Footer', 'libros-starter'),
    ]);
});


/* ──────────────────────────────────────────────
   2. ENQUEUE ASSETS
   ────────────────────────────────────────────── */
add_action('wp_enqueue_scripts', function () {
    // Google Fonts
    wp_enqueue_style(
        'libros-google-fonts',
        'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;0,800;1,400&family=Inter:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap',
        [],
        null
    );

    // Design system CSS
    $css_files = ['variables', 'base', 'layout', 'components', 'animations'];
    foreach ($css_files as $file) {
        $path = LIBROS_DIR . '/assets/css/' . $file . '.css';
        if (file_exists($path)) {
            wp_enqueue_style(
                'libros-' . $file,
                LIBROS_URI . '/assets/css/' . $file . '.css',
                [],
                filemtime($path)
            );
        }
    }

    // WooCommerce overrides
    if (class_exists('WooCommerce')) {
        $wc_css = LIBROS_DIR . '/assets/css/woocommerce.css';
        if (file_exists($wc_css)) {
            wp_enqueue_style(
                'libros-woocommerce',
                LIBROS_URI . '/assets/css/woocommerce.css',
                ['woocommerce-general'],
                filemtime($wc_css)
            );
        }
    }

    // Main JS
    $js_path = LIBROS_DIR . '/assets/js/main.js';
    if (file_exists($js_path)) {
        wp_enqueue_script(
            'libros-main',
            LIBROS_URI . '/assets/js/main.js',
            [],
            filemtime($js_path),
            true
        );
    }
});


/* ──────────────────────────────────────────────
   3. PRODUCT LANDING PAGE SYSTEM
   ────────────────────────────────────────────── */

/**
 * Check if the current product has a custom landing page template.
 */
function libros_has_landing_page($slug = null)
{
    if (!$slug) {
        global $post;
        if (!$post)
            return false;
        $slug = $post->post_name;
    }
    return file_exists(LIBROS_DIR . '/landing-pages/' . $slug . '.php');
}

/**
 * Get the landing page template path for a product.
 */
function libros_get_landing_path($slug)
{
    return LIBROS_DIR . '/landing-pages/' . $slug . '.php';
}


/* ──────────────────────────────────────────────
   4. WOOCOMMERCE OVERRIDES
   ────────────────────────────────────────────── */

// Remove default WooCommerce wrappers
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

// Add our own wrappers
add_action('woocommerce_before_main_content', function () {
    echo '<main class="site-main">';
}, 10);

add_action('woocommerce_after_main_content', function () {
    echo '</main>';
}, 10);

// Remove sidebar from WooCommerce pages
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);


/* ──────────────────────────────────────────────
   5. HELPER FUNCTIONS
   ────────────────────────────────────────────── */

/**
 * Get the add-to-cart URL for a product.
 */
function libros_add_to_cart_url($product_id = null)
{
    if (!$product_id) {
        global $product;
        if ($product) {
            $product_id = $product->get_id();
        }
    }
    if (!$product_id)
        return '#';

    return esc_url(wc_get_checkout_url() . '?add-to-cart=' . $product_id);
}

/**
 * Direct checkout URL (add to cart + go to checkout).
 */
function libros_direct_checkout_url($product_id = null)
{
    if (!$product_id) {
        global $product;
        if ($product) {
            $product_id = $product->get_id();
        }
    }
    if (!$product_id)
        return '#';

    return esc_url(add_query_arg('add-to-cart', $product_id, wc_get_checkout_url()));
}

/**
 * Get product image URL by attachment ID.
 */
function libros_get_image_url($attachment_id, $size = 'full')
{
    $img = wp_get_attachment_image_src($attachment_id, $size);
    return $img ? $img[0] : '';
}


/* ──────────────────────────────────────────────
   6. DISABLE COMING SOON / MAINTENANCE
   ────────────────────────────────────────────── */
add_filter('woocommerce_coming_soon_exclude', '__return_true');

// Disable WooCommerce "coming soon" mode if it's set
add_action('init', function () {
    if (get_option('woocommerce_coming_soon') === 'yes') {
        update_option('woocommerce_coming_soon', 'no');
    }
});
