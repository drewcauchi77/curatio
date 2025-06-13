<?php

namespace App\Http\Controllers;

use App\Actions\Module\CreateModule;
use App\Actions\Module\UpdateModule;
use App\DTO\Module\ModuleData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Module\IndexModuleRequest;
use App\Http\Requests\Module\StoreModuleRequest;
use App\Http\Requests\Module\UpdateModuleRequest;
use App\Models\Module;
use App\Services\Module\ModuleQueryService;
use App\Traits\Module\HandlesModuleListing;
use Inertia\Inertia;
use Illuminate\Http\RedirectResponse;
use Inertia\Response as InertiaResponse;

/**
 * Manages module CRUD operations with resource-based authorization.
 */
class ModuleController extends Controller
{
    use HandlesModuleListing;

    /**
     * Initialize controller with query service and resource authorization.
     * 
     * @param   ModuleQueryService $queryService
     */
    function __construct(
        private readonly ModuleQueryService $queryService,
    ) {
        $this->authorizeResource(Module::class, 'module');
    }

    /**
     * Display paginated modules with filtering and search.
     *
     * @param   IndexModuleRequest $request
     * @return  RedirectResponse|InertiaResponse
     */
    public function index(IndexModuleRequest $request): RedirectResponse | InertiaResponse
    {
        return $this->renderModulesList($request);
    }

    /**
     * Show module creation form.
     *
     * @return  InertiaResponse
     */
    public function create(): InertiaResponse
    {
        return Inertia::render('modules/CreateModulePage');
    }

    /**
     * Store a newly created module.
     *
     * @param   CreateModule $action
     * @param   StoreModuleRequest $request
     * @return  RedirectResponse
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
     * @param   Module $module
     * @return  InertiaResponse
     */
    public function show(Module $module): InertiaResponse
    {
        $module->load('status');

        return Inertia::render('modules/ShowModulePage', [
            'module' => $module,
        ]);
    }

    /**
     * Update an existing module.
     *
     * @param   UpdateModule $action
     * @param   UpdateModuleRequest $request
     * @param   Module $module
     * @return  RedirectResponse
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
}
