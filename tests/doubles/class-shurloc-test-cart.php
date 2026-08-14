<?php
/**
 * WooCommerce cart test double.
 *
 * @package ShurLocCheckoutTools
 */

declare( strict_types=1 );

namespace Shurloc\CheckoutTools;

/**
 * Test WooCommerce cart.
 */
final class Shurloc_Test_Cart {

	/**
	 * Cart contents.
	 *
	 * @var array<string, array<string, mixed>>
	 */
	private array $cart = array();

	/**
	 * Added fees.
	 *
	 * @var array<int, array{name: string, amount: float}>
	 */
	private array $fees = array();

	/**
	 * Sets the cart contents.
	 *
	 * @param array<string, array<string, mixed>> $cart Cart contents.
	 */
	public function set_cart( array $cart ): void {
		$this->cart = $cart;
	}

	/**
	 * Gets the cart contents.
	 *
	 * @return array<string, array<string, mixed>>
	 */
	public function get_cart(): array {
		return $this->cart;
	}

	/**
	 * Adds a fee.
	 *
	 * @param string $name   Fee name.
	 * @param float  $amount Fee amount.
	 */
	public function add_fee(
		string $name,
		float $amount
	): void {
		$this->fees[] = array(
			'name'   => $name,
			'amount' => $amount,
		);
	}

	/**
	 * Gets fees added to the cart.
	 *
	 * @return array<int, array{name: string, amount: float}>
	 */
	public function get_added_fees(): array {
		return $this->fees;
	}
}
