<?php
/**
 * WooCommerce function test stubs.
 *
 * @package ShurlocCheckoutTools
 */

declare( strict_types=1 );

if ( ! function_exists( 'WC' ) ) {

	/**
	 * Returns the test WooCommerce instance.
	 *
	 * @return \Shurloc\CheckoutTools\Shurloc_Test_WooCommerce
	 */
	// phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid, Squiz.Commenting.FunctionComment.Missing -- Matches WooCommerce's WC() function.
	function WC(): \Shurloc\CheckoutTools\Shurloc_Test_WooCommerce {
		return $GLOBALS['shurloc_test_wc'];
	}
}

if ( ! function_exists( 'is_cart' ) ) {

	/**
	 * Determines whether the current request is the cart page.
	 */
	function is_cart(): bool {
		return $GLOBALS['shurloc_test_is_cart'] ?? false;
	}
}

if ( ! function_exists( 'is_checkout' ) ) {

	/**
	 * Determines whether the current request is the checkout page.
	 */
	function is_checkout(): bool {
		return $GLOBALS['shurloc_test_is_checkout'] ?? false;
	}
}
