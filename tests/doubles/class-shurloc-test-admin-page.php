<?php
/**
 * Admin page test double.
 *
 * @package ShurlocCheckoutTools
 */

declare( strict_types=1 );

namespace Shurloc\CheckoutTools;

use Shurloc\Tools\Shurloc_Admin_Page_Interface;

/**
 * Test admin page.
 */
final class Shurloc_Test_Admin_Page implements Shurloc_Admin_Page_Interface {

	/**
	 * Render the test admin page.
	 *
	 * @return void
	 */
	public function render_page(): void {
	}
}
