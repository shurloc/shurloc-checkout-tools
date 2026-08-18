<?php
/**
 * Payment gateway labels.
 *
 * @package ShurlocCheckoutTools
 */

declare( strict_types=1 );

namespace Shurloc\CheckoutTools;

/**
 * Customizes WooCommerce payment gateway labels.
 */
final class Shurloc_Payment_Gateway_Labels {

	/**
	 * PayPal gateway ID.
	 */
	private const PAYPAL_GATEWAY_ID = 'ppcp-gateway';

	/**
	 * PayPal checkout label.
	 */
	private const PAYPAL_CHECKOUT_LABEL = 'PayPal/Venmo';

	/**
	 * Registers WooCommerce hooks.
	 */
	public function register(): void {
		add_filter(
			'woocommerce_gateway_title',
			array( $this, 'filter_gateway_title' ),
			10,
			2
		);
	}

	/**
	 * Customizes the payment gateway title.
	 *
	 * @param string $title      Payment gateway title.
	 * @param string $gateway_id Payment gateway ID.
	 * @return string
	 */
	public function filter_gateway_title(
		string $title,
		string $gateway_id
	): string {
		if ( self::PAYPAL_GATEWAY_ID !== $gateway_id ) {
			return $title;
		}

		return self::PAYPAL_CHECKOUT_LABEL;
	}
}
