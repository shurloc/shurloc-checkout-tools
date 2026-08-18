<?php
/**
 * Tests for Shurloc_Payment_Gateway_Labels.
 *
 * @package ShurlocCheckoutTools
 */

declare( strict_types=1 );

namespace Shurloc\CheckoutTools;

use PHPUnit\Framework\TestCase;

/**
 * Tests payment gateway label customization.
 */
final class ShurlocPaymentGatewayLabelsTest extends TestCase {

	/**
	 * Sets up each test.
	 */
	protected function setUp(): void {
		parent::setUp();

		$GLOBALS['shurloc_test_filters'] = array();
	}

	/**
	 * Cleans up after each test.
	 */
	protected function tearDown(): void {
		unset(
			$GLOBALS['shurloc_test_filters']
		);

		parent::tearDown();
	}

	/**
	 * Tests that the gateway title filter is registered.
	 */
	public function test_register_adds_gateway_title_filter(): void {
		$labels = new Shurloc_Payment_Gateway_Labels();

		$labels->register();

		$this->assertCount(
			1,
			$GLOBALS['shurloc_test_filters']
		);

		$this->assertSame(
			'woocommerce_gateway_title',
			$GLOBALS['shurloc_test_filters'][0]['hook']
		);

		$this->assertSame(
			array( $labels, 'filter_gateway_title' ),
			$GLOBALS['shurloc_test_filters'][0]['callback']
		);

		$this->assertSame(
			10,
			$GLOBALS['shurloc_test_filters'][0]['priority']
		);

		$this->assertSame(
			2,
			$GLOBALS['shurloc_test_filters'][0]['accepted_args']
		);
	}

	/**
	 * Tests that the PayPal gateway title is changed to PayPal/Venmo.
	 */
	public function test_paypal_gateway_title_is_changed(): void {
		$labels = new Shurloc_Payment_Gateway_Labels();

		$result = $labels->filter_gateway_title(
			'PayPal',
			'ppcp-gateway'
		);

		$this->assertSame(
			'PayPal/Venmo',
			$result
		);
	}

	/**
	 * Tests that unrelated gateway titles are unchanged.
	 */
	public function test_unrelated_gateway_title_is_unchanged(): void {
		$labels = new Shurloc_Payment_Gateway_Labels();

		$result = $labels->filter_gateway_title(
			'Direct bank transfer',
			'bacs'
		);

		$this->assertSame(
			'Direct bank transfer',
			$result
		);
	}
}
