# Checkout Tools Migration Status

Status captured: 2026-09-03 16:40 America/Los_Angeles

Last updated: 2026-09-03

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
  an issue, correct it and rerun the check.
- Omit PHPStan during individual migration units unless explicitly requested.
  PHPStan was explicitly included in the final verification run.
- Run PHPUnit only when migrating or creating a test file, stub, or double.
- For an ordinary test file, run only its relevant PHPUnit test.
- For a stub or double change, run the complete PHPUnit suite.
- If PHPUnit fails, diagnose and fix the failure within the authorized review
  unit.
- If a candidate file depends on an unmigrated dependency, stop and report the
  dependency instead of migrating the file.
- Provide the actual direct source-versus-destination diff for every migrated
  file. For a new file without a source counterpart, show a `/dev/null` diff.
- Do not perform the abandoned JavaScript formatting work. The migrated asset
  bodies remain mechanical copies; only the required Site Tools headers and
  destination filenames differ from the source assets.
- Staging testing and release work are out of scope.

## Decisions Made

- The Site Tools working tree and its existing shared infrastructure are the
  destination source of truth when adapting test support.
- Existing Site Tools test globals and stubs are reused instead of introducing
  duplicate Checkout Tools representations.
- Site Tools stores actions and filters by hook name and stores priority and
  accepted-argument information in separate metadata globals. Migrated tests
  assert against that representation rather than requiring the Checkout Tools
  flat record arrays.
- Existing behavior must be preserved when a source stub or double collides
  with a Site Tools counterpart. Compatible source behavior may be added; an
  irreconcilable collision must be left for a later review unit and reported.
- The existing Site Tools `WC()` stub is retained. Checkout tests use
  `$GLOBALS['shurloc_test_woocommerce']` rather than the source
  `$GLOBALS['shurloc_test_wc']` representation.
- The existing hook-indexed `add_action()` and `add_filter()` stubs are retained;
  Checkout tests are adapted to them.
- The existing Site Tools enqueue and capability representations are retained;
  Checkout tests are adapted to them.
- The standalone Checkout Tools constants, autoloader, procedural bootstrap,
  and plugin entry point are not copied as parallel Site Tools infrastructure.
- Checkout composition is implemented by
  `Shurloc\SiteTools\Checkout\Bootstrap`. Autoloader setup and the
  `plugins_loaded` hook remain responsibilities of the root Site Tools
  bootstrap.
- The shared `Admin_Page_Interface` is used directly in Site Tools. The source
  runtime guard for the separate shared-tools plugin is unnecessary inside the
  integrated Site Tools codebase.
- UTF-8 without a BOM is the documentation encoding. Editors and command-line
  tools must read Markdown files as UTF-8 so arrows and tree diagrams render
  correctly.

## Migration Status

### Production Components Migrated

| Checkout Tools source                                        | Site Tools destination                                                                       |
| ------------------------------------------------------------ | -------------------------------------------------------------------------------------------- |
| `includes/settings/class-shurloc-settings.php`               | `includes/checkout/settings/class-settings.php` (`Settings`)                                 |
| `includes/checkout/class-shurloc-offline-payment-status.php` | `includes/checkout/integrations/class-offline-payment-status.php` (`Offline_Payment_Status`) |
| `includes/checkout/class-shurloc-payment-gateway-labels.php` | `includes/checkout/integrations/class-payment-gateway-labels.php` (`Payment_Gateway_Labels`) |
| `includes/checkout/class-shurloc-tariff-fees.php`            | `includes/checkout/integrations/class-tariff-fees.php` (`Tariff_Fees`)                       |
| `includes/checkout/class-shurloc-payment-processing-fee.php` | `includes/checkout/integrations/class-payment-processing-fee.php` (`Payment_Processing_Fee`) |
| `includes/frontend/class-shurloc-tariff-tooltips.php`        | `includes/checkout/frontend/class-tariff-tooltips.php` (`Tariff_Tooltips`)                   |
| `includes/admin/class-shurloc-settings-page.php`             | `includes/checkout/admin/class-settings-page.php` (`Settings_Page`)                          |
| `includes/admin/class-shurloc-admin-page-controller.php`     | `includes/checkout/admin/class-admin-page-controller.php` (`Admin_Page_Controller`)          |
| `includes/admin/class-shurloc-admin-menu.php`                | `includes/checkout/admin/class-admin-menu.php` (`Admin_Menu`)                                |
| `includes/bootstrap.php` composition                         | `includes/checkout/class-bootstrap.php` (`Bootstrap`)                                        |
| Checkout domain registration                                 | Existing root `includes/bootstrap.php`                                                       |

The Checkout domain bootstrap composes and registers all migrated settings,
admin, tariff, frontend, payment-fee, payment-label, and offline-status
components. It intentionally does not duplicate root autoloader or
`plugins_loaded` responsibilities. The root Site Tools bootstrap now imports,
instantiates, and registers the Checkout domain bootstrap immediately after
registering the root autoloader. Its destination PHPCS check passed.

### Tests Migrated or Added

