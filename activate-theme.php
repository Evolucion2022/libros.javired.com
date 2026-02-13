<?php
/**
 * One-time script to activate the libros-starter theme.
 *
 * Usage: https://libros.javired.com/activate-theme.php?key=libros-theme-2026
 * DELETE THIS FILE after activation!
 */

if (!isset($_GET['key']) || $_GET['key'] !== 'libros-theme-2026') {
    http_response_code(403);
    die('Forbidden');
}

// Load WordPress
define('WP_USE_THEMES', false);
require_once __DIR__ . '/wp-load.php';

header('Content-Type: text/plain; charset=utf-8');

$theme_slug = 'libros-starter';

// Check if theme exists
$theme = wp_get_theme($theme_slug);
if (!$theme->exists()) {
    echo "ERROR: Theme '$theme_slug' not found!\n";
    echo "Available themes:\n";
    foreach (wp_get_themes() as $slug => $t) {
        echo "  - $slug: " . $t->get('Name') . "\n";
    }
    exit;
}

echo "Theme found: " . $theme->get('Name') . " v" . $theme->get('Version') . "\n";

// Check current active theme
$current = get_stylesheet();
echo "Current active theme: $current\n";

if ($current === $theme_slug) {
    echo "\nTheme is already active! Nothing to do.\n";
    exit;
}

// Activate
switch_theme($theme_slug);

// Verify
$new_current = get_stylesheet();
if ($new_current === $theme_slug) {
    echo "\n✅ SUCCESS! Theme '$theme_slug' is now active.\n";
    echo "\nNext steps:\n";
    echo "  1. Visit the product page: https://libros.javired.com/producto/desactiva-tu-ansiedad/\n";
    echo "  2. Delete this file: activate-theme.php\n";
} else {
    echo "\n❌ ERROR: Theme activation failed. Current theme is still: $new_current\n";
}

// Also disable coming soon mode
if (function_exists('update_option')) {
    update_option('woocommerce_coming_soon', 'no');
    echo "\n📌 WooCommerce 'coming soon' mode disabled.\n";
}
