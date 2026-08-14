<?php
/**
 * Tariff tooltip frontend assets.
 *
 * @package ShurLocCheckoutTools
 */

declare( strict_types=1 );

namespace Shurloc\CheckoutTools;

/**
 * Registers tariff tooltip frontend assets.
 */
final class Shurloc_Tariff_Tooltips {

	/**
	 * Script handle.
	 */
	private const SCRIPT_HANDLE = 'shurloc-tariff-tooltips';

	/**
	 * Style handle.
	 */
	private const STYLE_HANDLE = 'shurloc-tariff-tooltips';

	/**
	 * Regular mesh tariff fee label.
	 */
	private const MESH_TARIFF_LABEL = 'Raw material import tariff';

	/**
	 * Sefar tariff fee label.
	 */
	private const SEFAR_TARIFF_LABEL = 'Sefar Mesh Tariff';

	/**
	 * Regular mesh tariff tooltip text.
	 */
	private const MESH_TARIFF_MESSAGE = 'Due to a 6% tariff from our suppliers, all mesh orders will include a 3% tariff fee as a separate line item on invoices. We\'re sharing this cost to minimize impact and will adjust if tariff conditions change. Thank you for your understanding.';

	/**
	 * Sefar tariff tooltip text.
	 */
	private const SEFAR_TARIFF_MESSAGE = 'Due to a 12% mesh tariff from Sefar, mesh orders will include a 9% tariff fee as a separate line item on invoices. Shur-Loc pays 3% of this tariff based on paying half of 6% for both Murakami and Saati sharing this cost to minimize industry impact and Shur-Loc will adjust if tariff conditions change. Thank you for your understanding.';

	/**
	 * Registers frontend hooks.
	 */
	public function register(): void {
		add_action(
			'wp_enqueue_scripts',
			array( $this, 'enqueue_assets' )
		);
	}

	/**
	 * Enqueues tariff tooltip assets.
	 */
	public function enqueue_assets(): void {
		if (
			! is_cart() &&
			! is_checkout()
		) {
			return;
		}

		wp_enqueue_style(
			self::STYLE_HANDLE,
			SHURLOC_CHECKOUT_TOOLS_URL . 'assets/css/tariff-tooltips.css',
			array(),
			SHURLOC_CHECKOUT_TOOLS_VERSION
		);

		wp_enqueue_script(
			self::SCRIPT_HANDLE,
			SHURLOC_CHECKOUT_TOOLS_URL . 'assets/js/tariff-tooltips.js',
			array( 'jquery' ),
			SHURLOC_CHECKOUT_TOOLS_VERSION,
			true
		);

		wp_localize_script(
			self::SCRIPT_HANDLE,
			'shurlocTariffTooltips',
			array(
				'fees' => array(
					array(
						'label'   => self::MESH_TARIFF_LABEL,
						'message' => self::MESH_TARIFF_MESSAGE,
					),
					array(
						'label'   => self::SEFAR_TARIFF_LABEL,
						'message' => self::SEFAR_TARIFF_MESSAGE,
					),
				),
			)
		);
	}
}
