<?php

namespace App\Models\Traits;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant(): void
    {
        static::addGlobalScope('tenant', function (Builder $query) {
            if (app()->has('currentTenant')) {
                $query->where($query->getModel()->getTable() . '.tenant_id', app('currentTenant')->id);
            }
        });

        static::creating(function ($model) {
            if (app()->has('currentTenant') && !$model->tenant_id) {
                $model->tenant_id = app('currentTenant')->id;
            }
        });
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
