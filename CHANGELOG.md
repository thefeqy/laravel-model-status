# Changelog

All notable changes to `Thefeqy/laravel-model-status` will be documented in this file.
This project follows [Semantic Versioning](https://semver.org/).


---

## v1.5.0 - 2025-03-04

#### New Features

- **Status Casting (`StatusCast`)**
  
  - The `status` field is now automatically cast to a `Status` object.
  - Allows method calls like `$model->status->isActive()` and `$model->status->isInactive()`.
  - Ensures better type safety when working with statuses.
  
- **Cascade Deactivation**
  
  - When a model is deactivated, related models can also be automatically deactivated.
  - Introduced `$cascadeDeactivate` property to define related models that should also be deactivated.
  - Example:
    ```php
    class Category extends Model
    {
        use HasActiveScope;
    
        protected array $cascadeDeactivate = ['products'];
    
        public function products()
        {
            return $this->hasMany(Product::class);
        }
    }
    
    ```
  - Calling `$category->deactivate();` will also deactivate all associated products.
  

#### Upgrade Guide

To update to v1.5.0, run:

```bash
composer update thefeqy/laravel-model-status

```
**Full Changelog**: https://github.com/thefeqy/laravel-model-status/compare/v1.4.0...v1.5.0

## [v1.4.0] - 2025-03-01

### Enhancements

- **Added `active()` scope to retrieve only active models**
- **Added `inActive()` scope to retrieve only inactive models**
- **Deprecated `withActive()` scope and replace it with `active()` scope directly to maintain naming convention**


---

## [v1.3.0] - 2025-02-26

- **Added support for Laravel 12**
- **Added support for PHP 8.4**
- **Drop support for Laravel 10**


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
