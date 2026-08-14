<?php
/**
 * Plugin bootstrap.
 *
 * @package ShurlocCcheckoutTools
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
}

add_action(
	'plugins_loaded',
	__NAMESPACE__ . '\\shurloc_checkout_tools_bootstrap',
	20
);
