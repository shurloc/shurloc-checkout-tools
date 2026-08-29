# Changelog

## [0.4.3] - 2026-08-29

### Internal Improvements

- Updated both build scripts to derive project name from script directory instead of hard coding it
- Improved required file detection logic

## [0.4.2] - 2026-08-21

### Internal Improvements

- Fixed branding in several files.
- Updated README.
- Added WooCommerce stubs to VS Code Intelephense configuration.

## [0.4.1] - 2026-08-19

### Changed

- Guard against missing Shurloc_Admin_Page_Interface.

## [0.4.0] - 2026-08-19

### Added

- Added `PayPal/Venmo` as the checkout label for the PayPal gateway.
- Added `Debit & Credit Cards (PayPal)` to the payment method row in admin New Order emails for PayPal card payments.
- Added direct Processing status handling for NET30/check and Card on File/BACS orders.
- Added automated coverage for payment gateway labels, email-context behavior, and offline payment status handling.

## [0.3.0] - 2026-08-15

### Added

- Added Checkout Tools admin interface under the shared ShurLoc Tools menu.
- Added configurable tariff settings for the raw material import and Sefar mesh tariffs.
- Added controls to enable or disable each tariff independently.
- Added configurable tariff percentages and customer-facing tooltip messages.
- Added automated test coverage for Checkout Tools settings, settings sanitization, admin menu integration, configurable tariff calculations, and tooltip configuration.

### Changed

- Updated tariff fee calculations to use configurable rates instead of hard-coded values.
- Updated tariff tooltips to use configurable customer-facing messages.
- Updated tariff percentage handling to store and display human-readable percentage values while providing decimal rates for fee calculations.

## [0.2.0] - 2026-08-14

### Added

- Added payment processing fees for eligible WooCommerce payment gateways.
- Added 1.5% processing fee for Card on File and PayPal card payments.
- Added 1.75% processing fee for eligible PayPal payment methods.
- Added automatic checkout recalculation when the selected payment method changes.
- Added PHPUnit coverage for payment processing fee calculation and frontend asset loading.

## [0.1.0] - 2026-08-14

### Added

- Added WooCommerce tariff fee calculation for Shur-Loc mesh and Sefar mesh products.
- Added 3% raw material import tariff for eligible Shur-Loc mesh products.
- Added 9% Sefar mesh tariff with precedence over the standard mesh tariff.
- Added responsive tariff information tooltips to cart and checkout pages.
- Added dedicated frontend JavaScript and CSS assets for tariff tooltip behavior and presentation.
- Added PHPUnit coverage for tariff calculation and tariff tooltip asset loading.
