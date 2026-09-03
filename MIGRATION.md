# Shurloc Checkout Tools → Site Tools Migration Guide

## Purpose

This document defines the rules for mechanically migrating the
standalone `shurloc-checkout-tools` plugin into the `Checkout` domain of
`shurloc-site-tools`.

The source plugin is the authoritative reference for behavior. The goal
is consolidation, not refactoring.

## Repository Roles

When both repositories are open in the same VS Code multi-root
workspace:

- `shurloc-checkout-tools` is the **read-only source/reference
  repository**.
- `shurloc-site-tools` is the **destination repository** and the only
  repository that should be modified for the migration.
- Do not modify the source Checkout Tools repository unless explicitly
  instructed.

## Migration Clarifications

The following project-specific clarifications apply to this migration:

- The authoritative source is the current `shurloc-checkout-tools`
  working tree. It is expected to be identical to the v0.4.2 tag except
  for this migration document.
- The migration targets the current `shurloc-site-tools` working tree.
  The destination should not contain pre-existing uncommitted changes
  when migration work begins.
- Migrated class and interface names should drop the `Shurloc_` prefix;
  the destination namespace replaces that prefix.
- Before adding `declare( strict_types=1 );` to a migrated file that does
  not already contain it, obtain explicit approval.
- Persisted identifiers may be reviewed case by case. A separate
  up-front identifier inventory is not required.
- The assignment covers code consolidation and verification only. It
  does not include staging verification or the release process.
- Do not add coexistence, upgrade, deactivation, or other safeguards for
  installations where both plugins may be active. Only consolidate the
  existing code.
- Change exactly one filesystem file at a time, then stop for review and
  approval before changing another file.
- Production files, tests, data providers, stubs, bootstraps, and all
  other supporting files each count as separate filesystem files and
  therefore require separate review steps.

## Primary Rule: Mechanical Migration Only

Preserve the existing Checkout Tools behavior and implementation.

Do not refactor, redesign, optimize, simplify, modernize, or otherwise
change behavior while migrating.

Allowed changes are limited to those necessary for consolidation,
including:

- Namespace changes.
- Imports required by namespace changes.
- Class/interface names when required by the consolidated naming
  structure.
- File paths and file names required by the Site Tools structure.
- Text domain changes to `shurloc-site-tools`.
- File header/package changes to `@package ShurlocSiteTools`.
- Asset paths and asset filenames.
- Bootstrap and autoloader wiring.
- Shared-interface references.
- Test namespaces, paths, bootstrap references, and fixture paths
  required by the move.
- Named parameters for calls to internal Shur-loc methods and
  constructors.

If an implementation improvement is discovered during migration, record
or report it separately. Do not include it in the migration unless
explicitly approved.

## Behavior That Must Be Preserved

Unless a consolidation change makes it technically unavoidable,
preserve:

- WordPress and WooCommerce hook names.
- Hook priorities and accepted argument counts.
- Public method behavior.
- Constructor behavior and dependency composition.
- WordPress request/action names.
- Nonce actions and nonce behavior.
- Query parameters.
- Shortcode names.
- HTML structure and CSS hooks.
- JavaScript behavior.
- WooCommerce filters and callbacks.
- Database queries.
- Structured-data behavior.
- Breadcrumb behavior.
- Admin report behavior.
- Migration behavior.
- Error handling.
- Sorting and ordering.
- Cache behavior.
- Return types and values.

Do not make unrelated formatting or implementation changes merely
because a file is being migrated.

## Persisted Identifiers

Do not rename persisted identifiers merely because the plugin is moving.

Preserve existing identifiers unless explicitly reviewed and approved,
including:

- WordPress option names.
- User meta keys.
- Post/product meta keys.
- Taxonomy metadata.
- Migration version options.
- Migration last-run options.
- Migration lock options.
- Cache keys and cache-generation keys.
- Existing stored Yoast-compatible metadata.
- Any identifier that may already exist in production data.

A legacy `shurloc-checkout-tools` string is not automatically safe to
replace. Determine whether it is a text domain/path identifier or a
persisted/runtime contract before changing it.

## Destination Namespace

Checkout-domain classes should use the root namespace:

```php
Shurloc\SiteTools\Checkout
```

Use subnamespaces that correspond to the existing domain boundaries, for
example:

