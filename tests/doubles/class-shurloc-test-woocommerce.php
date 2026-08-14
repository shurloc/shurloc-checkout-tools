<?php
/**
 * WooCommerce test double.
 *
 * @package ShurlocCheckoutTools
 */

declare( strict_types=1 );

namespace Shurloc\CheckoutTools;

/**
 * Test WooCommerce instance.
 */
final class Shurloc_Test_WooCommerce {

	/**
	 * Test cart.
	 *
	 * @var Shurloc_Test_Cart
	 */
	public Shurloc_Test_Cart $cart;

	/**
	 * Creates the test WooCommerce instance.
	 */
	public function __construct() {
		$this->cart = new Shurloc_Test_Cart();
	}
}
