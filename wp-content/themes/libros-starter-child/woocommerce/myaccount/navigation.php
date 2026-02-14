<?php
/**
 * My Account navigation
 *
 * Override: Replaces sidebar with a minimal top bar for sub-pages.
 * On the main dashboard, this is hidden naturally or via CSS.
 */

if (!defined('ABSPATH')) {
    exit;
}

// 1. Get current endpoint to determine title
$current_endpoint = WC()->query->get_current_endpoint();
$title = '';

if ($current_endpoint) {
    // Map endpoints to readable titles
    switch ($current_endpoint) {
        case 'orders':
        case 'view-order':
            $title = 'Mis Pedidos';
            break;
        case 'downloads':
            $title = 'Descargas';
            break;
        case 'edit-address':
            $title = 'Direcciones';
            break;
        case 'payment-methods':
        case 'add-payment-method':
            $title = 'Métodos de Pago';
            break;
        case 'edit-account':
            $title = 'Detalles de la Cuenta';
            break;
        default:
            $title = str_replace('-', ' ', ucfirst($current_endpoint));
    }
}
?>

<?php if ($current_endpoint && $current_endpoint !== 'dashboard'): ?>
    <div class="account-nav-header">
        <div class="account-nav-header__left">
            <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>" class="account-back-btn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7" />
                </svg>
                Volver al Panel
            </a>
        </div>
        <div class="account-nav-header__right">
            <h1 class="entry-title" style="margin:0; font-size:1.5rem;">
                <?php echo esc_html($title); ?>
            </h1>
        </div>
    </div>
<?php endif; ?>