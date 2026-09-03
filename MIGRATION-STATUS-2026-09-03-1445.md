# Checkout Tools Migration Status

Status captured: 2026-09-03 14:45 America/Los_Angeles

This document records the current state of the mechanical migration from
`shurloc-checkout-tools` into the `Checkout` domain of
`shurloc-site-tools`. The detailed standing rules remain in `MIGRATION.md`.

## Current Migration Instructions

- Treat the current `shurloc-checkout-tools` working tree as the authoritative
  source. Do not require comparison with the v0.4.2 tag.
- Treat Checkout Tools as read-only unless a change there is explicitly
  requested. Site Tools is the migration destination.
- Preserve source behavior. Do not refactor, redesign, optimize, or include
  unrelated improvements during migration.
- Use `Shurloc\SiteTools\Checkout` and appropriate subnamespaces in Site Tools.
- Drop the `Shurloc_` prefix from migrated class names because the destination
  namespace replaces it.
- Change the package annotation to `@package ShurlocSiteTools`.
- Change the translation text domain from `shurloc-checkout-tools` to
  `shurloc-site-tools` only where it is actually a translation domain.
- Preserve persisted identifiers, hook names, priorities, accepted argument
  counts, public behavior, HTML/CSS hooks, asset handles, request names, nonce
  behavior, query parameters, and other runtime contracts.
- If a source file does not contain `declare( strict_types=1 );`, stop and ask
  before adding it. Absence of strict types is a mandatory review point.
- Change one filesystem file at a time and stop for review unless the user
  explicitly groups files into one review unit.
- Before checks, ensure every changed file ends with exactly one trailing
  newline.
- Do not run PHPCS against a source file.
- Run PHPCS against the destination file. If the initial PHPCS check reports
  an issue, report it and do not correct it without further direction.
- Omit PHPStan unless later instructions explicitly restore it.
- Run PHPUnit only when migrating a test file, stub, or double.
- For an ordinary test file, run its relevant PHPUnit test only.
- For a stub or double change, run the complete PHPUnit suite.
- If PHPUnit fails, diagnose and fix the failure within the authorized review
  unit.
- If a candidate file depends on an unmigrated dependency, stop and report the
  dependency instead of migrating the file.
- Provide a direct source-versus-destination diff for every migrated file.
- Staging testing and the release process are out of scope.

## Decisions Made

- The Site Tools working tree and its existing shared infrastructure are the
  destination source of truth when adapting test support.
- Existing Site Tools test globals and stubs are reused instead of introducing
  duplicate Checkout Tools representations.
- Site Tools stores actions and filters by hook name and stores priority and
  accepted-argument information in separate metadata globals. Migrated tests
  must assert against that representation rather than requiring the Checkout
  Tools flat record arrays.
- Existing behavior must be preserved when a source stub or double collides
  with a Site Tools counterpart. Compatible source behavior may be added; an
  irreconcilable collision must be left for a later review unit and reported.
- The existing Site Tools `WC()` stub is retained. It uses
  `$GLOBALS['shurloc_test_woocommerce']` and returns a `WooCommerce` instance,
  creating a base instance when necessary.
- Checkout tests that need cart and session controls can assign the migrated
  `Shurloc\SiteTools\Checkout\Test_WooCommerce` to
  `$GLOBALS['shurloc_test_woocommerce']`. This is compatible because
  `Test_WooCommerce` extends the existing `WooCommerce` double.
- Tests migrated from `$GLOBALS['shurloc_test_wc']` should use
  `$GLOBALS['shurloc_test_woocommerce']` and reset it to `null` during cleanup.
- The existing hook-indexed `add_action()` and `add_filter()` stubs are retained;
  Checkout tests are adapted to them.
- The existing Site Tools behavior of shared WordPress stubs was preserved
  while adding Checkout behavior where the two representations could coexist.
- The standalone constants, standalone autoloader, and procedural standalone
  bootstrap are not expected to be copied mechanically as independent Site
  Tools infrastructure. Checkout will ultimately be registered through a
  domain `Shurloc\SiteTools\Checkout\Bootstrap` and the root Site Tools
  bootstrap.
- UTF-8 without a BOM is the documentation encoding. Editors and command-line
  tools must read Markdown files as UTF-8 so arrows and tree diagrams render
  correctly.

## Migration Status

Both repositories had clean working trees when this status was captured.

### Production Classes Migrated

| Checkout Tools source | Site Tools destination |
| --- | --- |
| `includes/settings/class-shurloc-settings.php` | `includes/checkout/settings/class-settings.php` (`Settings`) |
| `includes/checkout/class-shurloc-offline-payment-status.php` | `includes/checkout/integrations/class-offline-payment-status.php` (`Offline_Payment_Status`) |
| `includes/checkout/class-shurloc-payment-gateway-labels.php` | `includes/checkout/integrations/class-payment-gateway-labels.php` (`Payment_Gateway_Labels`) |

### Tests Migrated

| Checkout Tools source | Site Tools destination |
| --- | --- |
| `tests/settings/ShurlocSettingsTest.php` | `tests/checkout/settings/SettingsTest.php` |
| `tests/checkout/ShurlocOfflinePaymentStatusTest.php` | `tests/checkout/integrations/OfflinePaymentStatusTest.php` |
| `tests/checkout/ShurlocPaymentGatewayLabelsTest.php` | `tests/checkout/integrations/PaymentGatewayLabelsTest.php` |

The payment gateway labels test was adapted to the existing Site Tools
hook-indexed callback and metadata globals. Its targeted PHPUnit run passed 10
tests and 21 assertions. Its destination PHPCS check also passed.

