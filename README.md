# Shur-loc Checkout Tools

Utilities for customizing and enhancing the Shur-loc® WooCommerce checkout and order workflow.

## Features

- Apply product-specific tariff fees to qualifying WooCommerce cart items.
- Apply payment processing fees for configured payment methods.
- Display explanatory tariff tooltips in the cart and checkout.
- Customize payment gateway labels for checkout and customer communications.
- Manage order statuses for offline payment methods.
- Integrate checkout settings into the shared Shur-loc Tools administration interface.

## Requirements

- WordPress 7.0 or later
- WooCommerce
- Shur-loc Tools
- PHP 8.4 or later

## Installation

1. Install and activate **Shur-loc Tools**.
2. Install and activate **Shur-loc Checkout Tools**.
3. Navigate to **Shur-loc Tools → Checkout** in the WordPress admin.
4. Configure the available checkout settings as needed.

## Development

### Dependencies

Shur-loc Checkout Tools depends on the shared **Shur-loc Tools** plugin for common infrastructure and admin interfaces.

For development, both repositories should be checked out as sibling directories:

```text
wordpress-plugins/
├── shurloc-tools/
└── shurloc-checkout-tools/
```

This layout allows development and static-analysis tooling to resolve classes and interfaces provided by `shurloc-tools`.

Install the development dependencies with Composer:

```bash
composer install
```

### PHPUnit

The project includes PHPUnit unit tests covering tariff fees, payment processing fees, payment gateway labels, offline payment status handling, admin functionality, and other plugin behavior.

Run the test suite:

```bash
composer test
```

### PHP_CodeSniffer

PHP_CodeSniffer is used to enforce the project's PHP coding standards.

Run code style checks:

```bash
composer lint
```

### PHPStan

PHPStan is used for static analysis of the plugin source and test suite.

Run static analysis:

```bash
composer phpstan
```

### Release Packages

A PowerShell build script is provided for creating distributable plugin packages:

```powershell
.\bin\build.ps1
```

Development files, tests, static-analysis configuration, and other files not required at runtime are excluded from release packages.

## License

This project is licensed under the MIT License. See the `LICENSE` file for details.
