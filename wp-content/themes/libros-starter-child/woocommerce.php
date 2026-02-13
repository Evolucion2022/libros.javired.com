<?php
/**
 * WooCommerce Template Override (Child Theme)
 *
 * This file overrides the parent theme's woocommerce.php.
 * For shop/category archive pages → loads our custom premium template.
 * For other WooCommerce pages (cart, checkout, account) → uses default.
 */

if (!defined('ABSPATH'))
    exit;

// Shop page and product category archives: use our custom premium template
if (is_shop() || is_product_taxonomy()) {
    include get_stylesheet_directory() . '/woocommerce/archive-product.php';
    return;
}

// All other WooCommerce pages: use standard wrapper
get_header();
?>

<main class="site-main">
    <div class="container" style="padding-top: var(--space-10); padding-bottom: var(--space-10);">
        <?php woocommerce_content(); ?>
    </div>
</main>

<?php get_footer();
