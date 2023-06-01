<?php
/**
 * All public facing functions
 */
namespace Codexpert\Woolementor;
use Codexpert\Plugin\Base;
use Codexpert\Woolementor\Helper;

/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @package Plugin
 * @subpackage Front
 * @author Codexpert <hi@codexpert.io>
 */
class Front extends Base {

	public $plugin;

	/**
	 * Constructor function
	 */
	public function __construct( $plugin ) {
		$this->plugin	= $plugin;
		$this->slug		= $this->plugin['TextDomain'];
		$this->name		= $this->plugin['Name'];
		$this->version	= $this->plugin['Version'];
		$this->assets 	= WOOLEMENTOR_ASSETS;
	}

	public function add_admin_bar( $admin_bar ) {
		if( !current_user_can( 'manage_options' ) ) return;

		$admin_bar->add_menu( [
			'id'    => $this->slug,
			'title' => $this->name,
			'href'  => add_query_arg( 'page', $this->slug, admin_url( 'admin.php' ) ),
			'meta'  => [
				'title' => $this->name,            
			],
		] );
	}
	/**
	 * Enqueue JavaScripts and stylesheets
	 */
	public function enqueue_scripts() {
		// Are we in debug mode?
		$min = defined( 'WOOLEMENTOR_DEBUG' ) && WOOLEMENTOR_DEBUG ? '' : '.min';

		// slick
		wp_register_style(  'slick', "{$this->assets}/third-party/slick/slick.css", '', '1.8.1', 'all' );
		wp_register_script( 'slick', "{$this->assets}/third-party/slick/slick.min.js", [ 'jquery' ], '1.8.1', true );

		//box-slider
		wp_register_script( 'modernizr', "{$this->assets}/third-party/box-slider/modernizr.min.js", [ 'jquery' ], $this->version, true );
		wp_register_script( 'box-slider', "{$this->assets}/third-party/box-slider/box-slider-all.jquery.min.js", [ 'jquery' ], $this->version, true );

		//lc_lightbox
		wp_register_style(  'lc_lightbox', "{$this->assets}/third-party/lc_lightbox/lc_lightbox.min.css", '', $this->version, 'all' );
		wp_register_style( 'minimal', "{$this->assets}/third-party/lc_lightbox/minimal.min.css", '', $this->version, 'all' );
		wp_register_script( 'lc_lightbox', "{$this->assets}/third-party/lc_lightbox/lc_lightbox.lite.min.js", [ 'jquery' ], $this->version, true );

		// fancybox
		wp_register_style(  'fancybox', "{$this->assets}/third-party/fancybox/jquery.fancybox.min.css", '', '3.5.7', 'all' );
		wp_register_script( 'fancybox', "{$this->assets}/third-party/fancybox/jquery.fancybox.min.js", [ 'jquery' ], '3.5.7', true );

		// DataTables
		wp_register_style(  'dataTables', "{$this->assets}/third-party/dataTables/jquery.dataTables.min.css", '', '1.10.20', 'all' );
		wp_register_script( 'dataTables', "{$this->assets}/third-party/dataTables/jquery.dataTables.min.js", [ 'jquery' ], '1.10.20', true );

		// chosen
		wp_register_style(  'chosen', "{$this->assets}/third-party/chosen/chosen.min.css", '', '1.8.7', 'all' );
		wp_register_script( 'chosen', "{$this->assets}/third-party/chosen/chosen.jquery.min.js", [ 'jquery' ], '1.8.7', true );

		// enqueue stylesheet
		wp_enqueue_style( $this->slug, "{$this->assets}/css/front{$min}.css", '', $this->version, 'all' );
		wp_enqueue_style( "{$this->slug}-grid-system", "{$this->assets}/css/cx-grid{$min}.css", '', $this->version, 'all' );

		// enqueue JavaScript
		wp_enqueue_script( $this->slug, "{$this->assets}/js/front{$min}.js", [ 'jquery' ], $this->version, true );
		
		// define localized scripts
		$localized = array(
			'ajaxurl'		=> admin_url( 'admin-ajax.php' ),
			'_nonce'		=> wp_create_nonce( $this->slug ),
			'widgets'		=> wcd_active_widgets(),
			'min_price'		=> floor( $min_price = wcd_price_limit( 'min' ) ),
			'max_price'		=> ceil( $max_price = wcd_price_limit( 'max' ) ),
			'cart_url'		=> function_exists( 'WC' ) ? wc_get_cart_url() : '',
			'crnt_min'		=> isset( $_GET['filter']['min_price'] ) ? sanitize_text_field( $_GET['filter']['min_price'] ) : floor( $min_price ),
			'crnt_max'		=> isset( $_GET['filter']['max_price'] ) ? sanitize_text_field( $_GET['filter']['max_price'] ) : ceil( $max_price ),
			'pro_installed'	=> wcd_is_pro(),
			'pro_activated'	=> wcd_is_pro_activated(),
		);

		wp_localize_script( $this->slug, 'WOOLEMENTOR', apply_filters( "{$this->slug}-localized", $localized ) );
	}

