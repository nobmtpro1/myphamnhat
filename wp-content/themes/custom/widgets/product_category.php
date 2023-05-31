<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Elementor_product_category_Widget extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'product_category';
    }
    public function get_title()
    {
        return esc_html__('product_category', 'elementor-product_category-widget');
    }
    public function get_keywords()
    {
        return ['product_category'];
    }

    protected function render()
    {
        $taxonomy  = 'product_cat';
        $tax_terms = get_terms($taxonomy, array('hide_empty' => true, 'parent' => 0));
?>
        <div class="main-carousel" data-flickity='{ "groupCells": true }'>
            <?php foreach ($tax_terms as $term) : ?>
                <?php $thum_id = @get_woocommerce_term_meta($term->term_id, 'thumbnail_id', true) ?>
                <a href="<?= get_term_link($term) ?>" class="carousel-cell">
                    <img width="200" src="<?= $thum_id ? @wp_get_attachment_url($thum_id) : bloginfo('template_directory') . '/assets/img/example.jpg' ?>" alt="">
                    <div class="content">
                        <h3><?= $term->name ?></h3>
                    </div>

                </a>
            <?php endforeach; ?>
        </div>
<?php
    }
}
