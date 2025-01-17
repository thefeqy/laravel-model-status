<?php

namespace Thefeqy\ModelStatus\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

trait HasActiveScope
{
    public static function bootHasActiveScope(): void
    {
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where(
                Config::get('model-status.column_name', 'status'),
                Config::get('model-status.default_value', 'active')
            );
        });
    }

    public function scopeWithoutActive(Builder $query): Builder
    {
        return $query->withoutGlobalScope('active');
    }

    public function initializeHasActiveScope(): void
    {
        if (!property_exists($this, 'fillable')) {
            $this->fillable = [];
        }

        $columnName = Config::get('model-status.column_name', 'status');
        if (!in_array($columnName, $this->fillable)) {
            $this->fillable[] = $columnName;
        }
    }
}
