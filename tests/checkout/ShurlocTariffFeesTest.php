<?php
/**
 * Tests for Shurloc_Tariff_Fees.
 *
 * @package ShurlocCheckoutTools
 */

declare( strict_types=1 );

namespace Shurloc\CheckoutTools;

use PHPUnit\Framework\TestCase;

/**
 * Tests tariff fee calculation.
 */
final class ShurlocTariffFeesTest extends TestCase {

	/**
	 * Test WooCommerce instance.
	 *
	 * @var Shurloc_Test_WooCommerce
	 */
	private Shurloc_Test_WooCommerce $woocommerce;

	/**
	 * Sets up each test.
	 */
	protected function setUp(): void {
		parent::setUp();

		$this->woocommerce = new Shurloc_Test_WooCommerce();

		$GLOBALS['shurloc_test_wc']       = $this->woocommerce;
		$GLOBALS['shurloc_test_is_admin'] = false;
		$GLOBALS['shurloc_test_terms']    = array();
		$GLOBALS['shurloc_test_actions']  = array();
	}

	/**
	 * Cleans up after each test.
	 */
	protected function tearDown(): void {
		unset(
			$GLOBALS['shurloc_test_wc'],
			$GLOBALS['shurloc_test_is_admin'],
			$GLOBALS['shurloc_test_terms'],
			$GLOBALS['shurloc_test_actions']
		);

		parent::tearDown();
	}

	/**
	 * Tests that the WooCommerce hook is registered.
	 */
	public function test_register_adds_cart_calculate_fees_action(): void {
		$tariff_fees = new Shurloc_Tariff_Fees();

		$tariff_fees->register();

		$this->assertCount(
			1,
			$GLOBALS['shurloc_test_actions']
		);

		$this->assertSame(
			'woocommerce_cart_calculate_fees',
			$GLOBALS['shurloc_test_actions'][0]['hook']
		);

		$this->assertSame(
			array( $tariff_fees, 'add_tariff_fees' ),
			$GLOBALS['shurloc_test_actions'][0]['callback']
		);
	}

	/**
	 * Tests that an empty cart does not receive tariff fees.
	 */
	public function test_empty_cart_adds_no_fees(): void {
		$tariff_fees = new Shurloc_Tariff_Fees();

		$tariff_fees->add_tariff_fees();

		$this->assertSame(
			array(),
			$this->woocommerce->cart->get_added_fees()
		);
	}

	/**
	 * Tests that a non-mesh product does not receive a tariff fee.
	 */
	public function test_non_mesh_product_adds_no_fee(): void {
		$this->woocommerce->cart->set_cart(
			array(
				'item-1' => array(
					'product_id' => 101,
					'line_total' => 100.00,
				),
			)
		);

		$tariff_fees = new Shurloc_Tariff_Fees();

		$tariff_fees->add_tariff_fees();

		$this->assertSame(
			array(),
			$this->woocommerce->cart->get_added_fees()
		);
	}

	/**
	 * Tests the regular mesh tariff.
	 */
	public function test_mesh_product_adds_three_percent_tariff(): void {
		$this->woocommerce->cart->set_cart(
			array(
				'item-1' => array(
					'product_id' => 101,
					'line_total' => 100.00,
				),
			)
		);

		$this->add_product_term(
			101,
			'product_cat',
			'shurloc-mesh'
		);

		$tariff_fees = new Shurloc_Tariff_Fees();

		$tariff_fees->add_tariff_fees();

		$this->assertSame(
			array(
				array(
					'name'    => 'Raw material import tariff',
					'amount'  => 3.00,
					'taxable' => false,
				),
			),
			$this->woocommerce->cart->get_added_fees()
		);
	}

	/**
	 * Tests the Sefar mesh tariff.
	 */
	public function test_sefar_product_adds_nine_percent_tariff(): void {
		$this->woocommerce->cart->set_cart(
			array(
				'item-1' => array(
					'product_id' => 101,
					'line_total' => 100.00,
				),
			)
		);

		$this->add_product_term(
			101,
			'product_tag',
			'sefar'
		);

		$tariff_fees = new Shurloc_Tariff_Fees();

		$tariff_fees->add_tariff_fees();

		$this->assertSame(
			array(
				array(
					'name'    => 'Sefar Mesh Tariff',
					'amount'  => 9.00,
					'taxable' => false,
				),
			),
			$this->woocommerce->cart->get_added_fees()
		);
	}

