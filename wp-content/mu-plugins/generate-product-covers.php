<?php
/**
 * Generate Product Cover Images — MU Plugin
 *
 * Creates unique, visually appealing ebook cover images for all products
 * using PHP GD library. Each cover has a gradient background, decorative
 * elements, title text, and author name.
 *
 * Runs once on admin_init, guarded by an option check.
 */

if (!defined('ABSPATH'))
    exit;

add_action('admin_init', function () {
    // Only run once
    if (get_option('libros_covers_generated'))
        return;

    // Make sure WooCommerce is active
    if (!class_exists('WooCommerce'))
        return;

    // Make sure GD is available
    if (!function_exists('imagecreatetruecolor')) {
        error_log('[Libros] GD library not available. Cannot generate covers.');
        return;
    }

    // Category → color scheme mapping
    $category_colors = [
        'Espiritualidad y Mindfulness' => ['#6B21A8', '#A855F7', '#E9D5FF'],
        'Finanzas e Inversiones' => ['#1E3A5F', '#2563EB', '#93C5FD'],
        'Marketing y Emprendimiento' => ['#9A3412', '#EA580C', '#FED7AA'],
        'Crianza, Familia y Educación' => ['#166534', '#22C55E', '#BBF7D0'],
        'Desarrollo Personal' => ['#1E40AF', '#3B82F6', '#BFDBFE'],
        'Mascotas' => ['#854D0E', '#CA8A04', '#FEF08A'],
        'Cocina y Recetas' => ['#991B1B', '#DC2626', '#FECACA'],
        'Aplicaciones Web Lucrativas' => ['#0F766E', '#14B8A6', '#99F6E4'],
        'Exclusivos' => ['#1C1917', '#78716C', '#F5F5F4'],
        'Belleza y Cuidado Personal' => ['#831843', '#EC4899', '#FBCFE8'],
        'Hobbies, Habilidades y Oficios' => ['#3730A3', '#6366F1', '#C7D2FE'],
        'Relaciones y Sexualidad' => ['#7C2D12', '#F97316', '#FFEDD5'],
        'Salud y Deportes' => ['#064E3B', '#10B981', '#A7F3D0'],
    ];

    // Get all products
    $products = wc_get_products([
        'limit' => -1,
        'status' => 'publish',
    ]);

    if (empty($products)) {
        error_log('[Libros] No products found.');
        return;
    }

    $count = 0;

    foreach ($products as $product) {
        // Skip if already has an image
        if ($product->get_image_id())
            continue;

        $title = $product->get_name();
        $author = $product->get_attribute('Autor');

        // Determine category for color scheme
        $cats = get_the_terms($product->get_id(), 'product_cat');
        $cat_name = '';
        $colors = ['#1A3C40', '#2E6B5A', '#E8F5E9']; // default

        if (!empty($cats) && !is_wp_error($cats)) {
            foreach ($cats as $cat) {
                if ($cat->term_id !== (int) get_option('default_product_cat')) {
                    $cat_name = $cat->name;
                    if (isset($category_colors[$cat_name])) {
                        $colors = $category_colors[$cat_name];
                    }
                    break;
                }
            }
        }

        // Generate image
        $image_path = libros_generate_cover($title, $author, $cat_name, $colors, $product->get_id());

        if ($image_path && file_exists($image_path)) {
            // Upload to WordPress media library
            $attachment_id = libros_upload_cover($image_path, $title, $product->get_id());
            if ($attachment_id) {
                $product->set_image_id($attachment_id);
                $product->save();
                $count++;
            }
            // Clean up temp file
            @unlink($image_path);
        }
    }

    update_option('libros_covers_generated', true);
    error_log("[Libros] Generated covers for $count products.");
});


/**
 * Generate a premium ebook cover image using GD.
 */
