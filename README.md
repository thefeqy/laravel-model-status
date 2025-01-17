# Laravel Model Status

[![Latest Version on Packagist](https://img.shields.io/packagist/v/thefeqy/laravel-model-status.svg?style=flat-square)](https://packagist.org/packages/thefeqy/laravel-model-status)
[![Total Downloads](https://img.shields.io/packagist/dt/thefeqy/laravel-model-status.svg?style=flat-square)](https://packagist.org/packages/thefeqy/laravel-model-status)

A Laravel package to automate adding configurable status columns to models and migrations. This package provides an easy-to-use `HasActiveScope` trait, a `Status` enum, middleware for ensuring active users, and a custom Artisan command to streamline your workflow.

---

## Features

- Add a configurable `status` column to models and migrations.
- Automatically handle global scopes for active statuses.
- Enforce user activity with the `EnsureAuthenticatedUserIsActive` middleware.
- Support dynamic configuration for column name, default value, and length.
- Includes a `make:model-status` command.

---

## Installation

You can install the package via Composer:

```bash
    composer require thefeqy/laravel-model-status
```

Optionally, you can publish the configuration file:

```bash
    php artisan vendor:publish --tag=config
```


## Usage

### Using the Trait
To use the HasActiveScope trait, include it in your model:

```php
use Thefeqy\ModelStatus\Traits\HasActiveScope;

class ExampleModel extends Model
{
    use HasActiveScope;

    protected $fillable = ['name'];
}
```

## Creating Models with `status`
You can use the custom Artisan command to create models and migrations with a `status` column automatically:

```bash
    php artisan make:model-status Example
```

This will:

- Create a model named `Example` and automatically use `HaveActiveScope`.
- Add a `status` column to the generated migration file with the configuration values.

## Querying Models
Retrieve Active Models (Default Behavior)

```php
    $activeModels = ExampleModel::all();
```

Include Inactive Models
```php
    $allModels = ExampleModel::withoutActive()->get();
```

## Middleware Usage
The package includes the `EnsureAuthenticatedUserIsActive` middleware, which enforces that only users with an active status can access certain routes.

### Add Middleware to Routes
Instead of registering a string alias for the middleware, you can reference it by class name in your route definition:

```php
    use Illuminate\Support\Facades\Route;
    use Thefeqy\ModelStatus\Middleware\EnsureAuthenticatedUserIsActive;

    Route::middleware(['auth', EnsureAuthenticatedUserIsActive::class])->group(function () {
        Route::get('/dashboard', function () {
            return 'Welcome to your dashboard!';
        });
    });

    Route::get('/login', function () {
        return 'Login page';
    })->name('login');
```

### Behavior

- If the authenticated user's status is not active:
    - The user will be logged out.
    - A `403 Forbidden` response will be returned with the message:

```
    This account is suspended. Please contact the administrator.
```
---

## Example Workflow

1- Use the `make:model-status` command to create a model with a `status` column and include the `HasActiveScope` trait:

```php
    php artisan make:model-status Company
```

2- Update the database schema to include a `status` column in the `companies` table (if not already present):

```php 
    php artisan migrate
```

3. Protect your routes using the middleware to ensure only active users can access them:

```php

    use App\Models\Company;
    use Thefeqy\ModelStatus\Middleware\EnsureAuthenticatedUserIsActive;

    Route::middleware(['auth', EnsureAuthenticatedUserIsActive::class])->group(function () {
        Route::get('/companies', function () {
            return view('companies', ['companies' => Company::get()]);
        });
    });
```

4. Test the functionality:

- try to access the `companies` route to make sure that only active companies are included in the response

## Configuration
Edit the `config/model-status.php` file to customize the column name, default value, and length:

```php
    return [
        'column_name' => 'status',
        'default_value' => 'active',
        'column_length' => 10,
    ];
```

## Example Configuration Use Case

- If you change the `column_name` to `state` in the config, the migration will automatically generate:

```php
    $table->string('state', 10)->default('active');
```

- The global scope and the `HasActiveScope` trait will also dynamically adapt to use `state` instead of `status`.

## Contributing
Contributions are welcome! [https://github.com/thefeqy/laravel-model-status](https://github.com/thefeqy/laravel-model-status) Please follow these steps:

1. Fork the repository.
2. Create a new branch:
   ```bash
   git checkout -b feature/your-feature
   ```
3. Commit your changes:
   ```bash
   git commit -m "Add your feature"
   ```
4. Push to the branch:
   ```bash
   git push origin feature/your-feature
   ```
5. Open a pull request.
---
 
## License 
The MIT License (MIT). Please see [License File](LICENSE) for more information.
