<?php
/**
 * WooCommerce main template.
 * Used for shop/archive pages (not single products).
 */
get_header(); ?>

<main class="site-main">
    <div class="container" style="padding-top: var(--space-10); padding-bottom: var(--space-10);">
        <?php woocommerce_content(); ?>
    </div>
</main>

<?php get_footer();
