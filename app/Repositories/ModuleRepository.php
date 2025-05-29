<?php

namespace App\Repositories;

use App\DTO\Module\ModuleFilterData;
use App\Models\Module;
use Illuminate\Pagination\LengthAwarePaginator;

class ModuleRepository
{
    public function getPaginatedModules(ModuleFilterData $filters): LengthAwarePaginator
    {
        return Module::query()
            ->forCompany($filters->companyId)
            ->withStatus($filters->status)
            ->search($filters->search)
            ->orderByField($filters->orderBy, $filters->order)
            ->paginate($filters->perPage);
    }

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
