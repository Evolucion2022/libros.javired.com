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
   4. SHOP CSS + JS (all WC pages except checkout — for header/footer)
   ────────────────────────────────────────────── */
add_action('wp_enqueue_scripts', function () {
    // Skip checkout (has its own CSS)
    if (function_exists('is_checkout') && is_checkout())
        return;

    // Load on shop, categories, cart, account, and any other WC page
    $is_wc_page = (function_exists('is_shop') && is_shop())
        || (function_exists('is_product_taxonomy') && is_product_taxonomy())
        || (function_exists('is_cart') && is_cart())
        || (function_exists('is_account_page') && is_account_page())
        || (function_exists('is_woocommerce') && is_woocommerce());

    if (!$is_wc_page)
        return;

    // Shop CSS (header, drawer, cards, etc.)
    $css_file = get_stylesheet_directory() . '/assets/css/shop.css';
    if (file_exists($css_file)) {
        wp_enqueue_style(
            'libros-shop',
            get_stylesheet_directory_uri() . '/assets/css/shop.css',
            ['libros-starter-child'],
            filemtime($css_file)
        );
    }

    // Home CSS (footer styles)
    $home_css = get_stylesheet_directory() . '/assets/css/home.css';
    if (file_exists($home_css)) {
        wp_enqueue_style(
            'libros-home',
            get_stylesheet_directory_uri() . '/assets/css/home.css',
            ['libros-shop'],
            filemtime($home_css)
        );
    }

    // Shop JS (dark/light toggle, drawer, header scroll)
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

    // Account Dashboard CSS (only on account pages)
    if (function_exists('is_account_page') && is_account_page()) {
        $account_css = get_stylesheet_directory() . '/assets/css/account.css';
        if (file_exists($account_css)) {
            wp_enqueue_style(
                'libros-account',
                get_stylesheet_directory_uri() . '/assets/css/account.css',
                ['libros-shop'],
                filemtime($account_css)
            );
        }
    }

    // Cart CSS (only on cart page)
    if (function_exists('is_cart') && is_cart()) {
        $cart_css = get_stylesheet_directory() . '/assets/css/cart.css';
        if (file_exists($cart_css)) {
            wp_enqueue_style(
                'libros-cart',
                get_stylesheet_directory_uri() . '/assets/css/cart.css',
                ['libros-shop'],
                filemtime($cart_css)
            );
        }
    }
}, 999);


/* ──────────────────────────────────────────────
   5. GOOGLE FONTS (all WC pages except checkout)
   ────────────────────────────────────────────── */
