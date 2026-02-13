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
    // Only run once (v2 = unified green palette)
    if (get_option('libros_covers_generated_v2'))
        return;

    // Make sure WooCommerce is active
    if (!class_exists('WooCommerce'))
        return;

    // Make sure GD is available
    if (!function_exists('imagecreatetruecolor')) {
        error_log('[Libros] GD library not available. Cannot generate covers.');
        return;
    }

    // Unified emerald / forest green palette — subtle tonal variations per category
    $category_colors = [
        'Espiritualidad y Mindfulness' => ['#0D3B2E', '#1A6B4A', '#D4E8DC'],
        'Finanzas e Inversiones' => ['#0F3D30', '#1B7050', '#D6EBDF'],
        'Marketing y Emprendimiento' => ['#123F33', '#1D7553', '#D8EDE1'],
        'Crianza, Familia y Educación' => ['#0E3A2C', '#186845', '#D2E6D9'],
        'Desarrollo Personal' => ['#104135', '#1F7A58', '#DAF0E4'],
        'Mascotas' => ['#113D2F', '#1C6F4D', '#D5EADD'],
        'Cocina y Recetas' => ['#0D3929', '#176442', '#D0E4D6'],
        'Aplicaciones Web Lucrativas' => ['#134336', '#21805C', '#DCF2E6'],
        'Exclusivos' => ['#0B3526', '#14603E', '#CEE2D3'],
        'Belleza y Cuidado Personal' => ['#0F3C2D', '#196947', '#D3E7DA'],
        'Hobbies, Habilidades y Oficios' => ['#114034', '#1E7856', '#D9EEE2'],
        'Relaciones y Sexualidad' => ['#103E31', '#1D7451', '#D7ECDF'],
        'Salud y Deportes' => ['#0E3B2D', '#1A6C49', '#D4E9DB'],
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
        // Delete existing cover to regenerate with new palette
        $old_image_id = $product->get_image_id();
        if ($old_image_id) {
            wp_delete_attachment($old_image_id, true);
            $product->set_image_id(0);
            $product->save();
        }

        $title = $product->get_name();
        $author = $product->get_attribute('Autor');

        // Determine category for color scheme
        $cats = get_the_terms($product->get_id(), 'product_cat');
        $cat_name = '';
        $colors = ['#0D3B2E', '#1A6B4A', '#D4E8DC']; // default green

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

    update_option('libros_covers_generated_v2', true);
    error_log("[Libros] Generated v2 covers for $count products.");
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
