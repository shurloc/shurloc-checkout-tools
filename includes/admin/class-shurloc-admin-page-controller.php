<?php
/**
 * Checkout admin page controller.
 *
 * Provides admin tools for checkout functions.
 *
 * @package ShurlocCheckoutTools
 */

declare( strict_types=1 );

namespace Shurloc\CheckoutTools;

use Shurloc\Tools\Shurloc_Admin_Page_Interface;

/**
 * Checkout admin page controller.
 */
final class Shurloc_Admin_Page_Controller implements Shurloc_Admin_Page_Interface {

	/**
	 * Checkout Tools settings page.
	 *
	 * @var Shurloc_Settings_Page
	 */
	private Shurloc_Settings_Page $settings_page;

	/**
	 * Constructor.
	 *
	 * @param Shurloc_Settings_Page $settings_page Checkout Tools settings page.
	 */
	public function __construct(
		Shurloc_Settings_Page $settings_page
	) {
		$this->settings_page = $settings_page;
	}

	/**
	 * Render the Checkout Tools page.
	 *
	 * @return void
	 */
	public function render_page(): void {
		$this->settings_page->render_page();
	}
}
