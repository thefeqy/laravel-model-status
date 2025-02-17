<?php

namespace Thefeqy\ModelStatus\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Thefeqy\ModelStatus\LaravelModelStatusServiceProvider;

abstract class TestCase extends BaseTestCase
{
    /**
     * Load package service provider.
     */
    protected function getPackageProviders($app)
    {
        return [
            LaravelModelStatusServiceProvider::class,
        ];
    }

    /**
     * Set up test database.
     */
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $this->artisan('migrate');
    }
}
