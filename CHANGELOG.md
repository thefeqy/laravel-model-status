# Changelog

All notable changes to `Thefeqy/laravel-model-status` will be documented in this file.  
This project follows [Semantic Versioning](https://semver.org/).

---

## [v1.2.1] - 2025-02-22
### Enhancements
- **Added withActiveScope to retrieve only active models**

---

## [v1.2.0] - 2025-02-17
### Enhancements
- **Added a local installation command** (`php artisan model-status:install`) to publish config and set up `.env` keys.
- **Implemented an admin bypass feature** to allow admins to see all models without using `withoutActive()`.
- **Added support for dynamic status values** in the `Status` class (removes dependency on static enums).

## Fixes & Improvements

- **Fixed missing** User model in tests by creating a FakeUser model implementing Authenticatable.
- **Refactored tests to use Orchestra Testbench correctly**, ensuring package isolation from Laravel core.

## Documentation Updates
- **Updated** [README.md](README.md) with new features and admin bypass details.
- **Added** [CONTRIBUTING.md](CONTRIBUTING.md) to guide contributors through setup, testing, and PR submission.
- **Updated test coverage** to validate activate() and deactivate() model methods.

---

## [v1.1.0] - 2025-01-17
### Enhancements
- **Upgraded to version `1.1.0`** with internal improvements and stability fixes.

---

## [v1.0.0] - 2025-01-17
### Initial Release
- **Launched `Thefeqy/laravel-model-status` v1.0.0** – prepare Laravel Model Status package for first stable release.

---

### Notes
- For detailed usage, refer to the [README.md](README.md).
- Found an issue? Report it on [GitHub Issues](https://github.com/thefeqy/laravel-model-status/issues).