```php
Shurloc\SiteTools\Checkout\Admin
Shurloc\SiteTools\Checkout\Generators
Shurloc\SiteTools\Checkout\Integrations
Shurloc\SiteTools\Checkout\Migrations
Shurloc\SiteTools\Checkout\Parsers
Shurloc\SiteTools\Checkout\Services
```

Do not reorganize classes into new architectural layers as part of the
migration.

## Destination Structure

Checkout functionality belongs under:

```text
shurloc-site-tools/
├── assets/
│   └── checkout/
│       ├── css/
│       └── js/
├── includes/
│   └── checkout/
│       ├──  admin/
│       ├──  generators/
│       ├──  integrations/
│       ├──  migrations/
│       ├──  parsers/
│       ├──  services/
│       └── class-bootstrap.php
└── tests/
        └──  checkout/
```

Preserve additional existing Checkout Tools subdirectories when needed
rather than forcing files into an inappropriate category.

## File Headers

PHP, JavaScript, and CSS files migrated into Site Tools must have an
appropriate file-level header docblock.

Use:

```text
@package ShurlocSiteTools
```

Do not retain `@package ShurlocCheckoutTools` in migrated files.

## Text Domain

User-facing translations in the migrated Checkout domain should use:

```text
shurloc-site-tools
```

Replace the standalone plugin text domain `shurloc-checkout-tools` where
it is actually being used as a WordPress translation text domain.

Do not blindly replace identical strings when they serve another
persisted or contractual purpose.

## Internal Named Parameters

Named parameters may be added to calls to internal Shur-loc methods and
constructors.

Example:

```php
$service = new Checkout_Service(
    dependency: $dependency,
);
```

Do not use named parameters merely to alter calls to WordPress,
WooCommerce, or other external APIs.

## PHP Style Conventions

Follow the existing `shurloc-site-tools` conventions:

- Use `declare( strict_types=1 );`.
- Use the Site Tools namespace structure.
- Prefer imports for global PHP classes instead of leading backslash
  notation.
- Do not use `parent` as a variable or parameter name; use a
  descriptive alternative such as `parent_id` or `parent_term`.
- Do not use `default` as a variable or parameter name; use a
  descriptive alternative such as `default_value`.
- Preserve existing implementation logic during migration.

## Assets

Checkout-specific assets belong under:

```text
assets/checkout/css/
assets/checkout/js/
```

Asset filenames should use the `shurloc-` prefix.

When migrating an asset:

1.  Preserve its behavior.
2.  Add/update its file header with `@package ShurlocSiteTools`.
3.  Rename the asset only as needed to follow the Site Tools asset
    convention.
4.  Update the corresponding PHP enqueue/register path.
5.  Preserve handles unless changing a handle is necessary for
    consolidation and has been explicitly reviewed.

Do not rewrite JavaScript or CSS as part of the migration.

## Shared Interfaces

The standalone Checkout Tools plugin previously depended on shared
infrastructure from `shurloc-tools`.

In Site Tools, use the equivalent shared interfaces already migrated
under:

```text
includes/shared/interfaces/
```

For admin pages, the shared interface is:

```php
Shurloc\SiteTools\Shared\Interfaces\Admin_Page_Interface
```

Use the actual existing Site Tools namespace/file as the source of truth
if it differs.

Do not recreate duplicate shared interfaces inside the Checkout domain.

## Checkout Tools Source Scope

The Checkout Tools v0.4.2 source includes functionality for areas such
as:

- Checkout Tools constants, bootstrap, and namespace autoloading.
- Configurable mesh and Sefar tariff fees for qualifying WooCommerce cart
  items, including their tariff rates and customer-facing messages.
- Cart and checkout tariff-tooltip CSS and JavaScript assets.
- Payment-processing fees for the configured PayPal and bank-transfer payment
  gateways, including the checkout-refresh JavaScript asset.
- Checkout and order-email payment-gateway labels for PayPal and PayPal card
  payments.
- Processing-status handling for cheque and BACS payments.
- The Checkout Tools settings option and its normalization defaults.
- Checkout tariff settings registration, sanitization, and rendering in the
  WordPress admin.
- Checkout settings-page registration within the shared Shur-loc Tools admin
  menu and overview.
- WordPress and WooCommerce test stubs and doubles supporting the checkout,
  frontend, settings, and admin test suites.