### Test Doubles Migrated

The following Checkout test doubles have been added to or incorporated into
the Site Tools test infrastructure:

- `Test_Admin_Page` in `tests/doubles/class-test-admin-page.php`.
- `Test_Fee` in `tests/doubles/class-test-fee.php`.
- `Test_Session` in `tests/doubles/class-test-session.php`.
- `Test_WC_Cart` in `tests/doubles/class-test-wc-cart.php`. Missing Checkout
  cart behavior was incorporated into the existing destination counterpart.
- `Test_WooCommerce` in `tests/doubles/class-test-woocommerce.php`. It extends
  the existing global `WooCommerce` double and supplies the migrated cart and
  session doubles.
- `WC_Abstract_Order` in `tests/doubles/class-wc-abstract-order.php`.
- `WC_Email` in `tests/doubles/class-wc-email.php`.
- `WC_Order` behavior in `tests/doubles/class-wc-order.php`. The existing Site
  Tools behavior was preserved while the Checkout payment-method behavior and
  base-order relationship were added.

All of these doubles are wired into `tests/bootstrap.php` in dependency-safe
load order.

### WordPress Stubs and Globals Migrated

The destination `tests/stubs/wordpress-functions.php` now includes the missing
Checkout test state and functions for:

- Localized scripts and `wp_localize_script()`.
- Registered settings and `register_setting()`.
- Registered settings sections and `add_settings_section()`.
- Registered settings fields and `add_settings_field()`.
- `checked()`.
- `esc_textarea()`.
- `sanitize_textarea_field()`.

Compatible behavior was also incorporated into existing counterparts for:

- `is_admin()`.
- `has_term()`.
- `add_submenu_page()`.

The following existing Site Tools counterparts were deliberately not replaced:

- `add_action()` and `add_filter()` use hook-indexed callback arrays plus
  separate metadata arrays. Migrated Checkout tests must use this structure.
- `wp_enqueue_style()` and `wp_enqueue_script()` use the existing Site Tools
  enqueue representation. Checkout tests that inspect enqueues must be adapted.
- `current_user_can()` uses the existing Site Tools capability representation.
  Checkout tests must be adapted if their source expectations differ.
- `esc_attr()` retains the existing Site Tools behavior. Checkout tests must be
  adapted if their source expectations differ.

### WooCommerce Stubs and Globals Migrated

The destination `tests/stubs/woocommerce-functions.php` now includes:

- `$GLOBALS['shurloc_test_is_cart']` and `is_cart()`.
- `$GLOBALS['shurloc_test_is_checkout']` and `is_checkout()`.

The source `WC()` stub and `$GLOBALS['shurloc_test_wc']` were not copied because
Site Tools already has the compatible `WC()` implementation described above.

## Work Left To Do

### Remaining Production Components

Recommended dependency-aware order:

1. Migrate `includes/checkout/class-shurloc-tariff-fees.php` after confirming
   its already-migrated `Settings` and test-double dependencies.
2. Migrate `tests/checkout/ShurlocTariffFeesTest.php`, adapting it to
   `Test_WooCommerce`, `$GLOBALS['shurloc_test_woocommerce']`, and the existing
   hook registry.
3. Migrate `includes/checkout/class-shurloc-payment-processing-fee.php`.
4. Migrate `tests/checkout/ShurlocPaymentProcessingFeeTest.php`, adapting its
   WooCommerce global, hook assertions, and script-enqueue assertions to Site
   Tools representations.
5. Migrate `includes/frontend/class-shurloc-tariff-tooltips.php` into an
   appropriate Checkout frontend or integration namespace.
6. Migrate `tests/frontend/ShurlocTariffTooltipsTest.php`, adapting shared stub
   representations where required.
7. Migrate the three Checkout-specific assets into `assets/checkout/`:
   `tariff-tooltips.css`, `tariff-tooltips.js`, and
   `payment-processing-fee.js`. Each asset is a separate review unit unless
   explicitly grouped.
8. Migrate `includes/admin/class-shurloc-settings-page.php` and its test.
9. Migrate `includes/admin/class-shurloc-admin-page-controller.php`. There is
   no direct source test file; its behavior is exercised through the admin
   composition tests.
10. Migrate `includes/admin/class-shurloc-admin-menu.php` and its test, using
    the existing shared `Admin_Page_Interface` rather than recreating the old
    shared dependency.
11. Create the Checkout domain `Bootstrap`, register all migrated Checkout
    components, and add Checkout bootstrap tests.
12. Wire the Checkout domain bootstrap into the root Site Tools bootstrap and
    extend the root bootstrap tests.

### Remaining Supporting Work

- Review whether any Checkout-specific additions are still required in the
  existing Site Tools test bootstrap as each remaining test is migrated.
- Do not copy the standalone `tests/phpstan-bootstrap.php` merely for the
  current check workflow; PHPStan is presently omitted. Reassess it only if
  required for the final consolidated static-analysis setup.
- Determine whether any standalone constant values remain runtime contracts
  needed by migrated asset paths. Do not copy standalone plugin path/version
  constants automatically.
- Perform the mechanical migration audit documented in `MIGRATION.md` after
  all components are migrated.
- Run the final complete PHPUnit and PHPCS suites when component migration and
  bootstrap wiring are complete. PHPStan remains omitted under the current
  instructions.
- Do not perform staging verification, version/release work, changelog release
  preparation, or tagging as part of this migration assignment.

## Review Boundary

The next file should not be migrated until this status document has been
reviewed, consistent with the one-file-at-a-time rule.
