<?php
/**
 * Shop Header — Portalibros
 * Sticky header with glassmorphism, nav, dark/light toggle, cart, user account, mobile hamburger.
 * Included by archive-product.php and front-page.php in the child theme.
 */
if (!defined('ABSPATH'))
    exit;

$cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
$shop_url = wc_get_page_permalink('shop');
$cart_url = wc_get_cart_url();
$home_url = home_url('/');
$account_url = wc_get_page_permalink('myaccount');

// Determine active page
$is_home = is_front_page();
$is_shop_page = (function_exists('is_shop') && is_shop()) || (function_exists('is_product_taxonomy') && is_product_taxonomy());

// Get product categories for dropdown
$categories = get_terms([
    'taxonomy' => 'product_cat',
    'hide_empty' => true,
    'orderby' => 'name',
    'order' => 'ASC',
    'exclude' => [get_option('default_product_cat')], // exclude "Uncategorized"
]);
?>

<!-- ═══ HEADER ═══ -->
<header class="shop-header" id="shopHeader">
    <div class="shop-header__inner">

        <!-- Logo -->
        <a href="<?php echo esc_url($home_url); ?>" class="shop-header__logo">
            <div class="shop-header__logo-icon">P</div>
            <div class="shop-header__logo-text">Portal<span>Libros</span></div>
        </a>

        <!-- Desktop Nav -->
        <nav class="shop-header__nav" aria-label="Navegación principal">
            <a href="<?php echo esc_url($home_url); ?>" <?php echo $is_home ? 'class="is-active"' : ''; ?>>Inicio</a>
            <a href="<?php echo esc_url($shop_url); ?>" <?php echo $is_shop_page ? 'class="is-active"' : ''; ?>>Tienda</a>

            <!-- Categories dropdown -->
            <div class="shop-nav-dropdown">
                <a href="#" class="shop-nav-dropdown__trigger" aria-expanded="false">
                    Categorías
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        style="width:14px;height:14px;margin-left:2px;">
                        <path d="M6 9l6 6 6-6" />
                    </svg>
                </a>
                <div class="shop-nav-dropdown__menu" role="menu">
                    <?php if (!empty($categories) && !is_wp_error($categories)): ?>
                        <?php foreach ($categories as $cat): ?>
                            <a href="<?php echo esc_url(get_term_link($cat)); ?>" role="menuitem">
                                <?php echo esc_html($cat->name); ?>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <a href="<?php echo esc_url($home_url); ?>#contacto">Contacto</a>
        </nav>

        <!-- Actions -->
        <div class="shop-header__actions">

            <!-- Dark/Light Toggle -->
            <button class="theme-toggle" id="themeToggle" aria-label="Cambiar tema de color" type="button">
                <span class="theme-toggle__sun">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="5" />
                        <path
                            d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42" />
                    </svg>
                </span>
                <div class="theme-toggle__knob">
                    <svg class="theme-toggle__knob-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <circle cx="12" cy="12" r="5" />
                        <path
                            d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42" />
                    </svg>
                    <svg class="theme-toggle__knob-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" style="display:none;">
                        <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
                    </svg>
                </div>
                <span class="theme-toggle__moon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
                    </svg>
                </span>
            </button>

            <!-- Mi Cuenta -->
            <a href="<?php echo esc_url($account_url); ?>" class="shop-header__account" aria-label="Mi Cuenta">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
            </a>

            <!-- Cart -->
            <a href="<?php echo esc_url($cart_url); ?>" class="shop-header__cart" aria-label="Carrito de compras">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0" />
                </svg>
                <?php if ($cart_count > 0): ?>
                    <span class="shop-header__cart-count">
                        <?php echo esc_html($cart_count); ?>
                    </span>
                <?php endif; ?>
            </a>

            <!-- Hamburger (mobile) -->
            <button class="shop-header__hamburger" id="shopHamburger" aria-label="Abrir menú" type="button">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="3" y1="6" x2="21" y2="6" />
                    <line x1="3" y1="12" x2="21" y2="12" />
                    <line x1="3" y1="18" x2="21" y2="18" />
                </svg>
            </button>
        </div>

    </div>
</header>

<!-- ═══ MOBILE DRAWER ═══ -->
<div class="shop-drawer-overlay" id="shopDrawerOverlay"></div>
<aside class="shop-drawer" id="shopDrawer" role="dialog" aria-modal="true" aria-label="Menú de navegación">
    <div class="shop-drawer__header">
        <span class="shop-drawer__title">Portal<span style="color:var(--shop-accent)">Libros</span></span>
        <button class="shop-drawer__close" id="shopDrawerClose" aria-label="Cerrar menú" type="button">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18" />
                <line x1="6" y1="6" x2="18" y2="18" />
            </svg>
        </button>
    </div>

    <nav class="shop-drawer__nav">
        <a href="<?php echo esc_url($home_url); ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                <polyline points="9 22 9 12 15 12 15 22" />
            </svg>
            Inicio
        </a>
        <a href="<?php echo esc_url($shop_url); ?>" class="is-active">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18" />
            </svg>
            Tienda
        </a>
        <a href="<?php echo esc_url($cart_url); ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="9" cy="21" r="1" />
                <circle cx="20" cy="21" r="1" />
                <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6" />
            </svg>
            Carrito
            <?php if ($cart_count > 0): ?> (
                <?php echo esc_html($cart_count); ?>)
            <?php endif; ?>
        </a>
        <a href="<?php echo esc_url($home_url); ?>#contacto">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                <polyline points="22,6 12,13 2,6" />
            </svg>
            Contacto
        </a>

        <div class="shop-drawer__divider"></div>
        <div class="shop-drawer__categories-title">Categorías</div>

        <?php if (!empty($categories) && !is_wp_error($categories)): ?>
            <?php foreach ($categories as $cat): ?>
                <a href="<?php echo esc_url(get_term_link($cat)); ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 19.5A2.5 2.5 0 016.5 17H20" />
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z" />
                    </svg>
                    <?php echo esc_html($cat->name); ?>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </nav>

    <div class="shop-drawer__footer">
        <div class="shop-drawer__theme-row">
            <span class="shop-drawer__theme-label">Modo oscuro</span>
            <button class="theme-toggle" id="themeToggleMobile" aria-label="Cambiar tema" type="button">
                <span class="theme-toggle__sun">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="5" />
                        <path
                            d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42" />
                    </svg>
                </span>
                <div class="theme-toggle__knob">
                    <svg class="theme-toggle__knob-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <circle cx="12" cy="12" r="5" />
                    </svg>
                    <svg class="theme-toggle__knob-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" style="display:none;">
                        <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
                    </svg>
                </div>
                <span class="theme-toggle__moon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
                    </svg>
                </span>
            </button>
        </div>
    </div>
</aside>