<?php

namespace Thefeqy\ModelStatus\Enums;

use Illuminate\Support\Facades\Config;

enum Status: string
{
    /**
     * Get the actual active status value from the config.
     */
    public static function active(): string
    {
        return Config::get('model-status.default_value', 'active');
    }

    /**
     * Get the actual inactive status value from the config.
     */
    public static function inactive(): string
    {
        return Config::get('model-status.inactive_value', 'inactive');
    }
}
