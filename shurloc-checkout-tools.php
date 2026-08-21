<?php
/**
 * Plugin Name:       Shur-loc Checkout Tools
 * Plugin URI:        https://shurloc.com/
 * Description:       Checkout tools for the Shur-loc website.
 * Version:           0.4.2
 * Requires at least: 7.0
 * Requires PHP:      8.4
 * Requires Plugins:  woocommerce, shurloc-tools
 * Author:            Shur-loc
 * Author URI:        https://shurloc.com/
 * Text Domain:       shurloc-checkout-tools
 *
 * @package ShurlocCheckoutTools
 */

namespace Shurloc\CheckoutTools;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/includes/constants.php';
require_once __DIR__ . '/includes/bootstrap.php';
