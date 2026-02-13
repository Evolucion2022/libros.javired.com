<?php
/**
 * Checkout Optimizer for Digital Products
 *
 * Streamlines WooCommerce checkout for ebook sales:
 * - Removes unnecessary billing fields (address, city, state, postcode, country, company)
 * - Keeps only: first_name, last_name, phone, email
 * - Removes additional information (order notes)
 * - Skips the cart page (direct to checkout)
 * - Auto-enables virtual/downloadable defaults
 * - Removes shipping section entirely
 *
 * @package LibrosJavired
 */

if (!defined('ABSPATH'))
    exit;


/* ──────────────────────────────────────────────
   1. REMOVE UNNECESSARY BILLING FIELDS
   ────────────────────────────────────────────── */
add_filter('woocommerce_checkout_fields', function ($fields) {
    // Remove billing fields we don't need for digital products
    $remove_billing = [
        'billing_company',
        'billing_address_1',
        'billing_address_2',
        'billing_city',
        'billing_postcode',
        'billing_state',
        'billing_country',
    ];

    foreach ($remove_billing as $field) {
        unset($fields['billing'][$field]);
    }

    // Remove order comments / additional info
    unset($fields['order']['order_comments']);

    // Remove shipping fields entirely
    $fields['shipping'] = [];

    // Customize remaining field labels and placeholders
    if (isset($fields['billing']['billing_first_name'])) {
        $fields['billing']['billing_first_name']['placeholder'] = 'Tu nombre';
        $fields['billing']['billing_first_name']['label'] = 'Nombre';
        $fields['billing']['billing_first_name']['priority'] = 10;
        $fields['billing']['billing_first_name']['class'] = ['form-row-first'];
    }

    if (isset($fields['billing']['billing_last_name'])) {
        $fields['billing']['billing_last_name']['placeholder'] = 'Tu apellido';
        $fields['billing']['billing_last_name']['label'] = 'Apellido';
        $fields['billing']['billing_last_name']['priority'] = 20;
        $fields['billing']['billing_last_name']['class'] = ['form-row-last'];
    }

    if (isset($fields['billing']['billing_email'])) {
        $fields['billing']['billing_email']['placeholder'] = 'tu@email.com';
        $fields['billing']['billing_email']['label'] = 'Email';
        $fields['billing']['billing_email']['priority'] = 30;
        $fields['billing']['billing_email']['class'] = ['form-row-wide'];
    }

    if (isset($fields['billing']['billing_phone'])) {
        $fields['billing']['billing_phone']['placeholder'] = '+1 234 567 8900';
        $fields['billing']['billing_phone']['label'] = 'Teléfono / WhatsApp';
        $fields['billing']['billing_phone']['priority'] = 40;
        $fields['billing']['billing_phone']['class'] = ['form-row-wide'];
        $fields['billing']['billing_phone']['required'] = true;
    }

    return $fields;
}, 9999);


/* ──────────────────────────────────────────────
   2. MAKE BILLING COUNTRY OPTIONAL (hidden)
   We set a default country so WooCommerce doesn't
   complain about missing country for tax/payment
   ────────────────────────────────────────────── */
add_filter('woocommerce_billing_fields', function ($fields) {
    if (isset($fields['billing_country'])) {
        $fields['billing_country']['required'] = false;
        $fields['billing_country']['class'] = ['hidden'];
    }
    return $fields;
}, 9999);

// Set a default country for virtual products
add_filter('default_checkout_billing_country', function () {
    return 'US';
});


/* ──────────────────────────────────────────────
   3. SKIP CART — DIRECT TO CHECKOUT
   ────────────────────────────────────────────── */
add_filter('woocommerce_add_to_cart_redirect', function ($url) {
    return wc_get_checkout_url();
});

// Empty the cart before adding new product (single product at a time for ebooks)
add_filter('woocommerce_add_to_cart_validation', function ($passed, $product_id) {
    WC()->cart->empty_cart();
    return $passed;
}, 10, 2);


/* ──────────────────────────────────────────────
   4. REMOVE "SHIP TO DIFFERENT ADDRESS" section
   ────────────────────────────────────────────── */
add_filter('woocommerce_cart_needs_shipping', '__return_false');


