<?php
/**
 * Front Page Template — PortaLibros Premium Home
 *
 * @package libros-starter-child
 */
if (!defined('ABSPATH'))
    exit;

get_header();

// Get WC data
$shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : '/tienda/';
$cart_url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : '/carrito/';
$account_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : '/mi-cuenta/';

// Categories
$categories = get_terms([
    'taxonomy' => 'product_cat',
    'hide_empty' => true,
    'orderby' => 'count',
    'order' => 'DESC',
    'exclude' => [get_option('default_product_cat')],
]);

// Featured/on-sale products (up to 8)
$featured_products = wc_get_products([
    'limit' => 8,
    'status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC',
    'on_sale' => true,
]);

// If not enough on-sale, get latest products
if (count($featured_products) < 4) {
    $featured_products = wc_get_products([
        'limit' => 8,
        'status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
    ]);
}

// Total counts
$product_count = wp_count_posts('product');
$total_products = $product_count->publish;
$total_cats = count($categories);

// Category icons (SVG paths)
$cat_icons = [
    'Espiritualidad' => '<path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>',
    'Finanzas' => '<line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>',
    'Marketing' => '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>',
    'Crianza' => '<path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/>',
    'Desarrollo' => '<circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/>',
    'Mascotas' => '<path d="M10 5.172C10 3.782 8.423 2.679 6.5 3c-2.823.47-4.113 6.006-4 7 .08.703 1.725 1.722 3.656 1 1.261-.472 1.96-1.45 2.344-2.5M14.267 5.172c0-1.39 1.577-2.493 3.5-2.172 2.823.47 4.113 6.006 4 7-.08.703-1.725 1.722-3.656 1-1.261-.472-1.855-1.45-2.239-2.5M8 14v.5M16 14v.5M11.25 16.25h1.5L12 17l-.75-.75z"/>',
    'Cocina' => '<path d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3"/>',
    'Aplicaciones' => '<rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>',
    'Exclusivos' => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
    'Belleza' => '<circle cx="12" cy="12" r="3"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>',
    'Hobbies' => '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>',
    'Relaciones' => '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>',
    'Salud' => '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>',
];

// Helper: find matching icon
function libros_get_cat_icon($cat_name, $icons)
{
    foreach ($icons as $key => $svg_path) {
        if (stripos($cat_name, $key) !== false) {
            return $svg_path;
        }
    }
    return '<path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/>';
}
?>

<?php include get_stylesheet_directory() . '/header-shop.php'; ?>

<!-- ═══ HERO ═══ -->
<section class="home-hero">
    <div class="home-hero__content">
        <div class="home-hero__badge">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path
                    d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
            </svg>
            Biblioteca Digital Premium
        </div>
        <h1 class="home-hero__title">
            Conocimiento que<br><span>Transforma Vidas</span>
        </h1>
        <p class="home-hero__subtitle">
            Descubre nuestra colección de ebooks escritos por expertos. Desde finanzas hasta desarrollo personal —
            conocimiento práctico al alcance de un clic.
        </p>
        <div class="home-hero__actions">
            <a href="<?php echo esc_url($shop_url); ?>" class="home-hero__cta home-hero__cta--primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0" />
                </svg>
                Explorar Tienda
            </a>
            <a href="#categorias" class="home-hero__cta home-hero__cta--secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7" />
                    <rect x="14" y="3" width="7" height="7" />
                    <rect x="14" y="14" width="7" height="7" />
                    <rect x="3" y="14" width="7" height="7" />
                </svg>
                Ver Categorías
            </a>
        </div>
    </div>
</section>

<!-- ═══ TRUST BAR ═══ -->
<section class="home-trust">
    <div class="home-trust__inner">
        <div class="home-trust__item home-reveal">
            <div class="home-trust__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 19.5A2.5 2.5 0 016.5 17H20" />
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z" />
                </svg>
            </div>
            <span class="home-trust__number" data-count="<?php echo esc_attr($total_products); ?>">
                <?php echo esc_html($total_products); ?>+
            </span>
            <span class="home-trust__label">Ebooks Disponibles</span>
        </div>
        <div class="home-trust__item home-reveal">
            <div class="home-trust__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7" />
                    <rect x="14" y="3" width="7" height="7" />
                    <rect x="14" y="14" width="7" height="7" />
                    <rect x="3" y="14" width="7" height="7" />
                </svg>
            </div>
            <span class="home-trust__number" data-count="<?php echo esc_attr($total_cats); ?>">
                <?php echo esc_html($total_cats); ?>
            </span>
            <span class="home-trust__label">Categorías</span>
        </div>
        <div class="home-trust__item home-reveal">
            <div class="home-trust__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3" />
                </svg>
            </div>
            <span class="home-trust__number">Instant</span>
            <span class="home-trust__label">Descarga Inmediata</span>
        </div>
        <div class="home-trust__item home-reveal">
            <div class="home-trust__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                </svg>
            </div>
            <span class="home-trust__number">100%</span>
            <span class="home-trust__label">Pago Seguro</span>
        </div>
    </div>
