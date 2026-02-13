<?php
/**
 * Product Landing Pages — Mu-Plugin
 *
 * Intercepts WooCommerce product pages and serves custom
 * self-contained landing pages when available.
 *
 * Landing pages are stored in: wp-content/product-landing-pages/{slug}.php
 * Each landing page is SELF-CONTAINED (outputs its own HTML, head, body).
 *
 * This approach works with ANY theme because it bypasses the
 * entire WordPress template system via template_redirect.
 */

if (!defined('ABSPATH'))
    exit;

add_action('template_redirect', function () {
    if (!is_product())
        return;

    global $post;
    if (!$post)
        return;

    $slug = $post->post_name;
    $landing_file = WP_CONTENT_DIR . '/product-landing-pages/' . $slug . '.php';

    if (!file_exists($landing_file))
        return;

    // Setup WooCommerce product global
    if (class_exists('WooCommerce')) {
        wc_setup_loop();
        global $product;
        if (!$product || !is_a($product, 'WC_Product')) {
            $product = wc_get_product($post->ID);
        }
    }

    // Serve the self-contained landing page
    include $landing_file;
    exit; // Stop WordPress from rendering its own template
}, 1); // Priority 1 = run very early
