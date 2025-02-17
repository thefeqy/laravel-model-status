<?php

namespace Thefeqy\ModelStatus\Enums;

use Illuminate\Support\Facades\Config;

enum Status: string
{
   #[\Deprecated]
    case ACTIVE = 'active';
    #[\Deprecated]
    case INACTIVE = 'inactive';

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

    /**
     * Check if this string value matches the active status.
     */
    public function isActive(): bool
    {
        return $this->value === self::active();
    }

    /**
     * Check if this string value matches the inactive status.
     */
    public function isInactive(): bool
    {
        return $this->value === self::inactive();
    }
}