add_action('wp_enqueue_scripts', function () {
    if (function_exists('is_checkout') && is_checkout())
        return;

    $is_wc_page = (function_exists('is_shop') && is_shop())
        || (function_exists('is_product_taxonomy') && is_product_taxonomy())
        || (function_exists('is_cart') && is_cart())
        || (function_exists('is_account_page') && is_account_page())
        || (function_exists('is_woocommerce') && is_woocommerce());

    if (!$is_wc_page)
        return;

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


/* ──────────────────────────────────────────────
   7. HOME PAGE CSS + JS (only on front page)
   ────────────────────────────────────────────── */
add_action('wp_enqueue_scripts', function () {
    if (!is_front_page())
        return;

    // Home CSS —  also load shop.css for shared header/drawer styles
    $shop_css = get_stylesheet_directory() . '/assets/css/shop.css';
    if (file_exists($shop_css)) {
        wp_enqueue_style(
            'libros-shop',
            get_stylesheet_directory_uri() . '/assets/css/shop.css',
            ['libros-starter-child'],
            filemtime($shop_css)
        );
    }

    $home_css = get_stylesheet_directory() . '/assets/css/home.css';
    if (file_exists($home_css)) {
        wp_enqueue_style(
            'libros-home',
            get_stylesheet_directory_uri() . '/assets/css/home.css',
            ['libros-shop'],
            filemtime($home_css)
        );
    }

    // Home JS
    $home_js = get_stylesheet_directory() . '/assets/js/home.js';
    if (file_exists($home_js)) {
        wp_enqueue_script(
            'libros-home',
            get_stylesheet_directory_uri() . '/assets/js/home.js',
            [],
            filemtime($home_js),
            true
        );
    }
}, 999);


/* ──────────────────────────────────────────────
   8. GOOGLE FONTS FOR HOME
   ────────────────────────────────────────────── */
add_action('wp_enqueue_scripts', function () {
    if (!is_front_page())
        return;

    wp_enqueue_style(
        'libros-home-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@400;600;700;800&family=Poppins:wght@400;500;600;700&display=swap',
        [],
        null
    );
});


/* ──────────────────────────────────────────────
   9. CUSTOM TEMPLATES FOR WC PAGES (account, cart)
   ────────────────────────────────────────────── */
add_filter('template_include', function ($template) {
    // My Account page → use our custom template with header + footer
    if (function_exists('is_account_page') && is_account_page()) {
        $custom = get_stylesheet_directory() . '/template-account.php';
        if (file_exists($custom)) {
            return $custom;
        }
    }

    // Cart page → use our custom cart template
    if (function_exists('is_cart') && is_cart()) {
        $custom = get_stylesheet_directory() . '/template-cart.php';
        if (file_exists($custom)) {
            return $custom;
        }
    }

    return $template;
}, 999);


/* ──────────────────────────────────────────────
   10. WC AJAX ADD-TO-CART + SIDE CART INTEGRATION
   ────────────────────────────────────────────── */

// Enable WooCommerce AJAX add-to-cart on archive pages
add_filter('woocommerce_product_add_to_cart_url', function ($url, $product) {
    if ($product->is_type('simple')) {
        return $product->add_to_cart_url();
    }
    return $url;
}, 10, 2);

// Disable redirect after add to cart (let side cart handle it)
add_filter('woocommerce_add_to_cart_redirect', '__return_false');

// Ensure AJAX add-to-cart fragments are supported
add_theme_support('wc-product-gallery-zoom');
add_theme_support('wc-product-gallery-lightbox');
add_theme_support('wc-product-gallery-slider');

// Force WooCommerce to enable AJAX add-to-cart on archives
add_filter('pre_option_woocommerce_enable_ajax_add_to_cart', function () {
    return 'yes';
});

// Disable WC redirect to cart after add
add_filter('pre_option_woocommerce_cart_redirect_after_add', function () {
    return 'no';
});


/* ──────────────────────────────────────────────
   11. CUSTOM CSS FOR SIDE CART PREMIUM PLUGIN
   ────────────────────────────────────────────── */
add_action('wp_head', function () {
    if (!class_exists('Xoo_Wsc_Loader'))
        return;
    ?>
    <style id="sidecart-premium-overrides">
        /* ── Side Cart Container ── */
        .xoo-wsc-container {
            font-family: 'Inter', -apple-system, sans-serif !important;
        }

        /* ── Header ── */
        .xoo-wsc-header {
            background: #14532D !important;
            border-bottom: none !important;
            padding: 20px 24px !important;
        }

        .xoo-wsc-header span,
        .xoo-wsc-header .xoo-wsc-ctxt {
            color: #FFFFFF !important;
            font-weight: 700 !important;
            font-size: 1.15rem !important;
            letter-spacing: .3px;
        }

        .xoo-wsc-header .xoo-wsc-icon-cross {
            color: #FFFFFF !important;
            opacity: .7;
            transition: opacity .2s;
        }

        .xoo-wsc-header .xoo-wsc-icon-cross:hover {
            opacity: 1;
        }

        /* ── Body / Product List ── */
        .xoo-wsc-body {
            background: #FAFAF8 !important;
            padding: 16px !important;
        }

        /* ── Individual Product Items ── */
        .xoo-wsc-product {
            background: #FFFFFF !important;
            border: 1px solid #E2E8F0 !important;
            border-radius: 12px !important;
            padding: 16px !important;
            margin-bottom: 12px !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .05) !important;
            transition: box-shadow .3s ease !important;
        }

        .xoo-wsc-product:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, .1) !important;
        }

        /* Product Image */
        .xoo-wsc-img-col img {
            border-radius: 8px !important;
            border: 1px solid #E2E8F0 !important;
        }

        /* Product Name */
        .xoo-wsc-pname a {
            color: #1B1B1F !important;
            font-weight: 600 !important;
            font-size: .95rem !important;
            text-decoration: none !important;
        }

        .xoo-wsc-pname a:hover {
            color: #14532D !important;
        }

        /* Product Price */
        .xoo-wsc-pprice,
        .xoo-wsc-pprice .amount {
            color: #14532D !important;
            font-weight: 700 !important;
        }

        /* Quantity Controls */
        .xoo-wsc-qty input {
            border-radius: 8px !important;
            border: 2px solid #E2E8F0 !important;
            font-weight: 600 !important;
        }

        .xoo-wsc-qty input:focus {
            border-color: #14532D !important;
        }

        /* Remove Button */
        .xoo-wsc-icon-del {
            color: #EF4444 !important;
            opacity: .5 !important;
            transition: all .2s !important;
        }

        .xoo-wsc-icon-del:hover {
            opacity: 1 !important;
            transform: scale(1.2) !important;
        }

        /* ── Footer / Totals ── */
        .xoo-wsc-footer {
            background: #FFFFFF !important;
            border-top: 2px solid #E2E8F0 !important;
            padding: 20px 24px !important;
        }

        .xoo-wsc-ft-totals {
            font-size: 1.1rem !important;
            font-weight: 700 !important;
            color: #1B1B1F !important;
        }

        .xoo-wsc-ft-amt,
        .xoo-wsc-ft-amt .amount {
            color: #14532D !important;
            font-weight: 700 !important;
        }

        /* Checkout Button */
        .xoo-wsc-ft-btn a,
        .xoo-wsc-ft-btn button {
            background: #14532D !important;
            color: #FFFFFF !important;
            border: none !important;
            border-radius: 10px !important;
            font-weight: 700 !important;
            font-size: 1rem !important;
            padding: 14px 24px !important;
            transition: all .3s ease !important;
            text-transform: none !important;
            letter-spacing: .3px;
        }

        .xoo-wsc-ft-btn a:hover,
        .xoo-wsc-ft-btn button:hover {
            background: #166534 !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 14px rgba(20, 83, 45, .3) !important;
        }

        /* View Cart Button */
        .xoo-wsc-ft-btn-cart a {
            background: transparent !important;
            color: #14532D !important;
            border: 2px solid #14532D !important;
        }

        .xoo-wsc-ft-btn-cart a:hover {
            background: rgba(20, 83, 45, .06) !important;
        }

        /* ── Empty Cart ── */
        .xoo-wsc-empty-cart {
            color: #64748B !important;
            font-size: 1.05rem !important;
        }

        /* ── Overlay ── */
        .xoo-wsc-overlay {
            background: rgba(0, 0, 0, .5) !important;
            backdrop-filter: blur(2px);
        }

        /* ── Notification ── */
        .xoo-wsc-notice .xoo-wsc-nt-success {
            background: #14532D !important;
            color: #FFFFFF !important;
            border-radius: 8px !important;
        }

        /* ═══ DARK MODE ═══ */
        [data-theme="dark"] .xoo-wsc-header {
            background: #1C2128 !important;
            border-bottom: 1px solid #2D3748 !important;
        }

        [data-theme="dark"] .xoo-wsc-body {
            background: #0F1419 !important;
        }

        [data-theme="dark"] .xoo-wsc-product {
            background: #1C2128 !important;
            border-color: #2D3748 !important;
        }

        [data-theme="dark"] .xoo-wsc-pname a {
            color: #E6E1DB !important;
        }

        [data-theme="dark"] .xoo-wsc-pname a:hover {
            color: #4ADE80 !important;
        }

        [data-theme="dark"] .xoo-wsc-pprice,
        [data-theme="dark"] .xoo-wsc-pprice .amount {
            color: #4ADE80 !important;
        }

        [data-theme="dark"] .xoo-wsc-img-col img {
            border-color: #2D3748 !important;
        }

        [data-theme="dark"] .xoo-wsc-qty input {
            background: #161B22 !important;
            border-color: #2D3748 !important;
            color: #E6E1DB !important;
        }

        [data-theme="dark"] .xoo-wsc-footer {
            background: #1C2128 !important;
            border-top-color: #2D3748 !important;
        }

        [data-theme="dark"] .xoo-wsc-ft-totals {
            color: #E6E1DB !important;
        }

        [data-theme="dark"] .xoo-wsc-ft-amt,
        [data-theme="dark"] .xoo-wsc-ft-amt .amount {
            color: #4ADE80 !important;
        }

        [data-theme="dark"] .xoo-wsc-ft-btn a,
        [data-theme="dark"] .xoo-wsc-ft-btn button {
            background: #4ADE80 !important;
            color: #0D1210 !important;
        }

        [data-theme="dark"] .xoo-wsc-ft-btn a:hover,
        [data-theme="dark"] .xoo-wsc-ft-btn button:hover {
            background: #22C55E !important;
            box-shadow: 0 4px 14px rgba(74, 222, 128, .2) !important;
        }

        [data-theme="dark"] .xoo-wsc-ft-btn-cart a {
            color: #4ADE80 !important;
            border-color: #4ADE80 !important;
            background: transparent !important;
        }

        [data-theme="dark"] .xoo-wsc-ft-btn-cart a:hover {
            background: rgba(74, 222, 128, .1) !important;
        }

        [data-theme="dark"] .xoo-wsc-empty-cart {
            color: #9BA3A6 !important;
        }
    </style>
    <?php
}, 999);
