<?php
/**
 * WooCommerce Shop Archive Template (Child Theme Override)
 * Premium design with hero, categories, product grid, footer.
 *
 * @package libros-starter-child
 */
if (!defined('ABSPATH'))
    exit;

get_header();

// Get categories for filter bar
$categories = get_terms([
    'taxonomy' => 'product_cat',
    'hide_empty' => true,
    'orderby' => 'name',
    'order' => 'ASC',
    'exclude' => [get_option('default_product_cat')],
]);

// Current category filter
$current_cat = is_product_category() ? get_queried_object() : null;

// Count published products
$product_count = wp_count_posts('product');
$total_products = $product_count->publish;
?>

<?php
// Include custom shop header
include get_stylesheet_directory() . '/header-shop.php';
?>

<!-- ═══ HERO BANNER ═══ -->
<section class="shop-hero">
    <div class="shop-hero__content">
        <div class="shop-hero__badge">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path
                    d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
            </svg>
            Biblioteca Digital Premium
        </div>
        <h1 class="shop-hero__title">
            <?php if ($current_cat): ?>
                <?php echo esc_html($current_cat->name); ?>
            <?php else: ?>
                Nuestra Colección
            <?php endif; ?>
        </h1>
        <p class="shop-hero__subtitle">
            <?php if ($current_cat && $current_cat->description): ?>
                <?php echo esc_html($current_cat->description); ?>
            <?php else: ?>
                Descubre ebooks transformadores escritos por expertos. Conocimiento práctico que puedes aplicar hoy mismo.
            <?php endif; ?>
        </p>

        <!-- Search -->
        <form class="shop-hero__search" action="<?php echo esc_url(home_url('/')); ?>" method="get" role="search">
            <input type="text" name="s" placeholder="Buscar por título, autor o tema..."
                value="<?php echo esc_attr(get_search_query()); ?>" aria-label="Buscar libros">
            <input type="hidden" name="post_type" value="product">
            <button type="submit" aria-label="Buscar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8" />
                    <path d="M21 21l-4.35-4.35" />
                </svg>
            </button>
        </form>

        <!-- Stats -->
        <div class="shop-hero__stats">
            <div class="shop-hero__stat">
                <span class="shop-hero__stat-number">
                    <?php echo esc_html($total_products); ?>
                </span>
                <span class="shop-hero__stat-label">Libros</span>
            </div>
            <div class="shop-hero__stat">
                <span class="shop-hero__stat-number">
                    <?php echo esc_html(count($categories)); ?>
                </span>
                <span class="shop-hero__stat-label">Categorías</span>
            </div>
            <div class="shop-hero__stat">
                <span class="shop-hero__stat-number">100%</span>
                <span class="shop-hero__stat-label">Digital</span>
            </div>
        </div>
    </div>
</section>

<!-- ═══ CATEGORY FILTER BAR ═══ -->
<div class="shop-categories" id="shopCategories">
    <div class="shop-categories__inner">
        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>"
            class="shop-category-pill <?php echo !$current_cat ? 'is-active' : ''; ?>">
            Todos
        </a>
        <?php if (!empty($categories) && !is_wp_error($categories)): ?>
            <?php foreach ($categories as $cat): ?>
                <a href="<?php echo esc_url(get_term_link($cat)); ?>"
                    class="shop-category-pill <?php echo ($current_cat && $current_cat->term_id === $cat->term_id) ? 'is-active' : ''; ?>">
                    <?php echo esc_html($cat->name); ?>
                    <span class="shop-category-pill__count">
                        <?php echo esc_html($cat->count); ?>
                    </span>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- ═══ PRODUCT GRID ═══ -->
