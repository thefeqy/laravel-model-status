<?php

namespace Thefeqy\ModelStatus\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Thefeqy\ModelStatus\Status;
use UnexpectedValueException;

class StatusCast implements CastsAttributes
{
    /**
     * Cast the raw database value to a Status object.
     *
     * @param  mixed  $value
     * @return \Thefeqy\ModelStatus\Status
     */
    public function get($model, string $key, $value, array $attributes): Status
    {
        if (!is_string($value)) {
            throw new UnexpectedValueException("Invalid status value: expected string, got ".gettype($value));
        }

        return new Status($value);
    }

    /**
     * Convert the Status object back to a string for storage.
     *
     * @param  mixed  $value
     * @return string
     */
    public function set($model, string $key, $value, array $attributes): string
    {
        if ($value instanceof Status) {
            return $value->value();
        }

        if (is_string($value)) {
            return $value;
        }

        throw new UnexpectedValueException("Invalid status value: expected instance of Status or string.");
    }
}
