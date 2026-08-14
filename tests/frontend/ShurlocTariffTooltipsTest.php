<?php
/**
 * Tests for Shurloc_Tariff_Tooltips.
 *
 * @package ShurlocCheckoutTools
 */

declare( strict_types=1 );

namespace Shurloc\CheckoutTools;

use PHPUnit\Framework\TestCase;

/**
 * Tests tariff tooltip frontend assets.
 */
final class ShurlocTariffTooltipsTest extends TestCase {

	/**
	 * Sets up each test.
	 */
	protected function setUp(): void {
		parent::setUp();

		$GLOBALS['shurloc_test_actions']           = array();
		$GLOBALS['shurloc_test_enqueued_scripts']  = array();
		$GLOBALS['shurloc_test_enqueued_styles']   = array();
		$GLOBALS['shurloc_test_localized_scripts'] = array();
		$GLOBALS['shurloc_test_is_cart']           = false;
		$GLOBALS['shurloc_test_is_checkout']       = false;
	}

	/**
	 * Cleans up after each test.
	 */
	protected function tearDown(): void {
		unset(
			$GLOBALS['shurloc_test_actions'],
			$GLOBALS['shurloc_test_enqueued_scripts'],
			$GLOBALS['shurloc_test_enqueued_styles'],
			$GLOBALS['shurloc_test_localized_scripts'],
			$GLOBALS['shurloc_test_is_cart'],
			$GLOBALS['shurloc_test_is_checkout']
		);

		parent::tearDown();
	}

	/**
	 * Tests that the frontend asset hook is registered.
	 */
	public function test_register_adds_enqueue_scripts_action(): void {
		$tooltips = new Shurloc_Tariff_Tooltips();

		$tooltips->register();

		$this->assertCount(
			1,
			$GLOBALS['shurloc_test_actions']
		);

		$this->assertSame(
			'wp_enqueue_scripts',
			$GLOBALS['shurloc_test_actions'][0]['hook']
		);

		$this->assertSame(
			array( $tooltips, 'enqueue_assets' ),
			$GLOBALS['shurloc_test_actions'][0]['callback']
		);
	}

	/**
	 * Tests that assets are not enqueued outside the cart and checkout.
	 */
	public function test_assets_are_not_enqueued_on_other_pages(): void {
		$tooltips = new Shurloc_Tariff_Tooltips();

		$tooltips->enqueue_assets();

		$this->assertSame(
			array(),
			$GLOBALS['shurloc_test_enqueued_styles']
		);

		$this->assertSame(
			array(),
			$GLOBALS['shurloc_test_enqueued_scripts']
		);

		$this->assertSame(
			array(),
			$GLOBALS['shurloc_test_localized_scripts']
		);
	}

	/**
	 * Tests that assets are enqueued on the cart page.
	 */
	public function test_assets_are_enqueued_on_cart_page(): void {
		$GLOBALS['shurloc_test_is_cart'] = true;

		$tooltips = new Shurloc_Tariff_Tooltips();

		$tooltips->enqueue_assets();

		$this->assertAssetsEnqueued();
	}

	/**
	 * Tests that assets are enqueued on the checkout page.
	 */
	public function test_assets_are_enqueued_on_checkout_page(): void {
		$GLOBALS['shurloc_test_is_checkout'] = true;

		$tooltips = new Shurloc_Tariff_Tooltips();

		$tooltips->enqueue_assets();

		$this->assertAssetsEnqueued();
	}

	/**
	 * Tests the tariff tooltip configuration passed to JavaScript.
	 */
	public function test_tariff_configuration_is_localized(): void {
		$GLOBALS['shurloc_test_is_cart'] = true;

		$tooltips = new Shurloc_Tariff_Tooltips();

		$tooltips->enqueue_assets();

		$this->assertCount(
			1,
			$GLOBALS['shurloc_test_localized_scripts']
		);

		$localized = $GLOBALS['shurloc_test_localized_scripts'][0];

		$this->assertSame(
			'shurloc-tariff-tooltips',
			$localized['handle']
		);

		$this->assertSame(
			'shurlocTariffTooltips',
			$localized['object_name']
		);

		$this->assertSame(
			'Raw material import tariff',
			$localized['data']['fees'][0]['label']
		);

		$this->assertStringContainsString(
			'3% tariff fee',
			$localized['data']['fees'][0]['message']
		);

		$this->assertSame(
			'Sefar Mesh Tariff',
			$localized['data']['fees'][1]['label']
		);

		$this->assertStringContainsString(
			'9% tariff fee',
			$localized['data']['fees'][1]['message']
		);
	}

	/**
	 * Asserts that the tariff tooltip assets were enqueued.
	 */
	private function assertAssetsEnqueued(): void {
		$this->assertCount(
			1,
			$GLOBALS['shurloc_test_enqueued_styles']
		);

		$this->assertSame(
			'shurloc-tariff-tooltips',
			$GLOBALS['shurloc_test_enqueued_styles'][0]['handle']
		);

		$this->assertSame(
			SHURLOC_CHECKOUT_TOOLS_URL . 'assets/css/tariff-tooltips.css',
			$GLOBALS['shurloc_test_enqueued_styles'][0]['src']
		);

		$this->assertSame(
			SHURLOC_CHECKOUT_TOOLS_VERSION,
			$GLOBALS['shurloc_test_enqueued_styles'][0]['version']
		);

		$this->assertCount(
			1,
			$GLOBALS['shurloc_test_enqueued_scripts']
		);

		$this->assertSame(
			'shurloc-tariff-tooltips',
			$GLOBALS['shurloc_test_enqueued_scripts'][0]['handle']
		);

		$this->assertSame(
			SHURLOC_CHECKOUT_TOOLS_URL . 'assets/js/tariff-tooltips.js',
			$GLOBALS['shurloc_test_enqueued_scripts'][0]['src']
		);

		$this->assertSame(
			array( 'jquery' ),
			$GLOBALS['shurloc_test_enqueued_scripts'][0]['dependencies']
		);

		$this->assertSame(
			SHURLOC_CHECKOUT_TOOLS_VERSION,
			$GLOBALS['shurloc_test_enqueued_scripts'][0]['version']
		);

		$this->assertTrue(
			$GLOBALS['shurloc_test_enqueued_scripts'][0]['in_footer']
		);
	}
}
