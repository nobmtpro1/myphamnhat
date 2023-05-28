<?php

namespace Elementor;

defined('ABSPATH') || exit;

class ShopEngine_Account_Details_Config extends \ShopEngine\Base\Widget_Config {

	public function get_name() {
		return 'account-details';
	}

	public function get_title() {
		return esc_html__('Account Details', 'shopengine-pro');
	}


	public function get_icon() {
		return 'shopengine-widget-icon shopengine-icon-account_form_register';
	}


	public function get_categories() {
		return ['shopengine-my_account'];
	}


	public function get_keywords() {
		return ['woocommerce', 'shopengine', 'my account', 'dashboard', 'account details'];
	}

	public function get_template_territory() {
		return ['account_edit_account', 'my_account', 'account_edit_address'];
	}
}