</section>

<!-- ═══ CATEGORIES ═══ -->
<section class="home-section" id="categorias">
    <div class="home-section__inner">
        <div class="home-section__header home-reveal">
            <span class="home-section__eyebrow">Explora por tema</span>
            <h2 class="home-section__title">Categorías Populares</h2>
            <p class="home-section__subtitle">Encuentra el libro perfecto para ti navegando entre nuestras categorías
                cuidadosamente seleccionadas.</p>
        </div>

        <div class="home-cats">
            <?php
            $display_cats = array_slice($categories, 0, 9);
            foreach ($display_cats as $cat):
                $icon_path = libros_get_cat_icon($cat->name, $cat_icons);
                ?>
                <a href="<?php echo esc_url(get_term_link($cat)); ?>" class="home-cat-card home-reveal">
                    <div class="home-cat-card__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <?php echo $icon_path; ?>
                        </svg>
                    </div>
                    <div class="home-cat-card__info">
                        <div class="home-cat-card__name">
                            <?php echo esc_html($cat->name); ?>
                        </div>
                        <div class="home-cat-card__count">
                            <?php echo esc_html($cat->count); ?> libros
                        </div>
                    </div>
                    <svg class="home-cat-card__arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <polyline points="9 18 15 12 9 6" />
                    </svg>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ═══ FEATURED PRODUCTS ═══ -->
<section class="home-section home-section--alt">
    <div class="home-section__inner">
        <div class="home-section__header home-reveal">
            <span class="home-section__eyebrow">Los más vendidos</span>
            <h2 class="home-section__title">Destacados de la Semana</h2>
            <p class="home-section__subtitle">Descubre los ebooks que están transformando la vida de nuestros lectores.
            </p>
        </div>

        <div class="home-products">
            <?php foreach ($featured_products as $product):
                $pid = $product->get_id();
                $title = $product->get_name();
                $permalink = get_permalink($pid);
                $image_id = $product->get_image_id();
                $regular_price = $product->get_regular_price();
                $sale_price = $product->get_sale_price();
                $is_on_sale = $product->is_on_sale();
                $author_attr = $product->get_attribute('Autor');

                $pcats = get_the_terms($pid, 'product_cat');
                $pcat_name = '';
                if (!empty($pcats) && !is_wp_error($pcats)) {
                    foreach ($pcats as $pc) {
                        if ($pc->term_id !== (int) get_option('default_product_cat')) {
                            $pcat_name = $pc->name;
                            break;
                        }
                    }
                }

                $buy_url = add_query_arg('add-to-cart', $pid, wc_get_checkout_url());
                ?>
                <article class="home-product-card home-reveal">
                    <div class="home-product-card__image">
                        <?php if ($image_id): ?>
                            <a href="<?php echo esc_url($permalink); ?>">
                                <?php echo wp_get_attachment_image($image_id, 'medium_large', false, [
                                    'loading' => 'lazy',
                                    'alt' => esc_attr($title),
                                ]); ?>
                            </a>
                        <?php else: ?>
                            <a href="<?php echo esc_url($permalink); ?>" class="home-product-card__image-placeholder">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.5">
                                    <path d="M4 19.5A2.5 2.5 0 016.5 17H20" />
                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z" />
                                </svg>
                            </a>
                        <?php endif; ?>

                        <?php if ($is_on_sale && $regular_price > 0 && $sale_price):
                            $discount = round((($regular_price - $sale_price) / $regular_price) * 100);
                            ?>
                            <span class="home-product-card__badge">-
                                <?php echo esc_html($discount); ?>%
                            </span>
                        <?php endif; ?>

                        <?php if ($pcat_name): ?>
                            <span class="home-product-card__category">
                                <?php echo esc_html($pcat_name); ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="home-product-card__body">
                        <h3 class="home-product-card__title">
                            <a href="<?php echo esc_url($permalink); ?>">
                                <?php echo esc_html($title); ?>
                            </a>
                        </h3>

                        <?php if ($author_attr): ?>
                            <div class="home-product-card__author">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                                <?php echo esc_html($author_attr); ?>
                            </div>
                        <?php endif; ?>

                        <div class="home-product-card__price-row">
                            <?php if ($is_on_sale && $sale_price): ?>
                                <span class="home-product-card__price">
                                    <?php echo wc_price($sale_price); ?>
                                </span>
                                <span class="home-product-card__price-old">
                                    <?php echo wc_price($regular_price); ?>
                                </span>
                            <?php else: ?>
                                <span class="home-product-card__price">
                                    <?php echo wc_price($regular_price); ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <a href="<?php echo esc_url($buy_url); ?>" class="home-product-card__cta">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0" />
                            </svg>
                            Comprar Ahora
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <div class="home-products__more home-reveal">
            <a href="<?php echo esc_url($shop_url); ?>">
                Ver Todos los Libros
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9 18 15 12 9 6" />
                </svg>
            </a>
        </div>
    </div>
