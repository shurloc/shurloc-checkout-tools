<?php
/**
 * Tests for Shurloc_Admin_Menu.
 *
 * @package ShurlocCheckoutTools
 */

declare( strict_types=1 );

namespace Shurloc\CheckoutTools;

use PHPUnit\Framework\TestCase;
use Shurloc\Tools\Shurloc_Admin_Page_Interface;

/**
 * Tests the Checkout Tools admin menu.
 */
final class ShurlocAdminMenuTest extends TestCase {

	/**
	 * Test admin page.
	 *
	 * @var Shurloc_Admin_Page_Interface
	 */
	private Shurloc_Admin_Page_Interface $checkout_page;

	/**
	 * Admin menu.
	 *
	 * @var Shurloc_Admin_Menu
	 */
	private Shurloc_Admin_Menu $admin_menu;

	/**
	 * Sets up each test.
	 *
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();

		$GLOBALS['shurloc_test_actions']       = array();
		$GLOBALS['shurloc_test_submenu_pages'] = array();

		$this->checkout_page = new Shurloc_Test_Admin_Page();

		$this->admin_menu = new Shurloc_Admin_Menu(
			checkout_page: $this->checkout_page
		);
	}

	/**
	 * Cleans up after each test.
	 *
	 * @return void
	 */
	protected function tearDown(): void {
		unset(
			$GLOBALS['shurloc_test_actions'],
			$GLOBALS['shurloc_test_submenu_pages']
		);

		parent::tearDown();
	}

	/**
	 * Tests that the admin menu hooks are registered.
	 *
	 * @return void
	 */
	public function test_register_adds_admin_hooks(): void {
		$this->admin_menu->register();

		$this->assertCount(
			2,
			$GLOBALS['shurloc_test_actions']
		);

		$this->assertSame(
			'admin_menu',
			$GLOBALS['shurloc_test_actions'][0]['hook']
		);

		$this->assertSame(
			array( $this->admin_menu, 'register_menu' ),
			$GLOBALS['shurloc_test_actions'][0]['callback']
		);

		$this->assertSame(
			40,
			$GLOBALS['shurloc_test_actions'][0]['priority']
		);

		$this->assertSame(
			'shurloc_tools_overview',
			$GLOBALS['shurloc_test_actions'][1]['hook']
		);

		$this->assertSame(
			array( $this->admin_menu, 'render_overview_section' ),
			$GLOBALS['shurloc_test_actions'][1]['callback']
		);

		$this->assertSame(
			40,
			$GLOBALS['shurloc_test_actions'][1]['priority']
		);
	}

	/**
	 * Tests that the Checkout Tools submenu is registered.
	 *
	 * @return void
	 */
	public function test_register_menu_adds_checkout_submenu(): void {
		$this->admin_menu->register_menu();

		$this->assertCount(
			1,
			$GLOBALS['shurloc_test_submenu_pages']
		);

		$submenu = $GLOBALS['shurloc_test_submenu_pages'][0];

		$this->assertSame(
			'shurloc-tools',
			$submenu['parent_slug']
		);

		$this->assertSame(
			'ShurLoc Checkout Tools',
			$submenu['page_title']
		);

		$this->assertSame(
			'Checkout',
			$submenu['menu_title']
		);

		$this->assertSame(
			'manage_options',
			$submenu['capability']
		);

		$this->assertSame(
			'shurloc-checkout-tools',
			$submenu['menu_slug']
		);

		$this->assertSame(
			array( $this->checkout_page, 'render_page' ),
			$submenu['callback']
		);

		$this->assertSame(
			40,
			$submenu['position']
		);
	}

	/**
	 * Tests that the overview section renders the Checkout Tools link.
	 *
	 * @return void
	 */
	public function test_render_overview_section_outputs_checkout_tools_link(): void {
		ob_start();

		$this->admin_menu->render_overview_section();

		$output = ob_get_clean();

		$this->assertIsString( $output );

		$this->assertStringContainsString(
			'<h2>Checkout</h2>',
			$output
		);

		$this->assertStringContainsString(
			'Checkout and payment tools.',
			$output
		);

		$this->assertStringContainsString(
			'Open Checkout Tools',
			$output
		);

		$this->assertStringContainsString(
			'https://example.com/wp-admin/admin.php?page=shurloc-checkout-tools',
			$output
		);
	}
}