	/**
	 * Tests that the Sefar tariff takes precedence over the mesh tariff.
	 */
	public function test_sefar_product_in_mesh_category_receives_only_sefar_tariff(): void {
		$this->woocommerce->cart->set_cart(
			array(
				'item-1' => array(
					'product_id' => 101,
					'line_total' => 100.00,
				),
			)
		);

		$this->add_product_term(
			101,
			'product_cat',
			'shurloc-mesh'
		);

		$this->add_product_term(
			101,
			'product_tag',
			'sefar'
		);

		$tariff_fees = new Shurloc_Tariff_Fees();

		$tariff_fees->add_tariff_fees();

		$this->assertSame(
			array(
				array(
					'name'    => 'Sefar Mesh Tariff',
					'amount'  => 9.00,
					'taxable' => false,
				),
			),
			$this->woocommerce->cart->get_added_fees()
		);
	}

	/**
	 * Tests that multiple mesh products are combined before calculating the tariff.
	 */
	public function test_multiple_mesh_products_are_combined(): void {
		$this->woocommerce->cart->set_cart(
			array(
				'item-1' => array(
					'product_id' => 101,
					'line_total' => 100.00,
				),
				'item-2' => array(
					'product_id' => 102,
					'line_total' => 50.00,
				),
			)
		);

		$this->add_product_term(
			101,
			'product_cat',
			'shurloc-mesh'
		);

		$this->add_product_term(
			102,
			'product_cat',
			'shurloc-mesh'
		);

		$tariff_fees = new Shurloc_Tariff_Fees();

		$tariff_fees->add_tariff_fees();

		$this->assertSame(
			array(
				array(
					'name'    => 'Raw material import tariff',
					'amount'  => 4.50,
					'taxable' => false,
				),
			),
			$this->woocommerce->cart->get_added_fees()
		);
	}

	/**
	 * Tests a cart containing both regular mesh and Sefar products.
	 */
	public function test_mixed_mesh_and_sefar_cart_adds_both_tariffs(): void {
		$this->woocommerce->cart->set_cart(
			array(
				'item-1' => array(
					'product_id' => 101,
					'line_total' => 100.00,
				),
				'item-2' => array(
					'product_id' => 102,
					'line_total' => 50.00,
				),
				'item-3' => array(
					'product_id' => 103,
					'line_total' => 200.00,
				),
			)
		);

		$this->add_product_term(
			101,
			'product_cat',
			'shurloc-mesh'
		);

		$this->add_product_term(
			102,
			'product_cat',
			'shurloc-mesh'
		);

		$this->add_product_term(
			103,
			'product_cat',
			'shurloc-mesh'
		);

		$this->add_product_term(
			103,
			'product_tag',
			'sefar'
		);

		$tariff_fees = new Shurloc_Tariff_Fees();

		$tariff_fees->add_tariff_fees();

		$this->assertSame(
			array(
				array(
					'name'    => 'Raw material import tariff',
					'amount'  => 4.50,
					'taxable' => false,
				),
				array(
					'name'    => 'Sefar Mesh Tariff',
					'amount'  => 18.00,
					'taxable' => false,
				),
			),
			$this->woocommerce->cart->get_added_fees()
		);
	}

	/**
	 * Tests that the cart line total is used for tariff calculation.
	 */
	public function test_tariff_uses_discounted_line_total(): void {
		$this->woocommerce->cart->set_cart(
			array(
				'item-1' => array(
					'product_id' => 101,
					'line_total' => 80.00,
				),
			)
		);

		$this->add_product_term(
			101,
			'product_cat',
			'shurloc-mesh'
		);

		$tariff_fees = new Shurloc_Tariff_Fees();

		$tariff_fees->add_tariff_fees();

		$this->assertSame(
			array(
				array(
					'name'    => 'Raw material import tariff',
					'amount'  => 2.40,
					'taxable' => false,
				),
			),
			$this->woocommerce->cart->get_added_fees()
		);
	}

	/**
	 * Tests that tariff calculation does not run on normal admin requests.
	 */
	public function test_admin_request_adds_no_fees(): void {
		$GLOBALS['shurloc_test_is_admin'] = true;

		$this->woocommerce->cart->set_cart(
			array(
				'item-1' => array(
					'product_id' => 101,
					'line_total' => 100.00,
				),
			)
		);

		$this->add_product_term(
			101,
			'product_cat',
			'shurloc-mesh'
		);

		$tariff_fees = new Shurloc_Tariff_Fees();

		$tariff_fees->add_tariff_fees();

		$this->assertSame(
			array(),
			$this->woocommerce->cart->get_added_fees()
		);
	}

	/**
	 * Adds a taxonomy term to the test term registry.
	 *
	 * @param int    $product_id Product ID.
	 * @param string $taxonomy   Taxonomy name.
	 * @param string $term       Term slug.
	 */
	private function add_product_term(
		int $product_id,
		string $taxonomy,
		string $term
	): void {
		$GLOBALS['shurloc_test_terms'][ $product_id ][ $taxonomy ][] = $term;
	}
}
