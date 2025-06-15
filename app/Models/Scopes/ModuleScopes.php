<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;

/**
 * Query scopes for Module model.
 */
trait ModuleScopes
{
    /**
     * Query for modules on company ID.
     * 
     * @param Builder<\App\Models\Module> $query
     * @param string $companyId
     * @return Builder<\App\Models\Module>
     */
    public function scopeForCompany(Builder $query, string $companyId): Builder
    {
        return $query->where('company_id', $companyId);
    }

    /**
     * Query for modules on the status.
     * 
     * @param Builder<\App\Models\Module> $query
     * @param string $status
     * @return Builder<\App\Models\Module>
     */
    public function scopeWithStatus(Builder $query, string $status): Builder
    {
        $statuses = $status == 'all' ? ['draft', 'published'] : [$status];
        return $query->whereHas('status', fn($q) => $q->whereIn('status', $statuses));
    }

    /**
     * Query for modules for the search functionality through title.
     * 
     * @param Builder<\App\Models\Module> $query
     * @param string|null $search
     * @return Builder<\App\Models\Module>
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        return $query->when($search, fn($q) => $q->where('title', 'like', "%{$search}%"));
    }

    /**
     * Query for modules ordered by field.
     * 
     * @param Builder<\App\Models\Module> $query
     * @param string $orderBy
     * @param string $order
     * @return Builder<\App\Models\Module>
     */
    public function scopeOrderByField(Builder $query, string $orderBy, string $order): Builder
    {
        return $query->when(
            in_array($order, ['asc', 'desc']),
            fn($q) => $orderBy === 'title' ? $q->orderByRaw('LOWER(title) ' . $order) : $q->orderBy($orderBy, $order)
        );
    }
}