/* ──────────────────────────────────────────────
   5. DISABLE ORDER NOTES
   ────────────────────────────────────────────── */
add_filter('woocommerce_enable_order_notes_field', '__return_false');


/* ──────────────────────────────────────────────
   6. CHECKOUT PAGE CUSTOMIZATION HOOKS
   ────────────────────────────────────────────── */

// Remove coupon form from checkout (keep it simple)
// Uncomment the next line if you DON'T want coupons on checkout:
// remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);

// Remove "Returning customer? Click here to login"
add_filter('woocommerce_checkout_login_message', '__return_empty_string');
remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 20);

// Change checkout heading
add_filter('gettext', function ($translated_text, $text, $domain) {
    if ($domain === 'woocommerce') {
        switch ($text) {
            case 'Billing details':
            case 'Billing &amp; Shipping':
            case 'Detalles de facturación':
                return '📋 Tus Datos';
            case 'Your order':
            case 'Tu pedido':
                return '🛒 Tu Pedido';
            case 'Place order':
            case 'Realizar el pedido':
                return '🔒 Completar Compra Segura';
            case 'Have a coupon?':
            case '¿Tienes un cupón?':
                return '🎟️ ¿Tienes un código de descuento?';
        }
    }
    return $translated_text;
}, 20, 3);


/* ──────────────────────────────────────────────
   7. ADD TRUST BADGES AND REASSURANCE
   ────────────────────────────────────────────── */

// Add trust badges before payment methods
add_action('woocommerce_review_order_before_payment', function () {
    echo '<div class="checkout-trust-badges">';
    echo '<div class="trust-badge"><span>🔒</span> Pago 100% Seguro</div>';
    echo '<div class="trust-badge"><span>⚡</span> Descarga Inmediata</div>';
    echo '<div class="trust-badge"><span>🛡️</span> Garantía 7 Días</div>';
    echo '</div>';
});

// Add reassurance after place order button
add_action('woocommerce_review_order_after_submit', function () {
    echo '<div class="checkout-reassurance">';
    echo '<p>✓ Sin spam · ✓ Pago seguro SSL · ✓ Descarga al instante</p>';
    echo '</div>';
});

// Add product summary at the top of checkout
add_action('woocommerce_before_checkout_form', function () {
    $cart = WC()->cart;
    if ($cart->is_empty())
        return;

    $items = $cart->get_cart();
    $first_item = reset($items);

    if (!$first_item)
        return;

    $product = $first_item['data'];
    $image = $product->get_image(array(80, 80), array('class' => 'checkout-product-thumb'));
    $name = $product->get_name();
    $price = $cart->get_total();

    echo '<div class="checkout-summary-banner">';
    echo '<div class="checkout-summary-banner__inner">';
    echo '<div class="checkout-summary-banner__image">' . $image . '</div>';
    echo '<div class="checkout-summary-banner__info">';
    echo '<h3 class="checkout-summary-banner__title">' . esc_html($name) . '</h3>';
    echo '<p class="checkout-summary-banner__price">Total: ' . $price . '</p>';
    echo '<p class="checkout-summary-banner__meta">📥 Descarga digital inmediata · 🛡️ Garantía de 7 días</p>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
}, 5);


/* ──────────────────────────────────────────────
   8. ENQUEUE PREMIUM CHECKOUT CSS
   ────────────────────────────────────────────── */
add_action('wp_enqueue_scripts', function () {
    if (!is_checkout())
        return;

    // Enqueue the checkout-specific CSS
    $checkout_css = WP_CONTENT_DIR . '/themes/libros-starter/assets/css/checkout.css';
    if (file_exists($checkout_css)) {
        wp_enqueue_style(
            'libros-checkout-premium',
            content_url('/themes/libros-starter/assets/css/checkout.css'),
            ['woocommerce-general'],
            filemtime($checkout_css)
        );
    }
}, 999);


/* ──────────────────────────────────────────────
   9. DISABLE ACCOUNT CREATION ON CHECKOUT
   (Simpler checkout — guest checkout)
   ────────────────────────────────────────────── */
add_filter('pre_option_woocommerce_enable_guest_checkout', function () {
    return 'yes';
});

add_filter('pre_option_woocommerce_enable_signup_and_login_from_checkout', function () {
    return 'no';
});
