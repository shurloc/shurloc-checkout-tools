<?php
/**
 * Plugin bootstrap.
 *
 * @package ShurlocCheckoutTools
 */

declare( strict_types=1 );

namespace Shurloc\CheckoutTools;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Bootstrap the plugin.
 */
function shurloc_checkout_tools_bootstrap(): void {
	/**
	 * Autoloader.
	 */

	require_once SHURLOC_CHECKOUT_TOOLS_PATH . 'includes/class-shurloc-autoloader.php';

	$autoloader = new Shurloc_Autoloader(
		__DIR__
	);

	$autoloader->register();

	/**
	 * Settings.
	 */

	$settings = new Shurloc_Settings();

	if ( interface_exists( 'Shurloc_Admin_Page_Interface' ) ) {
		$settings_page = new Shurloc_Settings_Page(
			settings: $settings,
		);
		$settings_page->register();

		$admin_page = new Shurloc_Admin_Page_Controller(
			settings_page: $settings_page,
		);

		$admin_menu = new Shurloc_Admin_Menu(
			checkout_page: $admin_page,
		);
		$admin_menu->register();
	}

	/**
	 * Tariff fees.
	 */

	$tariff_fees = new Shurloc_Tariff_Fees(
		settings: $settings,
	);
	$tariff_fees->register();

	$tariff_tooltips = new Shurloc_Tariff_Tooltips(
		settings: $settings,
	);
	$tariff_tooltips->register();

	/**
	 * Payment processing fee.
	 */

	$payment_processing_fee = new Shurloc_Payment_Processing_Fee();
	$payment_processing_fee->register();

	/**
	 * Payment labels.
	 */

	$payment_labels = new Shurloc_Payment_Gateway_Labels();
	$payment_labels->register();

	/**
	 * Send new orders directly to Processing instead of On Hold.
	 */

	$offline_payment_status = new Shurloc_Offline_Payment_Status();
	$offline_payment_status->register();
}

add_action(
	'plugins_loaded',
	__NAMESPACE__ . '\\shurloc_checkout_tools_bootstrap',
	20
);