<main class="shop-main">
    <div class="shop-grid-header">
        <h2 class="shop-grid-header__title">
            <?php if ($current_cat): ?>
                <?php echo esc_html($current_cat->name); ?>
            <?php else: ?>
                Todos los Libros
            <?php endif; ?>
        </h2>
        <span class="shop-grid-header__count">
            <?php
            global $wp_query;
            echo esc_html($wp_query->found_posts);
            ?> libros
        </span>
    </div>

    <?php if (woocommerce_product_loop()): ?>
        <div class="shop-grid">
            <?php while (have_posts()):
                the_post(); ?>
                <?php
                global $product;
                $product_id = $product->get_id();
                $title = $product->get_name();
                $permalink = get_permalink($product_id);
                $image_id = $product->get_image_id();
                $regular_price = $product->get_regular_price();
                $sale_price = $product->get_sale_price();
                $is_on_sale = $product->is_on_sale();

                // Get author from product attribute
                $author_attr = $product->get_attribute('Autor');

                // Get product categories
                $product_cats = get_the_terms($product_id, 'product_cat');
                $cat_name = '';
                if (!empty($product_cats) && !is_wp_error($product_cats)) {
                    foreach ($product_cats as $pc) {
                        if ($pc->term_id !== (int) get_option('default_product_cat')) {
                            $cat_name = $pc->name;
                            break;
                        }
                    }
                }

                // Add to cart URL (redirects to cart page)
                $buy_url = add_query_arg('add-to-cart', $product_id, wc_get_cart_url());
                ?>
                <article class="shop-card" data-product-id="<?php echo esc_attr($product_id); ?>">
                    <!-- Image -->
                    <div class="shop-card__image">
                        <?php if ($image_id): ?>
                            <a href="<?php echo esc_url($permalink); ?>">
                                <?php echo wp_get_attachment_image($image_id, 'medium_large', false, [
                                    'loading' => 'lazy',
                                    'alt' => esc_attr($title),
                                ]); ?>
                            </a>
                        <?php else: ?>
                            <a href="<?php echo esc_url($permalink); ?>" class="shop-card__image-placeholder">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.5">
                                    <path d="M4 19.5A2.5 2.5 0 016.5 17H20" />
                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z" />
                                </svg>
                            </a>
                        <?php endif; ?>

                        <?php if ($is_on_sale): ?>
                            <?php
                            $discount = 0;
                            if ($regular_price > 0 && $sale_price) {
                                $discount = round((($regular_price - $sale_price) / $regular_price) * 100);
                            }
                            ?>
                            <span class="shop-card__badge">-
                                <?php echo esc_html($discount); ?>%
                            </span>
                        <?php endif; ?>

                        <?php if ($cat_name): ?>
                            <span class="shop-card__category">
                                <?php echo esc_html($cat_name); ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Content -->
                    <div class="shop-card__body">
                        <h3 class="shop-card__title">
                            <a href="<?php echo esc_url($permalink); ?>">
                                <?php echo esc_html($title); ?>
                            </a>
                        </h3>

                        <?php if ($author_attr): ?>
                            <div class="shop-card__author">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                                <?php echo esc_html($author_attr); ?>
                            </div>
                        <?php endif; ?>

                        <div class="shop-card__price-row">
                            <?php if ($is_on_sale && $sale_price): ?>
                                <span class="shop-card__price">
                                    <?php echo wc_price($sale_price); ?>
                                </span>
                                <span class="shop-card__price-original">
                                    <?php echo wc_price($regular_price); ?>
                                </span>
                            <?php else: ?>
                                <span class="shop-card__price">
                                    <?php echo wc_price($regular_price); ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <a href="<?php echo esc_url($buy_url); ?>" class="shop-card__cta">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0" />
                            </svg>
                            Añadir al Carrito
                        </a>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>

        <?php
        // Pagination
        $total_pages = $wp_query->max_num_pages;
        if ($total_pages > 1):
            ?>
            <nav class="shop-pagination" aria-label="Paginación">
                <?php
                echo paginate_links([
                    'total' => $total_pages,
                    'current' => max(1, get_query_var('paged')),
                    'type' => 'list',
                    'prev_text' => '&larr; Anterior',
                    'next_text' => 'Siguiente &rarr;',
                ]);
                ?>
            </nav>
        <?php endif; ?>

    <?php else: ?>
        <div class="shop-empty">
            <svg class="shop-empty__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M4 19.5A2.5 2.5 0 016.5 17H20" />
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z" />
            </svg>
            <h3 class="shop-empty__title">No se encontraron libros</h3>
            <p class="shop-empty__text">Intenta con otra búsqueda o categoría.</p>
        </div>
    <?php endif; ?>
</main>

<!-- ═══ FOOTER ═══ -->
<footer class="shop-footer">
    <div class="shop-footer__inner">
        <div class="shop-footer__grid">
            <div>
                <div class="shop-footer__brand-name">Portal<span>Libros</span></div>
                <p class="shop-footer__brand-desc">
                    Tu biblioteca digital de conocimiento práctico. Ebooks escritos por expertos para transformar tu
                    vida personal y profesional.
                </p>
            </div>
            <div>
                <div class="shop-footer__col-title">Tienda</div>
                <div class="shop-footer__links">
                    <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">Todos los libros</a>
                    <?php if (!empty($categories) && !is_wp_error($categories)): ?>
                        <?php foreach (array_slice($categories, 0, 5) as $cat): ?>
                            <a href="<?php echo esc_url(get_term_link($cat)); ?>">
                                <?php echo esc_html($cat->name); ?>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div>
                <div class="shop-footer__col-title">Ayuda</div>
                <div class="shop-footer__links">
                    <a href="#">Preguntas frecuentes</a>
                    <a href="#">Política de reembolso</a>
                    <a href="#">Términos y condiciones</a>
                    <a href="#">Política de privacidad</a>
                </div>
            </div>
            <div>
                <div class="shop-footer__col-title">Contacto</div>
                <div class="shop-footer__links">
                    <a href="mailto:soporte@portalibros.com">soporte@portalibros.com</a>
                    <a href="#">Centro de ayuda</a>
                </div>
            </div>
        </div>

        <div class="shop-footer__bottom">
            <span class="shop-footer__copy">&copy;
                <?php echo date('Y'); ?> PortaLibros. Todos los derechos reservados.
            </span>
            <div class="shop-footer__payments">
                <!-- Visa -->
                <svg viewBox="0 0 50 35" fill="currentColor">
                    <rect width="50" height="35" rx="4" fill="currentColor" opacity=".2" /><text x="25" y="21"
                        text-anchor="middle" font-size="11" font-weight="bold" fill="currentColor"
                        opacity=".6">VISA</text>
                </svg>
                <!-- MC -->
                <svg viewBox="0 0 50 35" fill="currentColor">
                    <rect width="50" height="35" rx="4" fill="currentColor" opacity=".2" /><text x="25" y="21"
                        text-anchor="middle" font-size="9" font-weight="bold" fill="currentColor" opacity=".6">MC</text>
                </svg>
                <!-- PayPal -->
                <svg viewBox="0 0 50 35" fill="currentColor">
                    <rect width="50" height="35" rx="4" fill="currentColor" opacity=".2" /><text x="25" y="21"
                        text-anchor="middle" font-size="8" font-weight="bold" fill="currentColor"
                        opacity=".6">PayPal</text>
                </svg>
            </div>
        </div>
    </div>
</footer>

<?php get_footer(); ?>