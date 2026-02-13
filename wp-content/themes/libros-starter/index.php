<?php
/**
 * Default template — blog / generic pages fallback.
 */
get_header(); ?>

<main class="site-main">
    <div class="container" style="padding-top: var(--space-10); padding-bottom: var(--space-10);">
        <?php if (have_posts()):
            while (have_posts()):
                the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <h1 class="section__title">
                        <?php the_title(); ?>
                    </h1>
                    <div class="entry-content" style="margin-top: var(--space-6); line-height: var(--leading-relaxed);">
                        <?php the_content(); ?>
                    </div>
                </article>
            <?php endwhile; else: ?>
            <p>No se encontró contenido.</p>
        <?php endif; ?>
    </div>
</main>

<?php get_footer();
