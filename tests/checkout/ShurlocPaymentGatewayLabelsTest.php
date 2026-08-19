<?php
/**
 * Tests for Shurloc_Payment_Gateway_Labels.
 *
 * @package ShurlocCheckoutTools
 */

declare( strict_types=1 );

namespace Shurloc\CheckoutTools;

use PHPUnit\Framework\TestCase;
use WC_Email;
use WC_Order;

/**
 * Tests payment gateway label customization.
 */
final class ShurlocPaymentGatewayLabelsTest extends TestCase {

	/**
	 * Sets up each test.
	 */
	protected function setUp(): void {
		parent::setUp();

		$GLOBALS['shurloc_test_actions'] = array();
		$GLOBALS['shurloc_test_filters'] = array();
	}

	/**
	 * Cleans up after each test.
	 */
	protected function tearDown(): void {
		unset(
			$GLOBALS['shurloc_test_actions'],
			$GLOBALS['shurloc_test_filters']
		);

		parent::tearDown();
	}

	/**
	 * Tests that the gateway label hooks are registered.
	 */
	public function test_register_adds_gateway_label_hooks(): void {
		$labels = new Shurloc_Payment_Gateway_Labels();

		$labels->register();

		$this->assertCount(
			2,
			$GLOBALS['shurloc_test_filters']
		);

		$this->assertCount(
			2,
			$GLOBALS['shurloc_test_actions']
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

		$this->assertSame(
			'woocommerce_get_order_item_totals',
			$GLOBALS['shurloc_test_filters'][1]['hook']
		);

		$this->assertSame(
			array( $labels, 'filter_order_item_totals' ),
			$GLOBALS['shurloc_test_filters'][1]['callback']
		);

		$this->assertSame(
			10,
			$GLOBALS['shurloc_test_filters'][1]['priority']
		);

		$this->assertSame(
			3,
			$GLOBALS['shurloc_test_filters'][1]['accepted_args']
		);

		$this->assertSame(
			'woocommerce_email_before_order_table',
			$GLOBALS['shurloc_test_actions'][0]['hook']
		);

		$this->assertSame(
			array( $labels, 'begin_email_context' ),
			$GLOBALS['shurloc_test_actions'][0]['callback']
		);

		$this->assertSame(
			10,
			$GLOBALS['shurloc_test_actions'][0]['priority']
		);

		$this->assertSame(
			4,
			$GLOBALS['shurloc_test_actions'][0]['accepted_args']
		);

		$this->assertSame(
			'woocommerce_email_after_order_table',
			$GLOBALS['shurloc_test_actions'][1]['hook']
		);

		$this->assertSame(
			array( $labels, 'end_email_context' ),
			$GLOBALS['shurloc_test_actions'][1]['callback']
		);

		$this->assertSame(
			10,
			$GLOBALS['shurloc_test_actions'][1]['priority']
		);

		$this->assertSame(
			4,
			$GLOBALS['shurloc_test_actions'][1]['accepted_args']
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

	/**
	 * Tests the PayPal card label outside email context.
	 */
	public function test_paypal_card_label_is_unchanged_outside_email_context(): void {
		$labels = new Shurloc_Payment_Gateway_Labels();
		$order  = $this->create_order(
			'ppcp-card-button-gateway'
		);

		$totals = $this->get_payment_method_totals();

		$result = $labels->filter_order_item_totals(
			$totals,
			$order,
			'excl'
		);

		$this->assertSame(
			$totals,
			$result
		);
	}

	/**
	 * Tests the PayPal card label in the admin new order email.
	 */
	public function test_paypal_card_label_is_changed_in_admin_new_order_email(): void {
		$labels = new Shurloc_Payment_Gateway_Labels();
		$order  = $this->create_order(
			'ppcp-card-button-gateway'
		);
		$email  = $this->create_email(
			'new_order'
		);

		$labels->begin_email_context(
			$order,
			true,
			false,
			$email
		);

		$result = $labels->filter_order_item_totals(
			$this->get_payment_method_totals(),
			$order,
			'excl'
		);

		$this->assertSame(
			'Debit & Credit Cards (PayPal)',
			$result['payment_method']['value']
		);
	}

	/**
	 * Tests that the PayPal card label is unchanged in a customer email.
	 */
	public function test_paypal_card_label_is_unchanged_in_customer_email(): void {
		$labels = new Shurloc_Payment_Gateway_Labels();
		$order  = $this->create_order(
			'ppcp-card-button-gateway'
		);
		$email  = $this->create_email(
			'customer_processing_order'
		);

		$labels->begin_email_context(
			$order,
			false,
			false,
			$email
		);

		$totals = $this->get_payment_method_totals();

		$result = $labels->filter_order_item_totals(
			$totals,
			$order,
			'excl'
		);

		$this->assertSame(
			$totals,
			$result
		);
	}

	/**
	 * Tests that the PayPal card label is unchanged in another admin email.
	 */
	public function test_paypal_card_label_is_unchanged_in_other_admin_email(): void {
		$labels = new Shurloc_Payment_Gateway_Labels();
		$order  = $this->create_order(
			'ppcp-card-button-gateway'
		);
		$email  = $this->create_email(
			'cancelled_order'
		);

		$labels->begin_email_context(
			$order,
			true,
			false,
			$email
		);

		$totals = $this->get_payment_method_totals();

		$result = $labels->filter_order_item_totals(
			$totals,
			$order,
			'excl'
		);

		$this->assertSame(
			$totals,
			$result
		);
	}

	/**
	 * Tests that another gateway is unchanged in the admin new order email.
	 */
	public function test_unrelated_gateway_is_unchanged_in_admin_new_order_email(): void {
		$labels = new Shurloc_Payment_Gateway_Labels();
		$order  = $this->create_order(
			'bacs'
		);
		$email  = $this->create_email(
			'new_order'
		);

		$labels->begin_email_context(
			$order,
			true,
			false,
			$email
		);

		$totals = $this->get_payment_method_totals();

		$result = $labels->filter_order_item_totals(
			$totals,
			$order,
			'excl'
		);

		$this->assertSame(
			$totals,
			$result
		);
	}

	/**
	 * Tests totals without a payment method row.
	 */
	public function test_missing_payment_method_total_is_unchanged(): void {
		$labels = new Shurloc_Payment_Gateway_Labels();
		$order  = $this->create_order(
			'ppcp-card-button-gateway'
		);
		$email  = $this->create_email(
			'new_order'
		);

		$labels->begin_email_context(
			$order,
			true,
			false,
			$email
		);

		$totals = array(
			'order_total' => array(
				'label' => 'Total:',
				'value' => '$100.00',
			),
		);

		$result = $labels->filter_order_item_totals(
			$totals,
			$order,
			'excl'
		);

		$this->assertSame(
			$totals,
			$result
		);
	}

	/**
	 * Tests that ending email context prevents further label changes.
	 */
	public function test_end_email_context_disables_label_change(): void {
		$labels = new Shurloc_Payment_Gateway_Labels();
		$order  = $this->create_order(
			'ppcp-card-button-gateway'
		);
		$email  = $this->create_email(
			'new_order'
		);

		$labels->begin_email_context(
			$order,
			true,
			false,
			$email
		);

		$labels->end_email_context(
			$order,
			true,
			false,
			$email
		);

		$totals = $this->get_payment_method_totals();

		$result = $labels->filter_order_item_totals(
			$totals,
			$order,
			'excl'
		);

		$this->assertSame(
			$totals,
			$result
		);
	}

	/**
	 * Creates a test order.
	 *
	 * @param string $payment_method Payment method ID.
	 * @return WC_Order
	 */
	private function create_order(
		string $payment_method
	): WC_Order {
		$order = $this->createStub( WC_Order::class );

		$order
			->method( 'get_payment_method' )
			->willReturn( $payment_method );

		return $order;
	}

	/**
	 * Creates a test email.
	 *
	 * @param string $email_id Email ID.
	 * @return WC_Email
	 */
	private function create_email(
		string $email_id
	): WC_Email {
		$email = $this->createStub( WC_Email::class );

		$email->id = $email_id;

		return $email;
	}

	/**
	 * Gets test payment method totals.
	 *
	 * @return array<string, array<string, string>>
	 */
	private function get_payment_method_totals(): array {
		return array(
			'payment_method' => array(
				'label' => 'Payment method:',
				'value' => 'Debit & Credit Cards',
			),
		);
	}
}
