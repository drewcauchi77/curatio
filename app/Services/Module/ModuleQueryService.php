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
     * @param ModuleRepository $repository Repository for module data access
     */
    function __construct(
        private readonly ModuleRepository $repository
    ) {}

    /**
     * Get filtered modules with status counts and pagination.
     * 
     * @param ModuleFilterData $filters Filter criteria for modules
     * @return array{
     *      modules: mixed, 
     *      counts: array<string, int>, 
     *      filters: ModuleFilterData
     * } Contains modules (paginated), counts (by status), and applied filters
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
     * @param ModuleFilterData $filters Filter criteria for modules
     * @param string $modal Modal name then handled in Vue
     * @return array{
     *      modules: mixed, 
     *      counts: array<string, int>, 
     *      filters: ModuleFilterData,
     *      modal: string
     * } Contains modules (paginated), counts (by status), applied filters and modal to be opened.
     */
    public function getFilteredModulesWithModal(ModuleFilterData $filters, string $modal): array
    {
        $data = array_merge($this->getFilteredModules($filters), [
            'modal' => $modal
        ]);

        return $data;
    }

    public function shouldRedirectToFirstPage(ModuleFilterData $filterData, LengthAwarePaginator $paginator): bool
    {
        $requestedPage = $filterData->page ?? 1;
        $lastPage = $paginator->lastPage();

        return $requestedPage > $lastPage && $lastPage > 0;
    }
}
