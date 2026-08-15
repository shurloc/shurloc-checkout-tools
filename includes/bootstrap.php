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
}

add_action(
	'plugins_loaded',
	__NAMESPACE__ . '\\shurloc_checkout_tools_bootstrap',
	20
);
