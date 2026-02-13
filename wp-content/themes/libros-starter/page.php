<?php
/**
 * Page template — used for WordPress pages (checkout, cart, my-account, etc.)
 */
get_header(); ?>

<main class="site-main">
    <div class="container" style="padding-top: var(--space-10); padding-bottom: var(--space-10);">
        <?php while (have_posts()):
            the_post(); ?>
            <article id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
                <h1 class="section__title">
                    <?php the_title(); ?>
                </h1>
                <div class="entry-content" style="margin-top: var(--space-6);">
                    <?php the_content(); ?>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer();
