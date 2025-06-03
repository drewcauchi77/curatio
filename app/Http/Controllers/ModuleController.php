<?php

namespace App\Http\Controllers;

use App\Actions\Module\CreateModule;
use App\Actions\Module\UpdateModule;
use App\DTO\Module\ModuleData;
use App\DTO\Module\ModuleFilterData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Module\IndexModuleRequest;
use App\Http\Requests\Module\StoreModuleRequest;
use App\Http\Requests\Module\UpdateModuleRequest;
use App\Models\Module;
use App\Services\Module\ModuleQueryService;
use Inertia\Inertia;
use Illuminate\Http\RedirectResponse;
use Inertia\Response as InertiaResponse;

/**
 * Manages module CRUD operations with resource-based authorization.
 */
class ModuleController extends Controller
{
    /**
     * Initialize controller with query service and resource authorization.
     * 
     * @param ModuleQueryService $queryService Service for module data retrieval
     */
    function __construct(
        private readonly ModuleQueryService $queryService,
    ) {
        $this->authorizeResource(Module::class, 'module');
    }

    /**
     * Display paginated modules with filtering and search.
     *
     * @param IndexModuleRequest $request Validated filter parameters
     * @return RedirectResponse|InertiaResponse Rendered module list or redirect
     */
    public function index(IndexModuleRequest $request): RedirectResponse | InertiaResponse
    {
        $filterData = ModuleFilterData::fromRequest($request);
        $result = $this->queryService->getFilteredModules($filterData);

        if ($result['modules']->currentPage() > $result['modules']->lastPage() && $result['modules']->lastPage() > 0) {
            return $this->redirectToFirstPage($request);
        }

        return Inertia::render('modules/Modules', $result);
    }

    /**
     * Show module creation form.
     *
     * @return InertiaResponse Rendered create form
     */
    public function create(): InertiaResponse
    {
        return Inertia::render('modules/Create');
    }

    /**
     * Store a newly created module.
     *
     * @param CreateModule $action Action to handle module creation
     * @param StoreModuleRequest $request Validated module data
     * @return RedirectResponse Redirect to created module with success message
     */
    public function store(CreateModule $action, StoreModuleRequest $request): RedirectResponse
    {
        $moduleData = ModuleData::fromStoreRequest($request);
        $this->authorize('store', [Module::class, $moduleData->toArray()]);
        $module = $action->handle($moduleData);

        return redirect()->route('modules.show', ['module' => $module])
            ->with([
                'type' => 'success',
                'title' => 'success.success',
                'message' => 'success.module.create-success',
            ]);
    }

    /**
     * Display module details with status relationship.
     *
     * @param Module $module The module to display
     * @return InertiaResponse Rendered module detail view
     */
    public function show(Module $module): InertiaResponse
    {
        $module->load('status');

        return Inertia::render('modules/Show', [
            'module' => $module,
        ]);
    }

    /**
     * Update an existing module.
     *
     * @param UpdateModule $action Action to handle module update
     * @param UpdateModuleRequest $request Validated update data
     * @param Module $module The module to update
     * @return RedirectResponse Redirect to updated module with success message
     */
    public function update(UpdateModule $action, UpdateModuleRequest $request, Module $module): RedirectResponse
    {
        $moduleData = ModuleData::fromUpdateRequest($request);
        $module = $action->handle($module, $moduleData);

        return redirect()
            ->route('modules.show', ['module' => $module])
            ->with([
                'type' => 'success',
                'title' => 'success.success',
                'message' => 'success.module.update-success',
            ]);
    }

    private function redirectToFirstPage(IndexModuleRequest $request): RedirectResponse
    {
        $params = $request->except('page');

        return redirect()->route('modules.index', $params)
            ->with([
                'type' => 'error',
                'title' => 'errors.pagination.not-available.title',
                'message' => 'errors.pagination.not-available.description'
            ]);
    }
}
