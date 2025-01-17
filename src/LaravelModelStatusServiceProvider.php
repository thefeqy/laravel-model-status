<?php

namespace Thefeqy\ModelStatus;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Thefeqy\ModelStatus\Commands\MakeModelWithStatus;

class LaravelModelStatusServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Publish configuration
        $this->publishes([
            __DIR__ . '/../config/model-status.php' => config_path('model-status.php'),
        ], 'config');

        // Register middleware
        Route::aliasMiddleware('active', \Thefeqy\ModelStatus\Middleware\EnsureAuthenticatedUserIsActive::class);
    }

    public function register(): void
    {
        // Merge the package's default config. Published config will override it at runtime.
        $this->mergeConfigFrom(
            __DIR__ . '/../config/model-status.php',
            'model-status'
        );

        // Register commands
        $this->commands([
            MakeModelWithStatus::class,
        ]);
    }
}
