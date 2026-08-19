<?php
/**
 * WooCommerce order test double.
 *
 * @package ShurlocCheckoutTools
 */

declare( strict_types=1 );

/**
 * Test double for WC_Order.
 */
class WC_Order extends WC_Abstract_Order {

	/**
	 * Payment method ID.
	 *
	 * @var string
	 */
	private string $payment_method = '';

	/**
	 * Sets the payment method ID.
	 *
	 * @param string $payment_method Payment method ID.
	 */
	public function set_payment_method(
		string $payment_method
	): void {
		$this->payment_method = $payment_method;
	}

	/**
	 * Gets the payment method ID.
	 */
	public function get_payment_method(): string {
		return $this->payment_method;
	}
}
