<?php
/**
 * Tariff fee calculation.
 *
 * @package ShurlocCheckoutTools
 */

declare( strict_types=1 );

namespace Shurloc\CheckoutTools;

/**
 * Adds tariff fees to the WooCommerce cart.
 */
final class Shurloc_Tariff_Fees {

	/**
	 * Mesh product category slug.
	 */
	private const MESH_CATEGORY_SLUG = 'shurloc-mesh';

	/**
	 * Sefar product tag slug.
	 */
	private const SEFAR_TAG_SLUG = 'sefar';

	/**
	 * Regular mesh tariff rate.
	 */
	private const MESH_TARIFF_RATE = 0.03;

	/**
	 * Sefar tariff rate.
	 */
	private const SEFAR_TARIFF_RATE = 0.09;

	/**
	 * Regular mesh tariff fee label.
	 */
	private const MESH_TARIFF_LABEL = 'Raw material import tariff';

	/**
	 * Sefar tariff fee label.
	 */
	private const SEFAR_TARIFF_LABEL = 'Sefar Mesh Tariff';

	/**
	 * Registers WooCommerce hooks.
	 */
	public function register(): void {
		add_action(
			'woocommerce_cart_calculate_fees',
			array( $this, 'add_tariff_fees' )
		);
	}

	/**
	 * Adds applicable tariff fees to the cart.
	 */
	public function add_tariff_fees(): void {
		if (
			is_admin() &&
			! defined( 'DOING_AJAX' )
		) {
			return;
		}

		$mesh_total  = 0.0;
		$sefar_total = 0.0;

		foreach ( WC()->cart->get_cart() as $cart_item ) {
			if (
				! isset( $cart_item['product_id'] ) ||
				! isset( $cart_item['line_total'] )
			) {
				continue;
			}

			$product_id = (int) $cart_item['product_id'];
			$line_total = (float) $cart_item['line_total'];

			if ( 0 >= $product_id || 0 >= $line_total ) {
				continue;
			}

			/*
			 * Sefar takes precedence over the regular mesh tariff.
			 */
			if (
				has_term(
					self::SEFAR_TAG_SLUG,
					'product_tag',
					$product_id
				)
			) {
				$sefar_total += $line_total;
				continue;
			}

			if (
				has_term(
					self::MESH_CATEGORY_SLUG,
					'product_cat',
					$product_id
				)
			) {
				$mesh_total += $line_total;
			}
		}

		if ( 0 < $mesh_total ) {
			WC()->cart->add_fee(
				self::MESH_TARIFF_LABEL,
				$mesh_total * self::MESH_TARIFF_RATE
			);
		}

		if ( 0 < $sefar_total ) {
			WC()->cart->add_fee(
				self::SEFAR_TARIFF_LABEL,
				$sefar_total * self::SEFAR_TARIFF_RATE
			);
		}
	}
}
