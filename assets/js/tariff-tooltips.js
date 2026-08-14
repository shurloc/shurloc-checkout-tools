(function ($) {
	'use strict';

	function getFeeConfig(label) {
		let match = null;

		$.each(shurlocTariffTooltips.fees, function (index, fee) {
			if (fee.label === label) {
				match = fee;
				return false;
			}

			return true;
		});

		return match;
	}

	function createDesktopTooltip(fee) {
		return $(
			'<span>',
			{
				class: 'shurloc-tariff-tooltip'
			}
		)
			.append(
				$(
					'<span>',
					{
						class: 'shurloc-tariff-tooltip-icon',
						'aria-hidden': 'true',
						text: 'ⓘ'
					}
				)
			)
			.append(
				$(
					'<span>',
					{
						class: 'shurloc-tariff-tooltip-text',
						text: fee.message
					}
				).append(
					$(
						'<span>',
						{
							class: 'shurloc-tariff-tooltip-arrow'
						}
					)
				)
			);
	}

	function createMobileTooltip(fee) {
		const $popup = $(
			'<div>',
			{
				class: 'shurloc-mobile-tariff-popup'
			}
		)
			.append(
				$(
					'<p>',
					{
						text: fee.message
					}
				)
			)
			.append(
				$(
					'<button>',
					{
						type: 'button',
						class: 'shurloc-mobile-tariff-close',
						text: 'Close'
					}
				)
			);

		return $(
			'<span>',
			{
				class: 'shurloc-mobile-tariff-tooltip'
			}
		)
			.append(
				$(
					'<button>',
					{
						type: 'button',
						class: 'shurloc-mobile-tariff-icon',
						'aria-label': 'Tariff information',
						text: 'ⓘ'
					}
				)
			)
			.append($popup);
	}

	function addDesktopTooltips() {
		$('.fee th').each(function () {
			const $header = $(this);
			const label = $.trim($header.text());
			const fee = getFeeConfig(label);

			if (!fee) {
				return;
			}

			if ($header.find('.shurloc-tariff-tooltip').length) {
				return;
			}

			$header
				.empty()
				.append(document.createTextNode(fee.label + ' '))
				.append(createDesktopTooltip(fee));
		});
	}

	function addMobileTooltips() {
		$('.fee td').each(function () {
			const $cell = $(this);
			const dataTitle = $cell.attr('data-title');

			if (!dataTitle) {
				return;
			}

			const fee = getFeeConfig(dataTitle);

			if (!fee) {
				return;
			}

			if ($cell.find('.shurloc-mobile-tariff-tooltip').length) {
				return;
			}

			$cell.append(createMobileTooltip(fee));
		});
	}

	function addTariffTooltips() {
		addDesktopTooltips();
		addMobileTooltips();
	}

	$(function () {
		addTariffTooltips();

		$(document.body).on(
			'updated_cart_totals updated_checkout',
			addTariffTooltips
		);

		$(document).on(
			'click',
			'.shurloc-mobile-tariff-icon',
			function () {
				$(this)
					.siblings('.shurloc-mobile-tariff-popup')
					.fadeIn(200);
			}
		);

		$(document).on(
			'click',
			'.shurloc-mobile-tariff-close',
			function () {
				$(this)
					.closest('.shurloc-mobile-tariff-popup')
					.fadeOut(200);
			}
		);

		$(document).on(
			'click',
			'.shurloc-mobile-tariff-popup',
			function (event) {
				if (event.target === this) {
					$(this).fadeOut(200);
				}
			}
		);
	});
})(jQuery);
