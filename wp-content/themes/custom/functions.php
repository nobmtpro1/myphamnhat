<?php

function my_custom_wc_theme_support()
{
    add_theme_support('custom-logo');
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}


add_action('after_setup_theme', 'my_custom_wc_theme_support');

add_theme_support('post-thumbnails');
add_filter('use_block_editor_for_post', '__return_false');
function register_menus()
{
    register_nav_menus(
        array(
            'main-menu' => 'Main Menu'
        )
    );

    register_sidebar([
        'name' => 'loop_sidebar',
        'id' => 'loop_sidebar',
    ]);
}
add_action('init', 'register_menus');


function add_styles()
{
?>
    <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
    <link rel="stylesheet" href="<?= bloginfo('template_directory') ?>/assets/css/main.css" />
    <?php
}
add_action('wp_head', 'add_styles', 999999999);

add_action('elementor/frontend/after_register_scripts', function () {
    wp_register_script('script-1', 'https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js');
    wp_register_script('script-2', get_template_directory_uri() . '/assets/js/main.js');

    wp_enqueue_script('script-1', 'script-1', [], '', true);
    wp_enqueue_script('script-2', 'script-2', [], '', true);
});

function register_elementor_widgets($widgets_manager)
{
    require_once(__DIR__ . '/widgets/product_category.php');

    $widgets_manager->register(new \Elementor_product_category_Widget());
}
add_action('elementor/widgets/register', 'register_elementor_widgets');

remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);



/*woo sale amount*/
if (class_exists('woocommerce')) {
    function plus_filter_woocommerce_sale_flash_amount($output_html, $post, $product)
    {
        if ($product->get_type() == 'variable') {
            $available_variations = $product->get_available_variations();
            $maximumper = 0;
            for ($i = 0; $i < count($available_variations); ++$i) {
                $variation_id = $available_variations[$i]['variation_id'];
                $variable_product1 = new WC_Product_Variation($variation_id);
                $regular_price = $variable_product1->get_regular_price();
                $sales_price = $variable_product1->get_sale_price();
                $percentage = $sales_price ? round((1 -  $sales_price / $regular_price) * 100) : 0;
                if ($percentage > $maximumper) {
                    $maximumper = $percentage;
                }
            }
            $output_html = '<span class="badge onsale perc">-' . $maximumper . '%</span>';
        } else if ($product->get_type() == 'simple') {
            $percentage = round((1 - $product->get_sale_price() / $product->get_regular_price()) * 100);
            $output_html = '<span class="badge onsale perc">-' . $percentage . '%</span>';
        } else if ($product->get_type() == 'external') {
            $percentage = round((1 - $product->get_sale_price() / $product->get_regular_price()) * 100);
            $output_html = '<span class="badge onsale perc">-' . $percentage . '%</span>';
        } else {
            $output_html = '<span class="badge onsale">' . esc_html__('Sale', 'theplus') . '</span>';
        }
        return $output_html;
    };

    add_filter('woocommerce_sale_flash', 'plus_filter_woocommerce_sale_flash_amount', 11, 3);
}


if (function_exists('YITH_WCWL')) {
    if (!function_exists('yith_wcwl_add_counter_shortcode')) {
        function yith_wcwl_add_counter_shortcode()
        {
            add_shortcode('yith_wcwl_items_count', 'yith_wcwl_print_counter_shortcode');
        }
    }

    if (!function_exists('yith_wcwl_print_counter_shortcode')) {
        function yith_wcwl_print_counter_shortcode()
        {
    ?>
            <span class="count"><?php echo esc_html(yith_wcwl_count_all_products()); ?></span>
<?php
        }
    }
    add_action('init', 'yith_wcwl_add_counter_shortcode');
}

function my_render_product_loop()
{
    do_action('woocommerce_shop_loop');

    wc_get_template_part('content', 'product');
}
add_shortcode('my_render_product_loop', 'my_render_product_loop');

add_filter('excerpt_length', function ($length) {
    return 20;
}, PHP_INT_MAX);

function my_render_post_excerpt()
{
    echo (the_excerpt());
}
add_shortcode('my_render_post_excerpt', 'my_render_post_excerpt');
