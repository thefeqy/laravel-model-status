<?php

namespace Thefeqy\ModelStatus\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use Thefeqy\ModelStatus\Status;

trait HasActiveScope
{
    public static function bootHasActiveScope(): void
    {
        $isAdmin = false;
        $adminDetector = Config::get('model-status.admin_detector');

        if (is_callable($adminDetector)) {
            $isAdmin = call_user_func($adminDetector);
        }

        if (!$isAdmin) {
            static::addGlobalScope('active', function (Builder $builder) {
                $builder->where(
                    Config::get('model-status.column_name', 'status'),
                    Status::active()
                );
            });
        }
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

    /**
     * Activate the model by setting the status to the configured "active" value.
     *
     * @return $this
     */
    public function activate(): bool
    {
        $this->{Config::get('model-status.column_name', 'status')} = Status::active();
        return $this->save();
    }

    /**
     * Deactivate the model by setting the status to the configured "inactive" value.
     *
     * @return $this
     */
    public function deactivate(): bool
    {
        $this->{Config::get('model-status.column_name', 'status')} = Status::inactive();
        return $this->save();
    }
}
