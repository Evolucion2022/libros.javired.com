<?php
/**
 * Plugin Name: Libros - Crear Productos de Ejemplo
 * Description: Crea 78 ebooks en 13 categorías. Se ejecuta UNA sola vez.
 */

if (get_option('libros_sample_products_created'))
    return;

add_action('init', function () {
    if (get_option('libros_sample_products_created'))
        return;
    if (!class_exists('WC_Product_Simple'))
        return;
    if (!is_admin() && !defined('DOING_CRON'))
        return;

    $data_file = __DIR__ . '/libros-product-data.php';
    if (!file_exists($data_file))
        return;

    $categories_data = include $data_file;

    foreach ($categories_data as $cat_name => $books) {
        // Crear o recuperar categoría
        $term = term_exists($cat_name, 'product_cat');
        if (!$term) {
            $term = wp_insert_term($cat_name, 'product_cat', [
                'slug' => sanitize_title($cat_name),
            ]);
        }
        $cat_id = is_array($term) ? $term['term_id'] : $term;

        foreach ($books as $book) {
            // Verificar si ya existe
            $existing = get_page_by_title($book['title'], OBJECT, 'product');
            if ($existing)
                continue;

            $product = new WC_Product_Simple();
            $product->set_name($book['title']);
            $product->set_slug(sanitize_title($book['title']));
            $product->set_description(libros_build_description($book));
            $product->set_short_description($book['short']);
            $product->set_regular_price($book['price']);
            if (!empty($book['sale'])) {
                $product->set_sale_price($book['sale']);
            }
            $product->set_sku($book['sku']);
            $product->set_catalog_visibility('visible');
            $product->set_status('publish');
            $product->set_virtual(true);
            $product->set_downloadable(true);
            $product->set_sold_individually(true);
            $product->set_manage_stock(false);
            $product->set_category_ids([(int) $cat_id]);

            // Atributos de autor y páginas
            $attrs = [];
            $autor_attr = new WC_Product_Attribute();
            $autor_attr->set_name('Autor');
            $autor_attr->set_options([$book['author']]);
            $autor_attr->set_visible(true);
            $attrs[] = $autor_attr;

            $pages_attr = new WC_Product_Attribute();
            $pages_attr->set_name('Páginas');
            $pages_attr->set_options([$book['pages']]);
            $pages_attr->set_visible(true);
            $attrs[] = $pages_attr;

            $format_attr = new WC_Product_Attribute();
            $format_attr->set_name('Formato');
            $format_attr->set_options(['PDF + EPUB']);
            $format_attr->set_visible(true);
            $attrs[] = $format_attr;

            $product->set_attributes($attrs);
            $product->save();
        }
    }

    update_option('libros_sample_products_created', true);
    error_log('[Libros] ✅ 78 productos creados exitosamente.');
}, 20);


/**
 * Genera HTML completo para la descripción del producto.
 */
function libros_build_description($book)
{
    $bullets_html = '';
    if (!empty($book['bullets'])) {
        $bullets_html = '<ul>';
        foreach ($book['bullets'] as $b) {
            $bullets_html .= "<li>{$b}</li>";
        }
        $bullets_html .= '</ul>';
    }

    $html = "
<h2>Sobre este libro</h2>
<p>{$book['desc']}</p>

<h3>¿Qué aprenderás?</h3>
{$bullets_html}

<h3>Sobre el autor</h3>
<p><strong>{$book['author']}</strong> — {$book['author_bio']}</p>

<h3>Detalles del producto</h3>
<ul>
    <li><strong>Formato:</strong> PDF + EPUB</li>
    <li><strong>Páginas:</strong> {$book['pages']}</li>
    <li><strong>Idioma:</strong> Español</li>
    <li><strong>Entrega:</strong> Descarga digital inmediata</li>
    <li><strong>Garantía:</strong> 7 días de devolución</li>
</ul>
";
    return $html;
}
