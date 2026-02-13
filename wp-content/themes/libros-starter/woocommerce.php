<?php
/**
 * WooCommerce Master Template
 * 
 * This is the entry point for ALL WooCommerce pages.
 * When woocommerce.php exists in the theme root, WooCommerce
 * uses it for EVERYTHING: shop, archives, AND single products.
 * 
 * So we must handle routing HERE — checking if the product
 * has a custom landing page template.
 */

defined('ABSPATH') || exit;

if (is_product()) {
    // === SINGLE PRODUCT ===
    global $post;
    $slug = $post->post_name;

    if (libros_has_landing_page($slug)) {
        // Custom landing page exists — load it directly
        // No header/footer wrappers, no WooCommerce containers
        get_header();
        include libros_get_landing_path($slug);
        get_footer();
    } else {
        // No custom landing page — use default WooCommerce layout
        get_header(); ?>
        <main class="site-main">
            <div class="container" style="padding-top: var(--space-10); padding-bottom: var(--space-10);">
                <?php woocommerce_content(); ?>
            </div>
        </main>
        <?php get_footer();
    }

} else {
    // === SHOP / ARCHIVE / CATEGORY PAGES ===
    get_header(); ?>
    <main class="site-main">
        <div class="container" style="padding-top: var(--space-10); padding-bottom: var(--space-10);">
            <?php woocommerce_content(); ?>
        </div>
    </main>
    <?php get_footer();
}
