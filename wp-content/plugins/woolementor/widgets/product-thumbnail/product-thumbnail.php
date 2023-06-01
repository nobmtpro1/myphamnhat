<?php
namespace Codexpert\Woolementor;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;

class Product_Thumbnail extends Widget_Base {

	public $id;

	public function __construct( $data = [], $args = null ) {
	    parent::__construct( $data, $args );

	    $this->id = wcd_get_widget_id( __CLASS__ );
	    $this->widget = wcd_get_widget( $this->id );
	}

	public function get_script_depends() {
		return [ "woolementor-{$this->id}", "fancybox" ];
	}

	public function get_style_depends() {
		return [ "woolementor-{$this->id}", "fancybox" ];
	}

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return $this->widget['title'];
	}

	public function get_icon() {
		return $this->widget['icon'];
	}

	public function get_categories() {
		return $this->widget['categories'];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_product_gallery_style',
			[
				'label' => __( 'Style', 'woolementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'product_thumbnail',
				'exclude'   => [ 'custom' ],
				'include'   => [],
				'default'   => 'woolementor-thumb',
			]
		);
		$this->add_control(
            'image_on_click',
            [
                'label'     => __( 'On Click', 'woolementor' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'none'          => __( 'None', 'woolementor' ),
                    'zoom'          => __( 'Zoom', 'woolementor' ),
                ],
                'default'   => 'none',
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'selector' => '{{WRAPPER}} .wl-product-thumbnail-image img',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'woolementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'.woocommerce {{WRAPPER}} .wl-product-thumbnail-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' 		=> 'image_box_shadow',
				'label' 	=> __( 'Box Shadow', 'woolementor' ),
				'selector' 	=> '{{WRAPPER}} .wl-product-thumbnail-image img',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// $product_id = isset( $_POST['product_id'] ) ? $_POST['product_id'] : get_the_ID();
		if ( ! is_woocommerce_activated() ) return;
		// if ( ! is_product() ) return;

		if ( wcd_is_edit_mode() || wcd_is_preview_mode() ) {
			$product_id 	= wcd_get_product_id();
			$product 		= wc_get_product( $product_id );
			$thumbnail_id 	= $product->get_image_id();
			$product_name 	= $product->get_name();
			$product_image 	= wp_get_attachment_image_src( $thumbnail_id, $settings['product_thumbnail_size'] );

			if ( 'none' == $settings['image_on_click'] ) {
				printf('<div class="wl-product-thumbnail-image">
						<img src="%s" alt="%s">
					</div>',
					esc_url( $product_image[0] ),
					esc_attr( $product_name )
				);
			}
			elseif ( 'zoom' == $settings['image_on_click'] ) {
				printf('<a class="wl-product-thumbnail-image wl-product-thumbnail-image-zoom" href="%s"><img src="%s" alt="%s"/></a>',
					esc_url( $product_image[0] ),
					esc_url( $product_image[0] ),
					esc_attr( $product_name )
				);
			}
		}
		elseif( !wp_doing_ajax() ) {


			global $product;
			$product = wc_get_product();

			if ( empty( $product ) && ( wcd_is_edit_mode() || wcd_is_preview_mode() ) ) {
				$product_id = wcd_get_product_id();
				$product 	= wc_get_product( $product_id );
			}

			if ( empty( $product ) ) return;

			$thumbnail_id 	= $product->get_image_id();
			$product_name 	= $product->get_name();
			$product_image 	= wp_get_attachment_image_src( $thumbnail_id, $settings['product_thumbnail_size'] );

			if ( 'none' == $settings['image_on_click'] ) {
				printf('<div class="wl-product-thumbnail-image">
						<img src="%s" alt="%s">
					</div>',
					esc_url( $product_image[0] ),
					esc_attr( $product_name )
				);
			}
			elseif ( 'zoom' == $settings['image_on_click'] ) {
				printf('<a class="wl-product-thumbnail-image wl-product-thumbnail-image-zoom" href="%s"><img src="%s" alt="%s"/></a>',
					esc_url( $product_image[0] ),
					esc_url( $product_image[0] ),
					esc_attr( $product_name )
				);
			}
		}
		else {
			if ( isset( $_POST['product_id'] ) ) {
				$product = wc_get_product( codesigner_sanitize_number( $_POST['product_id'] ) );
				
				if ( empty( $product ) ) return;

				$thumbnail_id 	= $product->get_image_id();
				$product_name 	= $product->get_name();
				$product_image 	= wp_get_attachment_image_src( $thumbnail_id, $settings['product_thumbnail_size'] );

				if ( 'none' == $settings['image_on_click'] ) {
					printf('<div class="wl-product-thumbnail-image">
							<img src="%s" alt="%s">
						</div>',
						esc_url( $product_image[0] ),
						esc_attr( $product_name )
					);
				}
				elseif ( 'zoom' == $settings['image_on_click'] ) {
					printf('<a class="wl-product-thumbnail-image wl-product-thumbnail-image-zoom" href="%s"><img src="%s" alt="%s"/></a>',
						esc_url( $product_image[0] ),
						esc_url( $product_image[0] ),
						esc_attr( $product_name )
					);
				}
			}
		}

        /**
         * Load Script
         */
        $this->render_script();
	}

	protected function render_script() {
        ?>
        <script>
            jQuery(function($){
                $(".wl-product-thumbnail-image-zoom").fancybox({
                    arrows: true,
                    'transitionIn'  :   'elastic',
                    'transitionOut' :   'elastic',
                    'speedIn'       :   600, 
                    'speedOut'      :   200, 
                    'overlayShow'   :   false

                }).attr('data-fancybox', 'gallery');
            })
        </script>
        <?php
    }
}