| Checkout Tools source                                | Site Tools destination                                     |
| ---------------------------------------------------- | ---------------------------------------------------------- |
| `tests/settings/ShurlocSettingsTest.php`             | `tests/checkout/settings/SettingsTest.php`                 |
| `tests/checkout/ShurlocOfflinePaymentStatusTest.php` | `tests/checkout/integrations/OfflinePaymentStatusTest.php` |
| `tests/checkout/ShurlocPaymentGatewayLabelsTest.php` | `tests/checkout/integrations/PaymentGatewayLabelsTest.php` |
| `tests/checkout/ShurlocTariffFeesTest.php`           | `tests/checkout/integrations/TariffFeesTest.php`           |
| `tests/checkout/ShurlocPaymentProcessingFeeTest.php` | `tests/checkout/integrations/PaymentProcessingFeeTest.php` |
| `tests/frontend/ShurlocTariffTooltipsTest.php`       | `tests/checkout/frontend/TariffTooltipsTest.php`           |
| `tests/admin/ShurlocSettingsPageTest.php`            | `tests/checkout/admin/SettingsPageTest.php`                |
| No dedicated source counterpart                      | `tests/checkout/admin/AdminPageControllerTest.php`         |
| `tests/admin/ShurlocAdminMenuTest.php`               | `tests/checkout/admin/AdminMenuTest.php`                   |
| No dedicated source counterpart                      | `tests/checkout/BootstrapTest.php`                         |

The dedicated admin-page-controller test was added because the source behavior
had only indirect coverage. The Checkout bootstrap test follows the conventions
used by the other Site Tools domain bootstrap tests. Its targeted run passed 3
tests and 11 assertions. The existing root `tests/BootstrapTest.php` now verifies
Checkout admin, fee, and payment-filter registration. Its targeted run passed 1
test and 12 assertions.

### Assets Migrated

| Checkout Tools source                 | Site Tools destination                                 |
| ------------------------------------- | ------------------------------------------------------ |
| `assets/css/tariff-tooltips.css`      | `assets/checkout/css/shurloc-tariff-tooltips.css`      |
| `assets/js/tariff-tooltips.js`        | `assets/checkout/js/shurloc-tariff-tooltips.js`        |
| `assets/js/payment-processing-fee.js` | `assets/checkout/js/shurloc-payment-processing-fee.js` |

The pending JavaScript formatting work was explicitly abandoned. The audit
identified formatting changes in the tariff tooltip JavaScript and CSS; those
changes were reverted so each destination asset body now matches its
authoritative source exactly. The only source-to-destination content difference
is the required Site Tools file header.

### Test Doubles Migrated

The following Checkout test doubles have been added to or incorporated into
the Site Tools test infrastructure:

- `Test_Admin_Page` in `tests/doubles/class-test-admin-page.php`.
- `Test_Fee` in `tests/doubles/class-test-fee.php`.
- `Test_Session` in `tests/doubles/class-test-session.php`.
- `Test_WC_Cart` in `tests/doubles/class-test-wc-cart.php`.
- `Test_WooCommerce` in `tests/doubles/class-test-woocommerce.php`.
- `WC_Abstract_Order` in `tests/doubles/class-wc-abstract-order.php`.
- `WC_Email` in `tests/doubles/class-wc-email.php`.
- Checkout behavior incorporated into `tests/doubles/class-wc-order.php`.

The doubles are wired into `tests/bootstrap.php` in dependency-safe load order.
WooCommerce parent-signature compatibility issues reported during migration
were corrected while preserving the test behavior.

### WordPress Stubs and Globals Migrated

The destination `tests/stubs/wordpress-functions.php` includes the required
Checkout state and behavior for localized scripts, settings, settings sections,
settings fields, `checked()`, `esc_textarea()`, and
`sanitize_textarea_field()`.

Compatible behavior was incorporated into the existing `is_admin()`,
`has_term()`, and `add_submenu_page()` counterparts. Existing Site Tools
behavior remains authoritative for hook-indexed actions and filters, enqueued
assets, capabilities, and escaping.

### WooCommerce Stubs and Globals Migrated

The destination `tests/stubs/woocommerce-functions.php` includes the Checkout
state and behavior for `is_cart()` and `is_checkout()`. The existing Site Tools
`WC()` implementation and `$GLOBALS['shurloc_test_woocommerce']` remain the
shared representation.

## Mechanical Migration Audit

The mechanical migration audit is complete. It verified:

- Complete source-to-destination component coverage.
- No stale Checkout Tools namespace, package annotation, standalone path
  constant, URL constant, or version constant remains in the destination
  Checkout scope.
- Remaining `shurloc-checkout-tools` values are intentional persisted menu and
  page slugs.
- The remaining `shurloc-tools` value is the intentional shared parent-menu
  slug.
- Asset handles and localized JavaScript object names remain unchanged.
- Enqueued asset URLs point into `assets/checkout/` and use Site Tools constants.
- All migrated PHP files contain `declare( strict_types=1 );`.
- Checkout test stubs retain the established Site Tools guards and shared
  representations.
- The Checkout domain bootstrap registers every migrated component and is
  registered by the root Site Tools bootstrap.
- Migrated Checkout files are UTF-8 without a BOM and end with exactly one
  trailing newline.

The two asset-formatting findings and the inaccurate status statement about
`"use strict";` were corrected after the audit.

## Final Verification

The complete Site Tools verification suites passed after the audit corrections:

- PHPCS: passed with no findings.
- PHPUnit: passed, 765 tests and 1,631 assertions.
- PHPStan: passed at configured level 8 across 216 files with no errors.

## Work Left To Do

No migration implementation or verification work remains under the current
scope. The status filename or timestamp may be adjusted if a final archival
naming convention is desired.

Do not independently copy the standalone Checkout Tools constants, autoloader,
`tests/phpstan-bootstrap.php`, procedural bootstrap, or plugin entry point unless
the final audit finds a specific runtime contract that the integrated Site Tools
implementation does not cover.

Do not perform staging verification, version/release work, changelog release
preparation, or tagging as part of this migration assignment.

## Review Boundary

The Checkout migration is complete under the defined scope. Any further file
changes require a new reviewed task.