</section>

<!-- ═══ VALUE PROPOSITIONS ═══ -->
<section class="home-section home-section--warm">
    <div class="home-section__inner">
        <div class="home-section__header home-reveal">
            <span class="home-section__eyebrow">¿Por qué elegirnos?</span>
            <h2 class="home-section__title">La Mejor Experiencia de Lectura Digital</h2>
        </div>

        <div class="home-values">
            <div class="home-value home-reveal">
                <div class="home-value__icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3" />
                    </svg>
                </div>
                <div class="home-value__title">Descarga Instantánea</div>
                <p class="home-value__text">Recibe tu ebook inmediatamente después del pago. Sin esperas, sin
                    complicaciones. Comienza a leer en segundos.</p>
            </div>
            <div class="home-value home-reveal">
                <div class="home-value__icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                    </svg>
                </div>
                <div class="home-value__title">Pago 100% Seguro</div>
                <p class="home-value__text">Tus datos están protegidos con cifrado SSL de grado bancario. Aceptamos
                    tarjetas de crédito, débito y PayPal.</p>
            </div>
            <div class="home-value home-reveal">
                <div class="home-value__icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                </div>
                <div class="home-value__title">Acceso de Por Vida</div>
                <p class="home-value__text">Una vez comprado, el ebook es tuyo para siempre. Descárgalo cuantas veces
                    quieras en cualquier dispositivo.</p>
            </div>
        </div>
    </div>
</section>

<!-- ═══ NEWSLETTER CTA ═══ -->
<section class="home-cta">
    <div class="home-cta__inner home-reveal">
        <h2 class="home-cta__title">¿No quieres perderte nada?</h2>
        <p class="home-cta__text">Suscríbete y recibe ofertas exclusivas, nuevos lanzamientos y contenido gratuito
            directamente en tu correo.</p>
        <form class="home-cta__form" action="#" method="post">
            <input type="email" name="email" placeholder="Tu correo electrónico..." required
                aria-label="Correo electrónico">
            <button type="submit">Suscribirse</button>
        </form>
    </div>
</section>

<!-- ═══ FOOTER ═══ -->
<footer class="home-footer">
    <div class="home-footer__inner">
        <div class="home-footer__grid">
            <div>
                <div class="home-footer__brand-name">Portal<span>Libros</span></div>
                <p class="home-footer__brand-desc">
                    Tu biblioteca digital de conocimiento práctico. Ebooks escritos por expertos para transformar tu
                    vida personal y profesional.
                </p>
                <div class="home-footer__social">
                    <a href="#" aria-label="Facebook">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z" />
                        </svg>
                    </a>
                    <a href="#" aria-label="Instagram">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="2" width="20" height="20" rx="5" />
                            <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z" />
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5" />
                        </svg>
                    </a>
                    <a href="#" aria-label="Twitter">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path
                                d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z" />
                        </svg>
                    </a>
                </div>
            </div>
            <div>
                <div class="home-footer__col-title">Tienda</div>
                <div class="home-footer__links">
                    <a href="<?php echo esc_url($shop_url); ?>">Todos los libros</a>
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
                <div class="home-footer__col-title">Tu Cuenta</div>
                <div class="home-footer__links">
                    <a href="<?php echo esc_url($account_url); ?>">Mi Cuenta</a>
                    <a href="<?php echo esc_url($cart_url); ?>">Carrito</a>
                    <a href="#">Preguntas frecuentes</a>
                    <a href="#">Política de reembolso</a>
                    <a href="#">Términos y condiciones</a>
                </div>
            </div>
            <div>
                <div class="home-footer__col-title">Contacto</div>
                <div class="home-footer__links">
                    <a href="mailto:soporte@portalibros.com">soporte@portalibros.com</a>
                    <a href="#">Centro de ayuda</a>
                    <a href="#">Política de privacidad</a>
                </div>
            </div>
        </div>

        <div class="home-footer__bottom">
            <span class="home-footer__copy">&copy;
                <?php echo date('Y'); ?> PortaLibros. Todos los derechos reservados.
            </span>
            <div class="home-footer__payments">
                <svg viewBox="0 0 50 35" fill="currentColor">
                    <rect width="50" height="35" rx="4" fill="currentColor" opacity=".2" /><text x="25" y="21"
                        text-anchor="middle" font-size="11" font-weight="bold" fill="currentColor"
                        opacity=".6">VISA</text>
                </svg>
                <svg viewBox="0 0 50 35" fill="currentColor">
                    <rect width="50" height="35" rx="4" fill="currentColor" opacity=".2" /><text x="25" y="21"
                        text-anchor="middle" font-size="9" font-weight="bold" fill="currentColor" opacity=".6">MC</text>
                </svg>
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