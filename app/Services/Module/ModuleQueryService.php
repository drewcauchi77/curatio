<?php

namespace App\Services\Module;

use App\DTO\Module\ModuleFilterData;
use App\Repositories\ModuleRepository;

class ModuleQueryService
{
    function __construct(
        private readonly ModuleRepository $repository
    ) {}

    public function getFilteredModules(ModuleFilterData $filters): array
    {
        $counts = $this->repository->getStatusCounts($filters->companyId);
        $paginator = $this->repository->getPaginatedModules($filters);

        return [
            'modules' => $paginator,
            'counts' => $counts,
            'filters' => $filters
        ];
    }
}
