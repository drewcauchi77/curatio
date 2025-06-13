<?php

namespace App\Services\Module;

use App\DTO\Module\ModuleFilterData;
use App\Repositories\ModuleRepository;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Service for querying and retrieving module data.
 */
class ModuleQueryService
{
    /**
     * Initialize the service with repository dependency.
     * 
     * @param   ModuleRepository $repository
     */
    function __construct(
        private readonly ModuleRepository $repository
    ) {}

    /**
     * Get filtered modules with status counts and pagination.
     * 
     * @param   ModuleFilterData $filters
     * @return  array{ modules: mixed, counts: array<string, int>, filters: ModuleFilterData }
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

    /**
     * Get filtered modules through getFilteredModules() with a modal to be opened.
     * 
     * @param   ModuleFilterData $filters
     * @param   string $modal
     * @return  array{ modules: mixed, counts: array<string, int>, filters: ModuleFilterData, modal: string }
     */
    public function getFilteredModulesWithModal(ModuleFilterData $filters, string $modal): array
    {
        $data = array_merge($this->getFilteredModules($filters), [
            'modal' => $modal
        ]);

        return $data;
    }
}
