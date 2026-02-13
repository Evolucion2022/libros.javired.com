<?php
/**
 * WooCommerce Template Override (Child Theme)
 *
 * This file overrides the parent theme's woocommerce.php.
 * - Shop/category archives → custom premium template (archive-product.php)
 * - Checkout → minimal layout (no custom header/footer)
 * - All other WC pages (cart, my account, etc.) → custom header + footer
 */

if (!defined('ABSPATH'))
    exit;

// 1. Shop page and product category archives: use our custom premium template
if (is_shop() || is_product_taxonomy()) {
    include get_stylesheet_directory() . '/woocommerce/archive-product.php';
    return;
}

// 2. Checkout: keep minimal design (no custom header/footer)
if (is_checkout()) {
    get_header();
    ?>
    <main class="site-main">
        <div class="container" style="padding-top: var(--space-10); padding-bottom: var(--space-10);">
            <?php woocommerce_content(); ?>
        </div>
    </main>
    <?php
    get_footer();
    return;
}

// 3. All other WC pages (cart, my account, orders, etc.): custom header + footer
get_header();

// Load shop.css / home.css styles that power the header/footer
include get_stylesheet_directory() . '/header-shop.php';
?>

<main class="site-main" style="background: var(--home-bg, var(--shop-bg, #F6F4F0)); min-height: 60vh;">
    <div style="max-width: 1320px; margin: 0 auto; padding: 40px 24px 80px; box-sizing: border-box;">
        <?php woocommerce_content(); ?>
    </div>
</main>

<!-- ═══ FOOTER ═══ -->
<?php
// Retrieve data needed for footer
$shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : '/tienda/';
$cart_url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : '/carrito/';
$account_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : '/mi-cuenta/';

$footer_cats = get_terms([
    'taxonomy' => 'product_cat',
    'hide_empty' => true,
    'orderby' => 'count',
    'order' => 'DESC',
    'exclude' => [get_option('default_product_cat')],
    'number' => 5,
]);
?>

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
                    <?php if (!empty($footer_cats) && !is_wp_error($footer_cats)): ?>
                        <?php foreach ($footer_cats as $cat): ?>
                            <a href="<?php echo esc_url(get_term_link($cat)); ?>"><?php echo esc_html($cat->name); ?></a>
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
            <span class="home-footer__copy">&copy; <?php echo date('Y'); ?> PortaLibros. Todos los derechos
                reservados.</span>
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

<?php get_footer();