function libros_generate_cover($title, $author, $category, $colors, $product_id)
{
    $width = 600;
    $height = 800;

    $img = imagecreatetruecolor($width, $height);
    imagealphablending($img, true);
    imagesavealpha($img, true);

    // Parse colors
    $c1 = libros_hex_to_rgb($colors[0]); // dark
    $c2 = libros_hex_to_rgb($colors[1]); // mid/accent
    $c3 = libros_hex_to_rgb($colors[2]); // light

    // Draw gradient background (vertical)
    for ($y = 0; $y < $height; $y++) {
        $ratio = $y / $height;
        $r = (int) ($c1[0] + ($c2[0] - $c1[0]) * $ratio);
        $g = (int) ($c1[1] + ($c2[1] - $c1[1]) * $ratio);
        $b = (int) ($c1[2] + ($c2[2] - $c1[2]) * $ratio);
        $color = imagecolorallocate($img, $r, $g, $b);
        imageline($img, 0, $y, $width, $y, $color);
    }

    // Add decorative geometric pattern
    $accent = imagecolorallocatealpha($img, $c3[0], $c3[1], $c3[2], 100); // semi-transparent
    $seed = $product_id * 17;

    // Decorative circles
    for ($i = 0; $i < 5; $i++) {
        $cx = ($seed * ($i + 3)) % $width;
        $cy = ($seed * ($i + 7)) % $height;
        $size = 80 + (($seed * ($i + 1)) % 120);
        imagefilledellipse($img, $cx, $cy, $size, $size, $accent);
    }

    // Horizontal decorative lines
    $line_color = imagecolorallocatealpha($img, 255, 255, 255, 110);
    for ($i = 0; $i < 3; $i++) {
        $ly = 200 + ($i * 200) + (($seed * ($i + 2)) % 40);
        imageline($img, 40, $ly, $width - 40, $ly, $line_color);
    }

    // White rectangle area for text background (top area)
    $text_bg = imagecolorallocatealpha($img, 255, 255, 255, 90);
    imagefilledrectangle($img, 30, 80, $width - 30, 380, $text_bg);

    // Category badge at top
    if ($category) {
        $badge_bg = imagecolorallocatealpha($img, $c1[0], $c1[1], $c1[2], 40);
        imagefilledrectangle($img, 30, 30, $width - 30, 70, $badge_bg);

        $white = imagecolorallocate($img, 255, 255, 255);
        $cat_upper = mb_strtoupper($category, 'UTF-8');

        // Use built-in font for category (GD built-in)
        $font_size = 3;
        $cat_width = imagefontwidth($font_size) * strlen($cat_upper);
        $cat_x = ($width - $cat_width) / 2;
        imagestring($img, $font_size, (int) $cat_x, 42, strtoupper(libros_transliterate($category)), $white);
    }

    // Title text
    $dark_text = imagecolorallocate($img, $c1[0], $c1[1], $c1[2]);

    // Word-wrap the title and draw
    $title_clean = libros_transliterate($title);
    $lines = libros_word_wrap_gd($title_clean, 20); // ~20 chars per line

    $font_size_title = 5; // largest built-in GD font
    $line_height_title = 22;
    $start_y = 120;

    foreach ($lines as $i => $line) {
        $text_w = imagefontwidth($font_size_title) * strlen($line);
        $text_x = ($width - $text_w) / 2;
        imagestring($img, $font_size_title, (int) $text_x, $start_y + ($i * $line_height_title), $line, $dark_text);
    }

    // Author text
    if ($author) {
        $author_clean = libros_transliterate($author);
        $accent_color = imagecolorallocate($img, $c2[0], $c2[1], $c2[2]);

        $author_y = $start_y + (count($lines) * $line_height_title) + 30;
        $dash_line = "--- " . $author_clean . " ---";
        $auth_w = imagefontwidth(4) * strlen($dash_line);
        $auth_x = ($width - $auth_w) / 2;
        imagestring($img, 4, (int) $auth_x, $author_y, $dash_line, $accent_color);
    }

    // "EBOOK" badge at bottom
    $bottom_badge = imagecolorallocatealpha($img, $c1[0], $c1[1], $c1[2], 40);
    imagefilledrectangle($img, ($width / 2) - 60, $height - 80, ($width / 2) + 60, $height - 45, $bottom_badge);

    $white_text = imagecolorallocate($img, 255, 255, 255);
    $ebook_w = imagefontwidth(4) * 5; // "EBOOK" = 5 chars
    imagestring($img, 4, (int) (($width - $ebook_w) / 2), $height - 72, "EBOOK", $white_text);

    // Decorative corner elements
    $corner = imagecolorallocatealpha($img, 255, 255, 255, 100);
    // Top-left
    imageline($img, 15, 15, 15, 50, $corner);
    imageline($img, 15, 15, 50, 15, $corner);
    // Top-right
    imageline($img, $width - 15, 15, $width - 15, 50, $corner);
    imageline($img, $width - 15, 15, $width - 50, 15, $corner);
    // Bottom-left
    imageline($img, 15, $height - 15, 15, $height - 50, $corner);
    imageline($img, 15, $height - 15, 50, $height - 15, $corner);
    // Bottom-right
    imageline($img, $width - 15, $height - 15, $width - 15, $height - 50, $corner);
    imageline($img, $width - 15, $height - 15, $width - 50, $height - 15, $corner);

    // Save as JPEG
    $upload_dir = wp_upload_dir();
    $filename = 'cover-' . $product_id . '-' . time() . '.jpg';
    $filepath = $upload_dir['basedir'] . '/' . $filename;

    imagejpeg($img, $filepath, 92);
    imagedestroy($img);

    return $filepath;
}


