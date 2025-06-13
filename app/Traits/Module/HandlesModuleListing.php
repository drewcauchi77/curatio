<?php

namespace App\Traits\Module;

use App\DTO\Module\ModuleFilterData;
use App\Http\Requests\Module\IndexModuleRequest;
use Inertia\Inertia;
use Illuminate\Http\RedirectResponse;
use Inertia\Response as InertiaResponse;
use Illuminate\Http\Request;

/**
 * Provides logic to handle reusable module list logic.
 */
trait HandlesModuleListing
{
    /**
     * Render a paginated modules list with optional filtering, search, and modal support.
     *
     * @param   IndexModuleRequest $request
     * @param   array<string, list<string>> $additionalViewData
     * @param   string|null $modalType
     * @return  RedirectResponse|InertiaResponse
     */
    protected function renderModulesList(IndexModuleRequest $request, array $additionalViewData = [], ?string $modalType = null): RedirectResponse | InertiaResponse
    {
        $filterData = ModuleFilterData::fromRequest($request);

        $result = $modalType
            ? $this->queryService->getFilteredModulesWithModal($filterData, $modalType)
            : $this->queryService->getFilteredModules($filterData);

        if ($result['modules']->currentPage() > $result['modules']->lastPage() && $result['modules']->lastPage() > 0) {
            return $this->redirectToFirstPage($request, 'modules.index');
        }

        return Inertia::render('modules/ListModulesPage', array_merge($result, $additionalViewData));
    }

    /**
     * Redirect to the first page of a paginated route when the requested page is invalid.
     *
     * @param   Request $request
     * @param   string $routeName
     * @param   array<string, list<string>> $additionalParams
     * @return  RedirectResponse
     */
    private function redirectToFirstPage(Request $request, string $routeName, array $additionalParams = []): RedirectResponse
    {
        $params = array_merge(
            $request->except('page'),
            $additionalParams
        );

        return redirect()->route($routeName, $params)
            ->with([
                'type' => 'error',
                'title' => 'errors.pagination.not-available.title',
                'message' => 'errors.pagination.not-available.description'
            ]);
    }
}