	/**
	 * Add some script to head
	 *
	 * @since 1.0
	 */
	public function head() {}

	/**
	 * Add some script to load
	 *
	 * @since 1.0
	 */
	public function load() {
		$enable = Helper::get_option( 'woolementor_tools', 'recently_viewed_products' );

		if ( ! function_exists( 'is_product' ) || ! is_product() || empty( $enable ) ) return;

		global $post;

		$product_id = $post->ID;

		if ( is_user_logged_in() ) {
			$user_id 		= get_current_user_id();
			$product_ids 	= get_user_meta( $user_id, 'recently_viewed_products', true ) ? : [];

			$product_ids[] 	= $product_id;
			update_user_meta( $user_id, 'recently_viewed_products', $this->sanitize( array_unique( $product_ids ), 'array' ) );

		}
		else {
			$_product_ids 	= isset( $_COOKIE['recently_viewed_products'] ) ? $_COOKIE['recently_viewed_products'] : '';

			$product_ids 	= unserialize( stripslashes( $_product_ids ) );
   			$product_ids[] 	= $product_id;

			setcookie( 'recently_viewed_products', serialize( $this->sanitize( array_unique( $product_ids ), 'array' ) ), time() + 3600, COOKIEPATH, COOKIE_DOMAIN );
		}

	}

	/**
	 * Setup the instance
	 *
	 * @since 1.0
	 */
	public function setup() {
		add_image_size( 'woolementor-thumb', 400, 400, true );
	}

	/**
	 * Adds a class to the <body> tag
	 *
	 * @since 1.0
	 */
	public function body_class( $class ) {
		$class[] = $this->slug;
		$class[] = 'wl';

		if ( wp_is_mobile() ) {
			$class[] = 'wl-mobile';
		}

		if ( !in_array( 'woocommerce', $class ) ) {
			$class[] = 'woocommerce'; 
		}

		return $class;
	}

	/**
	 * Re-generate checkout fields
	 *
	 * @since 1.0
	 */
	public function regenerate_fields( $fields ) {

		if( ! wcd_is_pro() ) return $fields;

		/**
		 * If it contains the WooCommerce shortcode, exit
		 * 
		 * @since 3.0.0
		 */
		global $post;
		if( has_shortcode( $post->post_content, 'woocommerce_checkout' ) ) return $fields;

		/**
		 * @since 1.3.2
		 */
		$_checkout_fields = get_option( '_wcd_checkout_fields', [] );

		foreach ( $_checkout_fields as $section => $_fields ) {
			if( count( $_fields ) > 0 ) {
				foreach ( $_fields as $_field ) {
					$fields[ $section ][ $_field["{$section}_input_name"] ] = [
						'label'			=> $_field["{$section}_input_label"],
						'required'		=> $_field["{$section}_input_required"],
						'class'			=> $_field["{$section}_input_class"],
						'autocomplete'	=> $_field["{$section}_input_autocomplete"],
						'type'			=> $_field["{$section}_input_type"],
					];
				}
			}
		}

		return $fields;
	}

	public function add_to_cart_text( $default_text ) {
		global $product;

		$text = Helper::get_option( 'woolementor_tools', 'add-to-cart-text' );
		if ( $text == '' ) return $default_text;
		return $text;
	}

	public function redirect_to_checkout( $url ) {
		$enable = Helper::get_option( 'woolementor_tools', 'redirect_to_checkout' );
		if ( $enable != 'on' ) return $url;

		return wc_get_checkout_url();
	}
}