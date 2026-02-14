<?php
/**
 * My Account Dashboard
 *
 * Override: Premium card-based dashboard layout.
 */

if (!defined('ABSPATH')) {
    exit;
}

$current_user = wp_get_current_user();
?>

<div class="account-dashboard-wrapper">

    <!-- 1. Welcome Banner -->
    <div class="account-welcome">
        <div class="account-welcome__avatar">
            <?php echo get_avatar($current_user->ID, 80); ?>
        </div>
        <div class="account-welcome__info">
            <h2>Hola,
                <?php echo esc_html($current_user->display_name); ?> 👋
            </h2>
            <p>Bienvenido a tu panel personal. Desde aquí puedes gestionar tus pedidos, descargas y detalles de cuenta.
            </p>
        </div>
    </div>

    <!-- 2. Dashboard Grid -->
    <div class="account-grid">

        <!-- Pedidos -->
        <a href="<?php echo esc_url(wc_get_endpoint_url('orders')); ?>" class="account-card">
            <div class="account-card__icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 8l-2-5H5L3 8v10a2 2 0 002 2h14a2 2 0 002-2V8z"></path>
                    <path d="M3 8h18"></path>
                    <path d="M16 8v3a4 4 0 01-8 0V8"></path>
                </svg>
            </div>
            <div class="account-card__title">Mis Pedidos</div>
            <div class="account-card__desc">Revisa tu historial de compras y el estado de tus pedidos recientes.</div>
            <div class="account-card__arrow">
                Ver pedidos &rarr;
            </div>
        </a>

        <!-- Descargas -->
        <a href="<?php echo esc_url(wc_get_endpoint_url('downloads')); ?>" class="account-card">
            <div class="account-card__icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
            </div>
            <div class="account-card__title">Descargas</div>
            <div class="account-card__desc">Accede a tus libros digitales y archivos comprados al instante.</div>
            <div class="account-card__arrow">
                Ver descargas &rarr;
            </div>
        </a>

        <!-- Direcciones -->
        <a href="<?php echo esc_url(wc_get_endpoint_url('edit-address')); ?>" class="account-card">
            <div class="account-card__icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
            </div>
            <div class="account-card__title">Direcciones</div>
            <div class="account-card__desc">Gestiona tus direcciones de facturación y envío para futuros pedidos.</div>
            <div class="account-card__arrow">
                Editar direcciones &rarr;
            </div>
        </a>

        <!-- Detalles de Cuenta -->
        <a href="<?php echo esc_url(wc_get_endpoint_url('edit-account')); ?>" class="account-card">
            <div class="account-card__icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
            <div class="account-card__title">Detalles de Cuenta</div>
            <div class="account-card__desc">Actualiza tu nombre, correo electrónico y cambia tu contraseña.</div>
            <div class="account-card__arrow">
                Editar detalles &rarr;
            </div>
        </a>

        <!-- Métodos de Pago (si existe el endpoint) -->
        <a href="<?php echo esc_url(wc_get_endpoint_url('payment-methods')); ?>" class="account-card">
            <div class="account-card__icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                    <line x1="1" y1="10" x2="23" y2="10"></line>
                </svg>
            </div>
            <div class="account-card__title">Métodos de Pago</div>
            <div class="account-card__desc">Administra tus tarjetas guardadas y métodos de pago preferidos.</div>
            <div class="account-card__arrow">
                Ver métodos &rarr;
            </div>
        </a>

        <!-- Cerrar Sesión -->
        <a href="<?php echo esc_url(wc_logout_url()); ?>" class="account-card" style="border-color: #EF4444;">
            <div class="account-card__icon" style="color: #EF4444; background: rgba(239, 68, 68, 0.1);">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
            </div>
            <div class="account-card__title" style="color: #EF4444;">Cerrar Sesión</div>
            <div class="account-card__desc">Cierra tu sesión de forma segura en este dispositivo.</div>
            <div class="account-card__arrow" style="color: #EF4444;">
                Salir ahora &rarr;
            </div>
        </a>

    </div>

    <!-- Hooks for additional content (plugins) -->
    <?php
    do_action('woocommerce_account_dashboard');
    do_action('woocommerce_before_my_account');
    do_action('woocommerce_after_my_account');
    ?>

</div>