<?php

namespace App\Services\Module;

use App\DTO\Module\ModuleFilterData;
use App\Repositories\ModuleRepository;

/**
 * Service for querying and retrieving module data.
 */
class ModuleQueryService
{
    /**
     * Initialize the service with repository dependency.
     * 
     * @param ModuleRepository $repository Repository for module data access
     */
    function __construct(
        private readonly ModuleRepository $repository
    ) {}

    /**
     * Get filtered modules with status counts and pagination.
     * 
     * @param ModuleFilterData $filters Filter criteria for modules
     * @return array Contains modules (paginated), counts (by status), and applied filters
     */
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
