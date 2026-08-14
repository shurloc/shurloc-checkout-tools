<?php
/**
 * WooCommerce fee test double.
 *
 * @package ShurlocCheckoutTools
 */

declare( strict_types=1 );

namespace Shurloc\CheckoutTools;

/**
 * Test double for a WooCommerce cart fee.
 */
final class Shurloc_Test_Fee {

	/**
	 * Fee name.
	 *
	 * @var string
	 */
	public string $name;

	/**
	 * Fee amount.
	 *
	 * @var float
	 */
	public float $amount;

	/**
	 * Creates a test fee.
	 *
	 * @param string $name   Fee name.
	 * @param float  $amount Fee amount.
	 */
	public function __construct(
		string $name,
		float $amount
	) {
		$this->name   = $name;
		$this->amount = $amount;
	}
}
