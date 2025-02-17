<?php

namespace Thefeqy\ModelStatus;

use Illuminate\Support\Facades\Config;
use UnexpectedValueException;

class Status
{
    public function __construct(public readonly string $value)
    {
        // Validate the value before setting it
        if (! in_array($value, self::allowedStatuses())) {
            throw new UnexpectedValueException("Invalid status value: '$value'. Allowed values: ".implode(', ', self::allowedStatuses()));
        }
    }

    /**
     * Get the allowed statuses from the config.
     */
    public static function allowedStatuses(): array
    {
        return [
            self::active(),
            self::inactive(),
        ];
    }

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
     * Check if this status instance is active.
     */
    public function isActive(): bool
    {
        return $this->value === self::active();
    }

    /**
     * Check if this status instance is inactive.
     */
    public function isInactive(): bool
    {
        return $this->value === self::inactive();
    }

    /**
     * Get the raw status value.
     */
    public function value(): string
    {
        return $this->value;
    }
}
