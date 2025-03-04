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

        if (! $isAdmin) {
            static::addGlobalScope('active', function (Builder $builder) {
                $builder->where(
                    Config::get('model-status.column_name', 'status'),
                    Status::active()
                );
            });
        }
    }

    /**
     * Scope a query to only include active models.
     *
     * @deprecated Use the `active` scope instead. This method will be removed in version 2.0.0.
     */
    public static function scopeWithActive(Builder $query): Builder
    {
        return $query->where(
            Config::get('model-status.column_name', 'status'),
            Status::active()
        );
    }

    /**
     * Scope a query to only include active models.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where(
            Config::get('model-status.column_name', 'status'),
            Status::active()
        );
    }

    /**
     * Scope a query to only include inactive models.
     */
    public function scopeInActive(Builder $query): Builder
    {
        return $query->where(
            Config::get('model-status.column_name', 'status'),
            Status::inactive()
        );
    }

    /**
     * Scope a query to only include active models.
     */
    public function scopeWithoutActive(Builder $query): Builder
    {
        return $query->withoutGlobalScope('active');
    }

    /**
     * Initialize the trait by adding the status column to the fillable array.
     */
    public function initializeHasActiveScope(): void
    {
        if (! property_exists($this, 'fillable')) {
            $this->fillable = [];
        }

        $columnName = Config::get('model-status.column_name', 'status');
        if (! in_array($columnName, $this->fillable)) {
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

        $this->save();

        $this->cascadeDeactivation();

        return true;
    }

    /**
     * Deactivate related models.
     */
    protected function cascadeDeactivation(): void
    {
        foreach ($this->getCascadeRelations() as $relation) {
            if (method_exists($this, $relation)) {
                $this->{$relation}()->update([
                    Config::get('model-status.column_name', 'status') => Status::inactive(),
                ]);
            }
        }
    }

    /**
     * Define which relationships should be deactivated when this model is deactivated.
     */
    protected function getCascadeRelations(): array
    {
        return property_exists($this, 'cascadeDeactivate') ? $this->cascadeDeactivate : [];
    }
}
