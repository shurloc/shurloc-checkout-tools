<?php
/**
 * WooCommerce function test stubs.
 *
 * @package ShurLocCheckoutTools
 */

declare( strict_types=1 );


/**
 * Returns the test WooCommerce instance.
 *
 * @return \Shurloc\CheckoutTools\Shurloc_Test_WooCommerce
 */
// phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid, Squiz.Commenting.FunctionComment.Missing -- Matches WooCommerce's WC() function.
function WC(): \Shurloc\CheckoutTools\Shurloc_Test_WooCommerce {
	return $GLOBALS['shurloc_test_wc'];
}
