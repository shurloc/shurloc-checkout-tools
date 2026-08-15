<?php
/**
 * Tariff tooltip frontend assets.
 *
 * @package ShurlocCheckoutTools
 */

declare( strict_types=1 );

namespace Shurloc\CheckoutTools;

/**
 * Registers tariff tooltip frontend assets.
 */
final class Shurloc_Tariff_Tooltips {

	/**
	 * Script handle.
	 *
	 * @var string
	 */
	private const SCRIPT_HANDLE = 'shurloc-tariff-tooltips';

	/**
	 * Style handle.
	 *
	 * @var string
	 */
	private const STYLE_HANDLE = 'shurloc-tariff-tooltips';

	/**
	 * Regular mesh tariff fee label.
	 *
	 * @var string
	 */
	private const MESH_TARIFF_LABEL = 'Raw material import tariff';

	/**
	 * Sefar tariff fee label.
	 *
	 * @var string
	 */
	private const SEFAR_TARIFF_LABEL = 'Sefar Mesh Tariff';

	/**
	 * Checkout Tools settings.
	 *
	 * @var Shurloc_Settings
	 */
	private Shurloc_Settings $settings;

	/**
	 * Creates the tariff tooltip handler.
	 *
	 * @param Shurloc_Settings $settings Checkout Tools settings.
	 */
	public function __construct(
		Shurloc_Settings $settings
	) {
		$this->settings = $settings;
	}

	/**
	 * Registers frontend hooks.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action(
			'wp_enqueue_scripts',
			array( $this, 'enqueue_assets' )
		);
	}

	/**
	 * Enqueues tariff tooltip assets.
	 *
	 * @return void
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
				'fees' => $this->get_tooltip_fees(),
			)
		);
	}

	/**
	 * Gets configured tariff tooltip data.
	 *
	 * @return array<int, array{label: string, message: string}> Tooltip fee data.
	 */
	private function get_tooltip_fees(): array {
		$fees = array();

		if ( $this->settings->is_mesh_tariff_enabled() ) {
			$fees[] = array(
				'label'   => self::MESH_TARIFF_LABEL,
				'message' => $this->settings->get_mesh_tariff_message(),
			);
		}

		if ( $this->settings->is_sefar_tariff_enabled() ) {
			$fees[] = array(
				'label'   => self::SEFAR_TARIFF_LABEL,
				'message' => $this->settings->get_sefar_tariff_message(),
			);
		}

		return $fees;
	}
}
