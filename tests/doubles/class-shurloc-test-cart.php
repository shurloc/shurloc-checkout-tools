<?php
/**
 * WooCommerce cart test double.
 *
 * @package ShurlocCheckoutTools
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
	 * @var array<int, array{name: string, amount: float, taxable: bool}>
	 */
	private array $fees = array();

	/**
	 * Existing fees.
	 *
	 * @var Shurloc_Test_Fee[]
	 */
	private array $existing_fees = array();

	/**
	 * Cart contents total.
	 *
	 * @var float
	 */
	private float $cart_contents_total = 0.0;

	/**
	 * Shipping total.
	 *
	 * @var float
	 */
	private float $shipping_total = 0.0;

	/**
	 * Cart taxes.
	 *
	 * @var array<string, float>
	 */
	private array $taxes = array();

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
	 * Sets the cart contents total.
	 *
	 * @param float $total Cart contents total.
	 */
	public function set_cart_contents_total( float $total ): void {
		$this->cart_contents_total = $total;
	}

	/**
	 * Gets the cart contents total.
	 */
	public function get_cart_contents_total(): float {
		return $this->cart_contents_total;
	}

	/**
	 * Sets the shipping total.
	 *
	 * @param float $total Shipping total.
	 */
	public function set_shipping_total( float $total ): void {
		$this->shipping_total = $total;
	}

	/**
	 * Gets the shipping total.
	 */
	public function get_shipping_total(): float {
		return $this->shipping_total;
	}

	/**
	 * Sets existing cart fees.
	 *
	 * @param Shurloc_Test_Fee[] $fees Existing fees.
	 */
	public function set_existing_fees( array $fees ): void {
		$this->existing_fees = $fees;
	}

	/**
	 * Gets existing cart fees.
	 *
	 * @return Shurloc_Test_Fee[]
	 */
	public function get_fees(): array {
		return $this->existing_fees;
	}

	/**
	 * Sets cart taxes.
	 *
	 * @param array<string, float> $taxes Cart taxes.
	 */
	public function set_taxes( array $taxes ): void {
		$this->taxes = $taxes;
	}

	/**
	 * Gets cart taxes.
	 *
	 * @return array<string, float>
	 */
	public function get_taxes(): array {
		return $this->taxes;
	}

	/**
	 * Adds a fee.
	 *
	 * @param string $name    Fee name.
	 * @param float  $amount  Fee amount.
	 * @param bool   $taxable Whether the fee is taxable.
	 */
	public function add_fee(
		string $name,
		float $amount,
		bool $taxable = false
	): void {
		$this->fees[] = array(
			'name'    => $name,
			'amount'  => $amount,
			'taxable' => $taxable,
		);
	}

	/**
	 * Gets fees added to the cart.
	 *
	 * @return array<int, array{name: string, amount: float, taxable: bool}>
	 */
	public function get_added_fees(): array {
		return $this->fees;
	}
}
