<?php

namespace App\Repositories;

use App\DTO\Module\ModuleFilterData;
use App\Models\Module;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Repository for module data operations.
 */
class ModuleRepository
{
    /**
     * Create a new module with the given attributes.
     * 
     * @param array $attributes Module attributes to create
     * @return Module The newly created module
     */
    public function create(array $attributes): Module
    {
        return Module::create($attributes);
    }

    /**
     * Update an existing module with new attributes.
     * 
     * @param Module $module The module to update
     * @param array $attributes New attributes to apply
     * @return Module The updated module with fresh data
     */
    public function update(Module $module, array $attributes): Module
    {
        $module->update($attributes);
        return $module->fresh();
    }

    /**
     * Get paginated modules based on filter criteria.
     * 
     * @param ModuleFilterData $filters Filter and pagination parameters
     * @return LengthAwarePaginator Paginated module results
     */
    public function getPaginatedModules(ModuleFilterData $filters): LengthAwarePaginator
    {
        return Module::query()
            ->forCompany($filters->companyId)
            ->withStatus($filters->status)
            ->search($filters->search)
            ->orderByField($filters->orderBy, $filters->order)
            ->paginate($filters->perPage);
    }

    /**
     * Get module counts grouped by status for a company.
     * 
     * @param string $companyId The company ID to get counts for
     * @return array Associative array with status counts (all, draft, published, deleted)
     */
    public function getStatusCounts(string $companyId): array
    {
        return [
            'all' => Module::forCompany($companyId)->withStatus('all')->count(),
            'draft' => Module::forCompany($companyId)->withStatus('draft')->count(),
            'published' => Module::forCompany($companyId)->withStatus('published')->count(),
            'deleted' => Module::forCompany($companyId)->withStatus('deleted')->count(),
        ];
    }
}