/**
 * Upload an image to the WordPress media library.
 */
function libros_upload_cover($filepath, $title, $product_id)
{
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');

    $upload_dir = wp_upload_dir();
    $filename = basename($filepath);

    // Copy to uploads folder if not already there
    $new_path = $upload_dir['path'] . '/' . $filename;
    if ($filepath !== $new_path) {
        copy($filepath, $new_path);
    }

    $filetype = wp_check_filetype($filename);

    $attachment = [
        'guid' => $upload_dir['url'] . '/' . $filename,
        'post_mime_type' => $filetype['type'],
        'post_title' => sanitize_text_field($title) . ' - Portada',
        'post_content' => '',
        'post_status' => 'inherit',
    ];

    $attach_id = wp_insert_attachment($attachment, $new_path, $product_id);
    if (is_wp_error($attach_id)) {
        return false;
    }

    $attach_data = wp_generate_attachment_metadata($attach_id, $new_path);
    wp_update_attachment_metadata($attach_id, $attach_data);

    return $attach_id;
}


/**
 * Hex color to RGB array.
 */
function libros_hex_to_rgb($hex)
{
    $hex = ltrim($hex, '#');
    return [
        hexdec(substr($hex, 0, 2)),
        hexdec(substr($hex, 2, 2)),
        hexdec(substr($hex, 4, 2)),
    ];
}


/**
 * Word-wrap for GD (since GD built-in fonts don't support wrapping).
 */
function libros_word_wrap_gd($text, $max_chars_per_line)
{
    $words = explode(' ', $text);
    $lines = [];
    $current_line = '';

    foreach ($words as $word) {
        if (strlen($current_line . ' ' . $word) > $max_chars_per_line && $current_line !== '') {
            $lines[] = trim($current_line);
            $current_line = $word;
        } else {
            $current_line .= ($current_line ? ' ' : '') . $word;
        }
    }
    if ($current_line) {
        $lines[] = trim($current_line);
    }

    return $lines;
}


/**
 * Transliterate accented characters for GD (which doesn't support UTF-8 well).
 */
function libros_transliterate($str)
{
    $map = [
        'á' => 'a',
        'é' => 'e',
        'í' => 'i',
        'ó' => 'o',
        'ú' => 'u',
        'Á' => 'A',
        'É' => 'E',
        'Í' => 'I',
        'Ó' => 'O',
        'Ú' => 'U',
        'ñ' => 'n',
        'Ñ' => 'N',
        'ü' => 'u',
        'Ü' => 'U',
        '¿' => '',
        '¡' => '',
        '°' => 'o',
    ];
    return strtr($str, $map);
}