The source repository is authoritative for the exact current
implementation.

## Testing Strategy

Migrate in small, verifiable units.

Preferred workflow:

```text
production file
→ corresponding test
→ required test stub/double adjustment
→ PHPUnit green
→ PHPStan/PHPCS clean
→ next file
```

Do not migrate a large group of files and then repair tests afterward
unless dependencies make that unavoidable.

Tests should continue to assert the same behavior as the standalone
Checkout Tools tests.

Changes to tests should primarily be mechanical:

- Namespace updates.
- Import updates.
- Path updates.
- Package/header updates.
- Shared-interface updates.
- Bootstrap updates.
- Named parameters for internal calls.
- Stub/double integration with the consolidated Site Tools test
  infrastructure.

Do not weaken assertions merely to make migrated code pass.

## Test Stubs and Doubles

Reuse the existing Site Tools WordPress/WooCommerce stubs and doubles
where possible.

Conventions:

- Keep WordPress and WooCommerce stubs appropriately separated.
- Guard stub functions with `function_exists()` where that is the
  established Site Tools pattern.
- Include PHPDoc-style comments/docblocks.
- For test globals, use PHPDoc-style descriptive comments but **do not
  use `@var` annotations**.
- Avoid changing an existing shared stub/global in a way that breaks
  unrelated tests.
- Prefer isolated additions or compatible extensions when Checkout
  tests need additional behavior.

## Recommended Migration Order

Use dependency-first migration order.

1.  Inventory the complete Checkout Tools source tree.
2.  Map every source file to its Site Tools destination.
3.  Identify persisted identifiers that must remain unchanged.
4.  Migrate low-level models/interfaces/parsers.
5.  Migrate generators and other low-level transformation components.
6.  Migrate services.
7.  Migrate integrations/frontend components.
8.  Migrate migrations.
9.  Migrate admin controllers/pages/menu components.
10. Migrate JavaScript and CSS assets.
11. Create/register the Checkout domain bootstrap.
12. Add Checkout bootstrap tests.
13. Wire Checkout into the root Site Tools bootstrap.
14. Extend root bootstrap tests.
15. Perform a mechanical migration audit.
16. Run the complete test/static-analysis/coding-standard suite.
17. Test on staging.
18. Cut a Site Tools release.

If the actual source dependency graph indicates a slightly different
file order, follow the source dependencies while preserving this overall
approach.

## Mechanical Migration Audit

Before declaring the migration complete, search the destination for:

```text
ShurlocCheckoutTools
Shurloc\CheckoutTools
shurloc-checkout-tools
ShurlocCheckoutTools
@package ShurlocCheckoutTools
```

Also search for:

- Standalone Checkout Tools constants.
- Old plugin filesystem paths.
- Old plugin URL constants.
- Dependencies on the standalone `shurloc-tools` plugin that should
  now use Site Tools shared infrastructure.
- Asset references outside `assets/checkout/`.
- Missing Checkout bootstrap registrations.

Review every match individually.

Do not blindly replace persisted identifiers or intentional
compatibility strings.

## Bootstrap Rules

The Checkout domain should ultimately be registered through:

```php
Shurloc\SiteTools\Checkout\Bootstrap
```

The root `shurloc-site-tools` bootstrap should instantiate and register
the Checkout bootstrap alongside the existing domains.

Do not wire Checkout into the root bootstrap until its component-level
migration is complete and its domain bootstrap tests are green.

## Release Discipline

Do not mix unrelated improvements into the Checkout migration release.

Before release:

- PHPUnit must pass.
- PHPStan must pass.
- PHPCS must pass.
- Checkout functionality must be verified on staging.
- The changelog must describe the Checkout Tools consolidation.
- Version information must be updated.
- The release should receive an annotated Git tag.

Current Site Tools annotated-tag convention:

- One summary line.
- Three detail lines.

## Working Rule for Codex

When uncertain whether a proposed change is required for consolidation
or is an implementation improvement:

**Do not make the change.**

Preserve the source implementation and report the possible improvement
separately for later review.

The objective is to be able to compare the standalone Checkout Tools
implementation with the Site Tools Checkout domain and see that behavior
has been preserved while ownership, namespaces, paths, dependencies, and
bootstrap wiring have moved into the consolidated plugin.
