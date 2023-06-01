<?php
namespace Codexpert\Woolementor;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Background;
use Codexpert\Woolementor\Controls\Group_Control_Gradient_Text;

class Shop_Standard extends Widget_Base {

	public $id;

	public function __construct( $data = [], $args = null ) {
	    parent::__construct( $data, $args );

	    $this->id = wcd_get_widget_id( __CLASS__ );
	    $this->widget = wcd_get_widget( $this->id );
	    
		// Are we in debug mode?
		$min = defined( 'WOOLEMENTOR_DEBUG' ) && WOOLEMENTOR_DEBUG ? '' : '.min';

		wp_register_style( "woolementor-{$this->id}", plugins_url( "assets/css/style{$min}.css", __FILE__ ), [], '1.1' );
	}

	public function get_script_depends() {
		return [ "woolementor-{$this->id}", 'fancybox' ];
	}

	public function get_style_depends() {
		return [ "woolementor-{$this->id}", 'fancybox' ];
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

		do_action( 'codesigner_before_shop_content_controls', $this );

		/**
		 * Settings controls
		 */
		$this->start_controls_section(
			'_section_settings',
			[
				'label' => __( 'Layout', 'woolementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
		    'columns',
		    [
		        'label'     => __( 'Columns', 'woolementor' ),
		        'type'      => Controls_Manager::SELECT,
		        'options'   => [
		            1 => __( '1 Column', 'woolementor' ),
		            2 => __( '2 Columns', 'woolementor' ),
		            3 => __( '3 Columns', 'woolementor' ),
		            4 => __( '4 Columns', 'woolementor' ),
		        ],
		        'desktop_default'   => 2,
		        'tablet_default'    => 2,
		        'mobile_default'    => 1,
		        'style_transfer'    => true,
		        'selectors' => [
		            '.wl {{WRAPPER}} .cx-grid' => 'grid-template-columns: repeat({{VALUE}}, minmax(100px,1fr));',
		        ],
		    ]
		);

		$this->add_control(
			'alignment',
			[
				'label'		=> __( 'Content Alignment', 'woolementor' ),
				'type' 		=>Controls_Manager::CHOOSE,
				'options' 	=> [
					'wl-ss-left' 	=> [
						'title' 	=> __( 'Left', 'woolementor' ),
						'icon' 		=> 'eicon-text-align-left',
					],
					'wl-ss-right' 	=> [
						'title' 	=> __( 'Right', 'woolementor' ),
						'icon' 		=> 'eicon-text-align-right',
					],
				],
				'default' 	=> 'wl-ss-left',
				'toggle'    => false,
			]
		);

		$this->end_controls_section();

		do_action( 'codesigner_shop_query_controls', $this );

		/**
		 * Image controls
		 */
		$this->start_controls_section(
			'section_content_product_image',
			[
				'label' => __( 'Product Image', 'woolementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
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
					'product_page'  => __( 'Product Page', 'woolementor' ),
				],
				'default'   => 'none',
			]
		);

		$this->end_controls_section();

		/**
		 * Sale Ribbon controls
		 */
		$this->start_controls_section(
			'section_content_sale_ribbon',
			[
				'label' => __( 'Sale Ribbon', 'woolementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'sale_ribbon_show_hide',
			[
				'label' 		=> __( 'Show/Hide', 'woolementor' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Show', 'woolementor' ),
				'label_off' 	=> __( 'Hide', 'woolementor' ),
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
			]
		);

		$this->add_control(
			'sale_ribbon_text',
			[
				'label' 		=> __( 'On Sale Test', 'woolementor' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __( 'Sale', 'woolementor' ),
				'placeholder' 	=> __( 'Type your title here', 'woolementor' ),
				'condition' 	=> [
                    'sale_ribbon_show_hide' => 'yes'
                ],
			]
		);

		$this->end_controls_section();

		/**
         * Stock Ribbon controls
         */
        $this->start_controls_section(
            'section_content_stock',
            [
                'label' => __( 'Stock text', 'woolementor' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'stock_show_hide',
            [
                'label'         => __( 'Show/Hide', 'woolementor' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'woolementor' ),
                'label_off'     => __( 'Hide', 'woolementor' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
            ]
        );

        $this->add_control(
            'stock_ribbon_text',
            [
                'label'         => __( 'Text', 'woolementor' ),
                'type'          => Controls_Manager::TEXT,
                'default'       => __( 'Out Of Stock', 'woolementor' ),
                'placeholder'   => __( 'Type your text here', 'woolementor' ),
                'condition' => [
                    'stock_show_hide' => 'yes'
                ],
            ]
        );

        $this->end_controls_section();

		/**
		 * Cart controls
		 */
		$this->start_controls_section(
			'section_content_cart',
			[
				'label' => __( 'Cart', 'woolementor' ) . wcd_pro_text(),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'cart_show_hide',
			[
				'label' 		=> __( 'Show/Hide', 'woolementor' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Show', 'woolementor' ),
				'label_off' 	=> __( 'Hide', 'woolementor' ),
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
			]
		);

		$this->end_controls_section();

		/**
		 * Wishlist controls
		 */
		$this->start_controls_section(
			'section_content_wishlist',
			[
				'label' => __( 'Wishlist', 'woolementor' ) . wcd_pro_text(),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'wishlist_show_hide',
			[
				'label' 		=> __( 'Show/Hide', 'woolementor' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Show', 'woolementor' ),
				'label_off' 	=> __( 'Hide', 'woolementor' ),
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
			]
		);

		$this->end_controls_section();

		/**
		 * Pagination controls
		 */
		$this->start_controls_section(
			'section_content_pagination',
			[
				'label' => __( 'Pagination', 'woolementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
                'condition'     => [
                    'product_source' => 'shop'
                ],
			]
		);

		$this->add_control(
			'pagination_show_hide',
			[
				'label'         => __( 'Show/Hide', 'woolementor' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'Show', 'woolementor' ),
				'label_off'     => __( 'Hide', 'woolementor' ),
				'return_value'  => 'yes',
				'default'       => 'yes',
			]
		);

		$this->end_controls_section();
		
		do_action( 'codesigner_after_shop_content_controls', $this );
		do_action( 'codesigner_before_shop_style_controls', $this );

		/**
		 * Product Style controls
		 */
		$this->start_controls_section(
			'style_section_box',
			[
				'label' => __( 'Card', 'woolementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);



		$this->add_responsive_control(
			'widget_box_height',
			[
				'label' 	=> __( 'Box Height', 'woolementor' ),
				'type' 		=> Controls_Manager::SLIDER,
				'size_units'=> [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wl-ss-single-widget' => 'height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .wl-ss-single-product' => 'height: {{SIZE}}{{UNIT}}',
				],
				'range' 	=> [
					'px' 	=> [
						'min' 	=> 1,
						'max' 	=> 500
					],
					'em' 	=> [
						'min' 	=> 1,
						'max' 	=> 30
					],
				],
				'desktop_default' => [
					'size' => '',
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 202,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 100,
					'unit' => '%',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' 		=> 'widget_box_background',
				'label' 	=> __( 'Background', 'woolementor' ),
				'types' 	=> [ 'classic', 'gradient' ],
				'selector' 	=> '{{WRAPPER}} .wl-ss-single-widget',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' 		=> 'widget_box_border',
				'label' 	=> __( 'Border', 'woolementor' ),
				'selector' 	=> '{{WRAPPER}} .wl-ss-single-widget',
				'separator' => 'before'
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' 		=> 'widget_box_shadow',
				'label' 	=> __( 'Box Shadow', 'woolementor' ),
				'selector' 	=> '{{WRAPPER}} .wl-ss-single-widget',
			]
		);

		$this->add_responsive_control(
		    'gap',
		    [
		        'label'     => __( 'Gap Row', 'woolementor' ),
		        'type'      => Controls_Manager::SLIDER,
		        'size_units'=> [ 'px', '%', 'em' ],
		        'selectors' => [
		            '.wl {{WRAPPER}} .cx-grid' => 'grid-row-gap: {{SIZE}}{{UNIT}}',
		        ],
		        'range'     => [
		            'px'    => [
		                'min'   => 1,
		                'max'   => 500
		            ],
		            'em'    => [
		                'min'   => 1,
		                'max'   => 30
		            ],
		        ],
		        'default' => [
		            'unit' => 'px',
		            'size' => 15,
		        ],
		    ]
		);

		$this->add_responsive_control(
		    'gap_column',
		    [
		        'label'     => __( 'Gap Column', 'woolementor' ),
		        'type'      => Controls_Manager::SLIDER,
		        'size_units'=> [ 'px', '%', 'em' ],
		        'selectors' => [
		            '.wl {{WRAPPER}} .cx-grid' => 'grid-column-gap: {{SIZE}}{{UNIT}}',
		        ],
		        'range'     => [
		            'px'    => [
		                'min'   => 1,
		                'max'   => 500
		            ],
		            'em'    => [
		                'min'   => 1,
		                'max'   => 30
		            ],
		        ],
		        'default' => [
		            'unit' => 'px',
		            'size' => 15,
		        ],
		    ]
		);

		$this->end_controls_section();

		/**
		 * Product Title
		 */
		$this->start_controls_section(
			'section_style_title',
			[
				'label' => __( 'Product Title', 'woolementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
            Group_Control_Gradient_Text::get_type(),
            [
                'name' => 'title_gradient_color',
                'selector' => '{{WRAPPER}} .wl-gradient-heading',
            ]
        );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'title_typography',
				'label' 	=> __( 'Typography', 'woolementor' ),
				'scheme' 	=> Typography::TYPOGRAPHY_1,
                'fields_options'    => [
                    'typography'    => [ 'default' => 'yes' ],
                    'font_size'     => [ 'default' => [ 'size' => 16 ] ],
                    // 'line_height'   => [ 'default' => [ 'size' => 37 ] ],
                    'font_family'   => [ 'default' => 'Montserrat' ],
                    'font_weight'   => [ 'default' => 500 ],
                ],
				'selector' 	=> '{{WRAPPER}} .wl-ss-product-name a',
			]
		);

		$this->end_controls_section();

		/**
		 * Product Short Dexcription
		 */
		$this->start_controls_section(
			'section_short_description',
			[
				'label' => __( 'Short Description', 'woolementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'short_description_show_hide',
			[
				'label'         => __( 'Show Content', 'woolementor' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'Show', 'your-plugin' ),
				'label_off'     => __( 'Hide', 'your-plugin' ),
				'return_value'  => 'yes',
				'default'       => 'yes',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'short_description_typography',
				'label'     => __( 'Typography', 'woolementor' ),
				'scheme'    => Typography::TYPOGRAPHY_3,
                'fields_options'    => [
                    'typography'    => [ 'default' => 'yes' ],
                    'font_size'     => [ 'default' => [ 'size' => 14 ] ],
                    // 'line_height'   => [ 'default' => [ 'size' => 37 ] ],
                    'font_family'   => [ 'default' => 'Montserrat' ],
                    'font_weight'   => [ 'default' => 400 ],
                ],
				'selector'  => '{{WRAPPER}} .wl-ss-product-info .wl-ss-product-desc p',
			]
		); 

		$this->add_control(
			'short_description_color',
			[
				'label'     => __( 'Color', 'woolementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wl-ss-product-info .wl-ss-product-desc p' => 'color: {{VALUE}}',
				],
			]
		);

        $this->add_control(
			'product_desc_words_count',
			[
				'label' 		=> __( 'Words Count', 'woolementor' ),
				'type' 			=> Controls_Manager::NUMBER,
				'default' 		=> 20,
			]
		);

		$this->end_controls_section();

		/**
		 * Product Price
		 */
		$this->start_controls_section(
			'section_style_price',
			[
				'label' => __( 'Product Price', 'woolementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'price_color',
			[
				'label'     => __( 'Color', 'woolementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wl-ss-info-icons .wl-ss-price h2' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wl-ss-info-icons .wl-ss-price h2 .woocommerce-Price-amount.amount' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wl-ss-info-icons .wl-ss-price ins .amount' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wl-ss-info-icons .wl-ss-price > .woocommerce-Price-amount.amount' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'price_size_typography',
				'label' 	=> __( 'Typography', 'woolementor' ),
				'scheme' 	=> Typography::TYPOGRAPHY_3,
                'fields_options'    => [
                    'typography'    => [ 'default' => 'yes' ],
                    'font_size'     => [ 'default' => [ 'size' => 14 ] ],
                    // 'line_height'   => [ 'default' => [ 'size' => 37 ] ],
                    'font_family'   => [ 'default' => 'Montserrat' ],
                    'font_weight'   => [ 'default' => 500 ],
                ],
				'selector' 	=> '{{WRAPPER}} .wl-ss-info-icons .wl-ss-price ins, {{WRAPPER}} .wl-ss-info-icons .wl-ss-price h2 > .amount',
			]
		);    

		$this->add_control(
			'sale_price_show_hide',
			[
				'label'			=> __( 'Show Sale Price', 'woolementor' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Show', 'your-plugin' ),
				'label_off' 	=> __( 'Hide', 'your-plugin' ),
				'return_value' 	=> 'block',
				'default' 		=> 'none',
				'separator' 	=> 'before',
				'selectors' 	=> [
					'{{WRAPPER}} .wl-ss-info-icons .wl-ss-price del' => 'display: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'sale_price_color',
			[
				'label'     => __( 'Color', 'woolementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wl-ss-info-icons .wl-ss-price h2 del .woocommerce-Price-amount.amount' => 'color: {{VALUE}}',
				],
				'condition' => [
					'sale_price_show_hide' => 'block'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'sale_price_size_typography',
				'label' 	=> __( 'Typography', 'woolementor' ),
				'scheme' 	=> Typography::TYPOGRAPHY_3,
                'fields_options'    => [
                    'typography'    => [ 'default' => 'yes' ],
                    'font_size'     => [ 'default' => [ 'size' => 14 ] ],
                    // 'line_height'   => [ 'default' => [ 'size' => 37 ] ],
                    'font_family'   => [ 'default' => 'Montserrat' ],
                    'font_weight'   => [ 'default' => 400 ],
                ],
				'selector' 	=> '{{WRAPPER}} .wl-ss-info-icons .wl-ss-price h2 del',
				'condition' => [
					'sale_price_show_hide' => 'block'
				],
			]
		);

		$this->end_controls_section();

		/**
		 * Product Currency Symbol
		 */
		$this->start_controls_section(
			'section_style_currency',
			[
				'label' => __( 'Currency Symbol', 'woolementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'price_currency',
			[
				'label'     => __( 'Color', 'woolementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-Price-currencySymbol' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'price_currency_typography',
				'label' 	=> __( 'Typography', 'woolementor' ),
				'scheme' 	=> Typography::TYPOGRAPHY_3,
                'fields_options'    => [
                    'typography'    => [ 'default' => 'yes' ],
                    'font_size'     => [ 'default' => [ 'size' => 14 ] ],
                    // 'line_height'   => [ 'default' => [ 'size' => 37 ] ],
                    'font_family'   => [ 'default' => 'Montserrat' ],
                    'font_weight'   => [ 'default' => 400 ],
                ],
				'selector' 	=> '{{WRAPPER}} .woocommerce-Price-currencySymbol',
			]
		);

		$this->end_controls_section();

		/**
		 * Product Image controls
		 */
		$this->start_controls_section(
			'section_style_image',
			[
				'label' => __( 'Product Image', 'woolementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' 		=> 'image_thumbnail',
				'exclude' 	=> [ 'custom' ],
				'include' 	=> [],
				'default' 	=> 'large',
			]
		);

		$this->add_responsive_control(
			'image_width',
			[
				'label' 	=> __( 'Image Width', 'woolementor' ),
				'type' 		=> Controls_Manager::SLIDER,
				'size_units'=> [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wl-ss-product-img img' => 'width: {{SIZE}}{{UNIT}}',
				],
				'range' 	=> [
					'px' 	=> [
						'min' 	=> 1,
						'max' 	=> 500
					],
					'em' 	=> [
						'min' 	=> 1,
						'max' 	=> 30
					],
				],
			]
		);

		$this->add_responsive_control(
			'image_height',
			[
				'label' 	=> __( 'Image Height', 'woolementor' ),
				'type' 		=> Controls_Manager::SLIDER,
				'size_units'=> [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wl-ss-product-img img' => 'height: {{SIZE}}{{UNIT}}',
				],
                'range'     => [
                    'px'    => [
                        'min'   => 1,
                        'max'   => 500
                    ],
                    'em'    => [
                        'min'   => 1,
                        'max'   => 30
                    ],
                ],
			]
		);

		$this->add_responsive_control(
			'image_box_height',
			[
				'label' 	=> __( 'Image Box Height', 'woolementor' ),
				'type' 		=> Controls_Manager::SLIDER,
				'size_units'=> [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wl-ss-product-img' => 'height: {{SIZE}}{{UNIT}}',
				],
                'range'     => [
                    'px'    => [
                        'min'   => 1,
                        'max'   => 500
                    ],
                    'em'    => [
                        'min'   => 1,
                        'max'   => 30
                    ],
                ],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' 		=> 'image_border',
				'label' 	=> __( 'Border', 'woolementor' ),
				'selector' 	=> '{{WRAPPER}} .wl-ss-product-img img',
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label' 		=> __( 'Border Radius', 'woolementor' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .wl-ss-product-img img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' 		=> 'image_box_shadow',
				'label' 	=> __( 'Box Shadow', 'woolementor' ),
				'selector' 	=> '{{WRAPPER}} .wl-ss-product-img img',
			]
		);

		$this->start_controls_tabs(
			'image_effects',
			[
				'separator' => 'before'
			]
		);

		$this->start_controls_tab(
			'image_effects_normal',
			[
				'label' 	=> __( 'Normal', 'woolementor' ),
			]
		);

		$this->add_control(
			'image_opacity',
			[
				'label' 	=> __( 'Opacity', 'woolementor' ),
				'type' 		=> Controls_Manager::SLIDER,
				'range' 	=> [
					'px' 	=> [
						'max' 	=> 1,
						'min' 	=> 0.10,
						'step' 	=> 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wl-ss-product-img img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' 		=> 'image_css_filters',
				'selector' 	=> '{{WRAPPER}} .wl-ss-product-img img',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'image_hover',
			[
				'label' 	=> __( 'Hover', 'woolementor' ),
			]
		);

		$this->add_control(
			'image_opacity_hover',
			[
				'label' 	=> __( 'Opacity', 'woolementor' ),
				'type' 		=> Controls_Manager::SLIDER,
				'range' 	=> [
					'px' 	=> [
						'max' 	=> 1,
						'min' 	=> 0.10,
						'step' 	=> 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wl-ss-product-img img:hover' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' 		=> 'image_css_filters_hover',
				'selector' 	=> '{{WRAPPER}} .wl-ss-product-img img:hover',
			]
		);

		$this->add_control(
			'image_hover_transition',
			[
				'label' 	=> __( 'Transition Duration', 'woolementor' ),
				'type' 		=> Controls_Manager::SLIDER,
				'range' 	=> [
					'px' 	=> [
						'max' 	=> 3,
						'step' 	=> 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wl-ss-product-img img:hover' => 'transition-duration: {{SIZE}}s',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		/**
        * Sale Ribbon Styleing 
        */
		$this->start_controls_section(
			'section_style_sale_ribbon',
			[
				'label' => __( 'Sale Ribbon', 'woolementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' 	=> [
                    'sale_ribbon_show_hide' => 'yes'
                ],
			]
		);

		$this->add_control(
			'sale_ribbon_offset_toggle',
			[
				'label' 		=> __( 'Offset', 'woolementor' ),
				'type' 			=> Controls_Manager::POPOVER_TOGGLE,
				'label_off' 	=> __( 'None', 'woolementor' ),
				'label_on' 		=> __( 'Custom', 'woolementor' ),
				'return_value' 	=> 'yes',
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'media_offset_x',
			[
				'label' 		=> __( 'Offset Left', 'woolementor' ),
				'type' 			=> Controls_Manager::SLIDER,
				'size_units' 	=> ['px', '%'],
				'condition' 	=> [
					'sale_ribbon_offset_toggle' => 'yes'
				],
				'range' 		=> [
					'px' 		=> [
						'min' 	=> -500,
						'max' 	=> 700,
						'step' 	=> 1,
					],
					'%' 		=> [
						'min' 	=> -100,
						'max' 	=> 150,
						'step' 	=> 1,
					],
				],
				'selectors' 	=> [
					'{{WRAPPER}} .wl-ss-corner-ribbon' => 'left: {{SIZE}}{{UNIT}}'
				],
				'render_type' 	=> 'ui',
			]
		);

		$this->add_responsive_control(
			'media_offset_y',
			[
				'label' 		=> __( 'Offset Top', 'woolementor' ),
				'type' 			=> Controls_Manager::SLIDER,
				'size_units' 	=> ['px', '%'],
				'condition' 	=> [
					'sale_ribbon_offset_toggle' => 'yes'
				],
				'range' 		=> [
					'px' 		=> [
						'min' 	=> -500,
						'max' 	=> 700,
						'step' 	=> 1,
					],
					'%' 		=> [
						'min' 	=> -100,
						'max' 	=> 150,
						'step' 	=> 1,
					],
				],
				'selectors' 	=> [
					'{{WRAPPER}} .wl-ss-corner-ribbon' => 'top: {{SIZE}}{{UNIT}}'
				]
			]
		);
		$this->end_popover();

		$this->add_responsive_control(
			'sale_ribbon_width',
			[
				'label' 	=> __( 'Width', 'woolementor' ),
				'type' 		=> Controls_Manager::SLIDER,
				'size_units'=> [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wl-ss-corner-ribbon' => 'width: {{SIZE}}{{UNIT}}',
				],
				'range' 	=> [
					'px' 	=> [
						'min' 	=> 50,
						'max' 	=> 500
					]
				],
			]
		);

		$this->add_responsive_control(
			'sale_ribbon_transform',
			[
				'label' 	=> __( 'Transform', 'woolementor' ),
				'type' 		=> Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .wl-ss-corner-ribbon' => '-webkit-transform: rotate({{SIZE}}deg); transform: rotate({{SIZE}}deg);'
				],
				'range' 	=> [
					'px' 	=> [
						'min' 	=> 0,
						'max' 	=> 360
					]
				],
			]
		);

		$this->add_control(
			'sale_ribbon_font_color',
			[
				'label'     => __( 'Color', 'woolementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wl-ss-corner-ribbon' => 'color: {{VALUE}}',
				],
				'separator' => 'before'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'content_typography',
				'label' 	=> __( 'Typography', 'woolementor' ),
				'scheme' 	=> Typography::TYPOGRAPHY_3,
                'fields_options'    => [
                    'typography'    => [ 'default' => 'yes' ],
                    'font_size'     => [ 'default' => [ 'size' => 14 ] ],
                    // 'line_height'   => [ 'default' => [ 'size' => 37 ] ],
                    'font_family'   => [ 'default' => 'Montserrat' ],
                    'font_weight'   => [ 'default' => 400 ],
                ],
				'selector' 	=> '{{WRAPPER}} .wl-ss-corner-ribbon',
			]
		);

		$this->add_control(
			'sale_ribbon_background',
			[
				'label' 		=> __( 'Background', 'woolementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .wl-ss-corner-ribbon' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'sale_ribbon_padding',
			[
				'label' 		=> __( 'Padding', 'woolementor' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .wl-ss-corner-ribbon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'       => [
					'top'           => '2',
					'right'         => '12',
					'bottom'        => '2',
					'left'          => '12',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' 			=> 'sale_ribbon_border',
				'label' 		=> __( 'Border', 'woolementor' ),
				'selector' 		=> '{{WRAPPER}} .wl-ss-corner-ribbon',
				'separator' 	=> 'before'
			]
		);

		$this->add_responsive_control(
			'sale_ribbon_border_radius',
			[
				'label' 		=> __( 'Border Radius', 'woolementor' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .wl-ss-corner-ribbon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		/**
        * Stock Ribbon Styleing 
        */

        $this->start_controls_section(
            'section_style_stock_ribbon',
            [
                'label' => __( 'Stock Ribbon', 'woolementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'stock_show_hide' => 'yes'
                ],
            ]
        );

        $this->add_control(
            'stock_offset_toggle',
            [
                'label'         => __( 'Offset', 'woolementor' ),
                'type'          => Controls_Manager::POPOVER_TOGGLE,
                'label_off'     => __( 'None', 'woolementor' ),
                'label_on'      => __( 'Custom', 'woolementor' ),
                'return_value'  => 'yes',
            ]
        );

        $this->start_popover();

        $this->add_responsive_control(
            'stock_media_offset_x',
            [
                'label'         => __( 'Offset Left', 'woolementor' ),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%'],
                'condition'     => [
                    'stock_offset_toggle' => 'yes'
                ],
                'range'         => [
                    'px'        => [
                        'min'   => -1000,
                        'max'   => 1000,
                    ],
                ],
                'selectors'     => [
                    '{{WRAPPER}} .wl-ss-stock' => 'left: {{SIZE}}{{UNIT}}'
                ],
                'render_type'   => 'ui',
            ]
        );

        $this->add_responsive_control(
            'stock_media_offset_y',
            [
                'label'         => __( 'Offset Top', 'woolementor' ),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%'],
                'condition'     => [
                    'stock_offset_toggle' => 'yes'
                ],
                'range'         => [
                    'px'        => [
                        'min'   => -1000,
                        'max'   => 1000,
                    ],
                ],
                'selectors'     => [
                    '{{WRAPPER}} .wl-ss-stock' => 'top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->end_popover();

        $this->add_responsive_control(
            'stock_ribbon_width',
            [
                'label'     => __( 'Width', 'woolementor' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units'=> [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .wl-ss-stock' => 'width: {{SIZE}}{{UNIT}}',
                ],
                'range'     => [
                    'px'    => [
                        'min'   => 50,
                        'max'   => 500
                    ]
                ],
            ]
        );

        $this->add_responsive_control(
            'stock_ribbon_transform',
            [
                'label'     => __( 'Transform', 'woolementor' ),
                'type'      => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .wl-ss-stock' => '-webkit-transform: rotate({{SIZE}}deg); transform: rotate({{SIZE}}deg);',
                ],
                'range'     => [
                    'px'    => [
                        'min'   => 0,
                        'max'   => 360
                    ]
                ],
            ]
        );

        $this->add_control(
            'stock_ribbon_font_color',
            [
                'label'     => __( 'Color', 'woolementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wl-ss-stock' => 'color: {{VALUE}}',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'stock_content_typography',
                'label'     => __( 'Typography', 'woolementor' ),
                'scheme'    => Typography::TYPOGRAPHY_3,
                'fields_options'    => [
                    'typography'    => [ 'default' => 'yes' ],
                    'font_size'     => [ 'default' => [ 'size' => 14 ] ],
                    // 'line_height'   => [ 'default' => [ 'size' => 37 ] ],
                    'font_family'   => [ 'default' => 'Montserrat' ],
                    'font_weight'   => [ 'default' => 400 ],
                ],
                'selector'  => '{{WRAPPER}} .wl-ss-stock',
            ]
        );

        $this->add_control(
            'stock_ribbon_background',
            [
                'label'         => __( 'Background', 'woolementor' ),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .wl-ss-stock' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'stock_ribbon_padding',
            [
                'label'         => __( 'Padding', 'woolementor' ),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => [ 'px', '%', 'em' ],
                'selectors'     => [
                    '{{WRAPPER}} .wl-ss-stock' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after'
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'          => 'stock_ribbon_border',
                'label'         => __( 'Border', 'woolementor' ),
                'selector'      => '{{WRAPPER}} .wl-ss-stock',
            ]
        );

        $this->add_responsive_control(
            'stock_ribbon_border_radius',
            [
                'label'         => __( 'Border Radius', 'woolementor' ),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => [ 'px', '%' ],
                'selectors'     => [
                    '{{WRAPPER}} .wl-ss-stock' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

		/**
		 * Cart Button
		 */
		$this->start_controls_section(
			'section_style_cart',
			[
				'label' 	=> __( 'Cart Button', 'woolementor' ),
				'tab'   	=> Controls_Manager::TAB_STYLE,
				'condition'     => [
				    'cart_show_hide' => 'yes'
				],
			]
		);

		$this->add_control(
            'cart_icon',
            [
                'label'         => __( 'Icon', 'woolementor' ),
                'type'          => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default'       => [
                    'value'     => 'eicon-cart-solid',
                    'library'   => 'fa-solid',
                ],
                'recommended'   => [
                    'fa-regular' => [
                        'luggage-cart',
                        'opencart',
                    ],
                    'fa-solid'  => [
                        'shopping-cart',
                        'cart-arrow-down',
                        'cart-plus',
                        'luggage-cart',
                    ]
                ]
            ]
        );

		$this->add_responsive_control(
			'cart_icon_size',
			[
				'label'     => __( 'Icon Size', 'woolementor' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units'=> [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wl-ss-product-cart i' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->start_controls_tabs(
            'cart_normal_separator',
            [
                'separator' => 'before'
            ]
        );

        $this->start_controls_tab(
            'cart_normal',
            [
                'label'     => __( 'Normal', 'woolementor' ),
            ]
        );

		$this->add_control(
			'cart_icon_color',
			[
				'label'     => __( 'Color', 'woolementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wl-ss-product-cart i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'cart_icon_bg',
			[
				'label'     => __( 'Background', 'woolementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wl-ss-product-cart i' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'cart_border',
				'label'         => __( 'Border', 'woolementor' ),
				'selector'      => '{{WRAPPER}} .wl-ss-product-cart i',
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'cart_hover',
            [
                'label'     => __( 'Hover', 'woolementor' ),
            ]
        );

		$this->add_control(
			'cart_icon_color_hover',
			[
				'label'     => __( 'Color', 'woolementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wl-ss-product-cart i:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'cart_icon_bg_hover',
			[
				'label'     => __( 'Background', 'woolementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wl-ss-product-cart i:hover' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'cart_border_hover',
				'label'         => __( 'Border', 'woolementor' ),
				'selector'      => '{{WRAPPER}} .wl-ss-product-cart i:hover',
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'cart_view_cart',
            [
                'label'     => __( 'View Cart', 'woolementor' ),
            ]
        );

        $this->add_control(
            'cart_icon_color_view_cart',
            [
                'label'     => __( 'Color', 'woolementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .added_to_cart.wc-forward::after' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'cart_icon_bg_view_cart',
            [
                'label'     => __( 'Background', 'woolementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .added_to_cart.wc-forward::after' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'          => 'cart_border_view_cart',
                'label'         => __( 'Border', 'woolementor' ),
                'selector'      => '{{WRAPPER}} .added_to_cart.wc-forward::after',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

		$this->add_responsive_control(
			'cart_area_size',
			[
				'label'     => __( 'Area Size', 'woolementor' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units'=> [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wl-ss-product-cart i' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .added_to_cart.wc-forward::after' 	=> 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'cart_border_radius',
			[
				'label'         => __( 'Border Radius', 'woolementor' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .wl-ss-product-cart i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wl-ss-product-cart .added_to_cart.wc-forward::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * Wishlist Button
		 */
		$this->start_controls_section(
			'section_style_wishlist',
			[
				'label' => __( 'Wishlist Button', 'woolementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' 	=> [
					'wishlist_show_hide' => 'yes'
				],
			]
		);

		$this->add_control(
            'wishlist_icon',
            [
                'label'         => __( 'Icon', 'woolementor' ),
                'type'          => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default'       => [
                    'value'     => 'eicon-heart',
                    'library'   => 'fa-solid',
                ],
                'recommended'   => [
                    'fa-regular' => [
                        'heart',
                    ],
                    'fa-solid'  => [
                        'heart',
                        'heart-broken',
                        'heartbeat',
                    ]
                ]
            ]
        );

		$this->add_responsive_control(
			'wishlist_icon_size',
			[
				'label'     => __( 'Icon Size', 'woolementor' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units'=> [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wl-ss-product-fav i' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->start_controls_tabs(
			'wishlist_style_tabs',
			[
				'separator'		=> 'before',
			]
		);

		$this->start_controls_tab(
			'regular_wishlist_color',
			[
				'label' => __( 'Regular', 'plugin-name' ),
			]
		);

		$this->add_control(
			'wishlist_icon_regular_color',
			[
				'label'     => __( 'Color', 'woolementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wl-ss-product-fav i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'wishlist_icon_regular_bg',
			[
				'label'     => __( 'Background', 'woolementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wl-ss-product-fav i' => 'background: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'active_wishlist_color',
			[
				'label' => __( 'Active', 'plugin-name' ),
			]
		);

		$this->add_control(
			'wishlist_icon_active_color',
			[
				'label'     => __( 'Color', 'woolementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wl-ss-product-fav .ajax_add_to_wish.fav-item' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'wishlist_icon_active_bg',
			[
				'label'     => __( 'Background', 'woolementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wl-ss-product-fav .ajax_add_to_wish.fav-item' => 'background: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'wishlist_area_size',
			[
				'label'     => __( 'Area Size', 'woolementor' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units'=> [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wl-ss-product-fav i' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
				'separator'		=> 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'wishlist_border',
				'label'         => __( 'Border', 'woolementor' ),
				'selector'      => '{{WRAPPER}} .wl-ss-product-fav i',
			]
		);

		$this->add_responsive_control(
			'wishlist_border_radius',
			[
				'label'         => __( 'Border Radius', 'woolementor' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .wl-ss-product-fav i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * Pagination
		 */
		$this->start_controls_section(
			'section_style_pagination',
			[
				'label' => __( 'Pagination', 'woolementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'pagination_show_hide' => 'yes',
                    'product_source' => 'shop'
                ],
			]
		);

		$this->add_control(
			'pagination_alignment',
			[
				'label' 	=> __( 'Alignment', 'woolementor' ),
				'type' 		=> Controls_Manager::CHOOSE,
				'options' 	=> [
					'left' 		=> [
						'title' 	=> __( 'Left', 'woolementor' ),
						'icon' 		=> 'eicon-text-align-left',
					],
					'center' 	=> [
						'title' 	=> __( 'Center', 'woolementor' ),
						'icon' 		=> 'eicon-text-align-center',
					],
					'right' 	=> [
						'title' 	=> __( 'Right', 'woolementor' ),
						'icon' 		=> 'eicon-text-align-right',
					],
				],
				'default' 	=> 'center',
				'toggle' 	=> true,
				'selectors' => [
					'{{WRAPPER}} .wl-ss-pagination' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
		    'pagination_gap',
		    [
		        'label'     => __( 'Gap with shop', 'woolementor' ),
		        'type'      => Controls_Manager::SLIDER,
		        'size_units'=> [ 'px', 'em' ],
		        'selectors' => [
		            '.wl {{WRAPPER}} .wl-ss-pagination' => 'margin-top: {{SIZE}}{{UNIT}}',
		        ],
		        'default' =>[
		            'unit' => 'px',
		            'size' => 20
		        ]
		    ]
		);

		$this->add_control(
            'pagination_left_icon',
            [
                'label'     => __( 'Left Icon', 'woolementor' ),
                'type'      => Controls_Manager::ICONS,
                'default'   => [
                    'value'     => 'eicon-chevron-left',
                    'library'   => 'solid',
                ],
				'separator' => 'before'
            ]
        );

		$this->add_control(
            'pagination_right_icon',
            [
                'label'     => __( 'Right Icon', 'woolementor' ),
                'type'      => Controls_Manager::ICONS,
                'default'   => [
                    'value'     => 'eicon-chevron-right',
                    'library'   => 'solid',
                ],
            ]
        );

		$this->add_responsive_control(
			'pagination_icon_size',
			[
				'label'     => __( 'Font Size', 'woolementor' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units'=> [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wl-ss-pagination .page-numbers' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
		    'pagination_area',
		    [
		        'label'     => __( 'Area Size', 'woolementor' ),
		        'type'      => Controls_Manager::SLIDER,
		        'size_units'=> [ 'px', 'em' ],
		        'selectors' => [
		            '.wl {{WRAPPER}} .wl-ss-pagination .page-numbers' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
		        ],
		    ]
		);

		$this->add_responsive_control(
		    'pagination_line_height',
		    [
		        'label'     => __( 'Line Height', 'woolementor' ),
		        'type'      => Controls_Manager::SLIDER,
		        'size_units'=> [ 'px', 'em' ],
		        'selectors' => [
		            '.wl {{WRAPPER}} .wl-ss-pagination .page-numbers' => 'line-height: {{SIZE}}{{UNIT}};',
		        ],
		    ]
		);

		$this->add_responsive_control(
			'pagination_item_margin',
			[
				'label'         => __( 'Margin', 'woolementor' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', 'em' ],
				'selectors'     => [
					'{{WRAPPER}} .wl-ss-pagination' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_item_padding',
			[
				'label'         => __( 'Padding', 'woolementor' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', 'em' ],
				'selectors'     => [
					'{{WRAPPER}} .wl-ss-pagination .page-numbers' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs(
			'pagination_separator',
			[
				'separator' => 'before'
			]
		);

		$this->start_controls_tab(
			'pagination_normal_item',
			[
				'label'     => __( 'Normal', 'woolementor' ),
			]
		);

		$this->add_control(
			'pagination_color',
			[
				'label'     => __( 'Color', 'woolementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wl-ss-pagination .page-numbers' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pagination_icon_bg',
			[
				'label'     => __( 'Background', 'woolementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wl-ss-pagination .page-numbers' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'pagination_border',
				'label'         => __( 'Border', 'woolementor' ),
				'selector'      => '{{WRAPPER}} .wl-ss-pagination .page-numbers',
			]
		);

		$this->add_responsive_control(
			'pagination_border_radius',
			[
				'label'         => __( 'Border Radius', 'woolementor' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .wl-ss-pagination .page-numbers' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'pagination_current_item',
			[
				'label'     => __( 'Active', 'woolementor' ),
			]
		);

		$this->add_control(
			'pagination_current_item_color',
			[
				'label'     => __( 'Color', 'woolementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wl-ss-pagination .page-numbers.current' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pagination_current_item_bg',
			[
				'label'     => __( 'Background', 'woolementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wl-ss-pagination .page-numbers.current' => 'background: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'pagination_current_item_border',
				'label'         => __( 'Border', 'woolementor' ),
				'selector'      => '{{WRAPPER}} .wl-ss-pagination .page-numbers.current',
			]
		);

		$this->add_responsive_control(
			'pagination_current_item_border_radius',
			[
				'label'         => __( 'Border Radius', 'woolementor' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .wl-ss-pagination .page-numbers.current' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'pagination_hover',
			[
				'label'     => __( 'Hover', 'woolementor' ),
			]
		);

		$this->add_control(
			'pagination_hover_item_color',
			[
				'label'     => __( 'Color', 'woolementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wl-ss-pagination .page-numbers:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pagination_hover_item_bg',
			[
				'label'     => __( 'Background', 'woolementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wl-ss-pagination .page-numbers:hover' => 'background: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'pagination_hover_item_border',
				'label'         => __( 'Border', 'woolementor' ),
				'selector'      => '{{WRAPPER}} .wl-ss-pagination .page-numbers:hover',
			]
		);

		$this->add_responsive_control(
			'pagination_hover_item_border_radius',
			[
				'label'         => __( 'Border Radius', 'woolementor' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .wl-ss-pagination .page-numbers:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'pagination_hover_transition',
			[
				'label' 	=> __( 'Transition Duration', 'woolementor' ),
				'type' 		=> Controls_Manager::SLIDER,
				'range' 	=> [
					'px' 	=> [
						'max' 	=> 3,
						'step' 	=> 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wl-ss-pagination .page-numbers:hover' => 'transition-duration: {{SIZE}}s',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		do_action( 'codesigner_after_shop_style_controls', $this );
	}

	protected function render() {
		$settings  = $this->get_settings_for_display();
        $widget_id = $this->id;
        extract( $settings );
        
        if ( !wcd_is_pro_activated() && !wcd_is_preview_mode() && !wcd_is_edit_mode() ) {
            $wishlist_show_hide = 'no';
        }

        $data = [];

        if ( wcd_is_pro_activated() ) {
        	$data = [
	            'sale_ribbon_text'              => $sale_ribbon_text,
	            'stock_ribbon_text'             => $stock_ribbon_text,
	            'woolementor_condition_list'    => $woolementor_condition_list,
	            'custom_query'                  => $custom_query,
	            'columns'                       => $columns,
	            'alignment'                     => $alignment,
	            'product_source'                => $product_source,
	            'short_description_show_hide'   => $short_description_show_hide,
	            'product_desc_words_count'   	=> $product_desc_words_count,
	            'content_source'                => $content_source,
	            'main_product_id'               => $main_product_id,
	            'product_limit'                 => $product_limit,
	            'ns_exclude_products'           => $ns_exclude_products,
	            'number'                        => $number,
	            'order'                         => $order,
	            'orderby'                       => $orderby,
	            'author'                        => $author,
	            'categories'                    => $categories,
	            'exclude_categories'            => $exclude_categories,
	            'include_products'              => $include_products,
	            'exclude_products'              => $exclude_products,
	            'sale_products_show_hide'       => $sale_products_show_hide,
	            'out_of_stock'                  => $out_of_stock,
	            'offset'                        => $offset,
	            'image_on_click'                => $image_on_click,
	            'sale_ribbon_show_hide'         => $sale_ribbon_show_hide,
	            'stock_show_hide'               => $stock_show_hide,
	            'cart_show_hide'                => $cart_show_hide,
	            'wishlist_show_hide'            => $wishlist_show_hide,
	            'pagination_show_hide'          => $pagination_show_hide,
	            'image_thumbnail_size'          => $image_thumbnail_size,
	            'quick_view_show_hide'          => $quick_view_show_hide,
	            'wishlist_icon'                 => $wishlist_icon,
	            'cart_icon'                     => $cart_icon,
	            'pagination_left_icon'          => $pagination_left_icon,
	            'pagination_right_icon'         => $pagination_right_icon,
	        ];
        }

        if ( !wcd_is_pro_activated() && !wcd_is_preview_mode() && !wcd_is_edit_mode() ) {
            $wishlist_show_hide = 'no';
        }

        do_action( 'codesigner_before_main_content' );

        echo '<div class="wl-shop wl-'. esc_attr( $widget_id ) .'" data-settings="'. esc_attr( serialize( $data ) ) .'">';
        echo Helper::get_template( 'template', "widgets/{$this->id}", [ 'widget_id' => $widget_id, 'settings' => $settings ] );
        echo '</div>';


        update_post_meta( get_the_ID(), 'codesigner_quick_checkout', 0 );
        if ( wcd_is_pro_activated() && 'yes' == $quick_checkout_show_hide ) {
            $config = [
                'active_mode' => $quick_checkout_modal_active_mode
            ];
            do_action( 'codesigner_quick_checkout', $config );

            update_post_meta( get_the_ID(), 'codesigner_quick_checkout', 1 );
        }
        
        do_action( 'codesigner_after_main_content' );
	}
}