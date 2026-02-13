<?php
/**
 * Libros Starter Child — functions.php
 *
 * Enqueues parent theme styles and child theme overrides.
 * Checkout-specific CSS is loaded only on the checkout page.
 */

if (!defined('ABSPATH'))
    exit;

/* ──────────────────────────────────────────────
   1. ENQUEUE PARENT + CHILD STYLES
   ────────────────────────────────────────────── */
add_action('wp_enqueue_scripts', function () {
    // Parent theme styles
    wp_enqueue_style(
        'libros-starter-parent',
        get_template_directory_uri() . '/style.css',
        [],
        wp_get_theme()->parent()->get('Version')
    );

    // Child theme style.css (general overrides)
    wp_enqueue_style(
        'libros-starter-child',
        get_stylesheet_directory_uri() . '/style.css',
        ['libros-starter-parent'],
        wp_get_theme()->get('Version')
    );
});


/* ──────────────────────────────────────────────
   2. CHECKOUT-SPECIFIC CSS (only on checkout page)
   ────────────────────────────────────────────── */
add_action('wp_enqueue_scripts', function () {
    if (!function_exists('is_checkout') || !is_checkout())
        return;

    $css_file = get_stylesheet_directory() . '/assets/css/checkout.css';
    if (file_exists($css_file)) {
        wp_enqueue_style(
            'libros-checkout',
            get_stylesheet_directory_uri() . '/assets/css/checkout.css',
            ['woocommerce-general', 'libros-starter-child'],
            filemtime($css_file)
        );
    }
}, 999);


/* ──────────────────────────────────────────────
   3. GOOGLE FONTS FOR CHECKOUT
   ────────────────────────────────────────────── */
add_action('wp_enqueue_scripts', function () {
    if (!function_exists('is_checkout') || !is_checkout())
        return;

    wp_enqueue_style(
        'libros-checkout-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap',
        [],
        null
    );
});


/* ──────────────────────────────────────────────
   4. SHOP PAGE CSS + JS (only on shop/category archive)
   ────────────────────────────────────────────── */
add_action('wp_enqueue_scripts', function () {
    if (
        !(function_exists('is_shop') && is_shop()) &&
        !(function_exists('is_product_taxonomy') && is_product_taxonomy())
    ) {
        return;
    }

    // Shop CSS
    $css_file = get_stylesheet_directory() . '/assets/css/shop.css';
    if (file_exists($css_file)) {
        wp_enqueue_style(
            'libros-shop',
            get_stylesheet_directory_uri() . '/assets/css/shop.css',
            ['libros-starter-child'],
            filemtime($css_file)
        );
    }

    // Shop JS
    $js_file = get_stylesheet_directory() . '/assets/js/shop.js';
    if (file_exists($js_file)) {
        wp_enqueue_script(
            'libros-shop',
            get_stylesheet_directory_uri() . '/assets/js/shop.js',
            [],
            filemtime($js_file),
            true
        );
    }
}, 999);


/* ──────────────────────────────────────────────
   5. GOOGLE FONTS FOR SHOP
   ────────────────────────────────────────────── */
add_action('wp_enqueue_scripts', function () {
    if (
        !(function_exists('is_shop') && is_shop()) &&
        !(function_exists('is_product_taxonomy') && is_product_taxonomy())
    ) {
        return;
    }

    wp_enqueue_style(
        'libros-shop-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@400;600;700;800&family=Poppins:wght@400;500;600;700&display=swap',
        [],
        null
    );
});


/* ──────────────────────────────────────────────
   6. SHOW ALL PRODUCTS ON SHOP PAGE
   ────────────────────────────────────────────── */
add_filter('loop_shop_per_page', function () {
    return 100; // Show all 78 products on one page
});
