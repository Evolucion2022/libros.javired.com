<?php
/**
 * WooCommerce Single Product — Landing Page Router
 *
 * If a landing page template exists at landing-pages/{slug}.php,
 * it is loaded and completely replaces the default product layout.
 * Otherwise, the standard WooCommerce product template is used.
 */

defined('ABSPATH') || exit;

get_header();

while (have_posts()):
    the_post();

    global $product;
    $slug = get_post_field('post_name', get_the_ID());

    if (libros_has_landing_page($slug)) {
        // Load custom landing page template
        include libros_get_landing_path($slug);
    } else {
        // Fallback: standard WooCommerce product layout
        ?>
        <main class="site-main">
            <div class="container" style="padding-top: var(--space-10); padding-bottom: var(--space-10);">
                <?php wc_get_template_part('content', 'single-product'); ?>
            </div>
        </main>
        <?php
    }

endwhile;

get_footer();
