<?php

namespace App\Http\Controllers;

use App\DTO\Module\ModuleFilterData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Module\IndexModuleRequest;
use App\Http\Requests\Module\StoreModuleRequest;
use App\Http\Requests\Module\UpdateModuleRequest;
use App\Models\Module;
use App\Services\Module\ModuleQueryService;
use App\Services\Module\ModuleService;
use Inertia\Inertia;
use Illuminate\Http\RedirectResponse;
use Inertia\Response as InertiaResponse;

class ModuleController extends Controller
{
    /**
     * Constructor to define the resource access & dependency injection.
     */
    function __construct(
        private readonly ModuleService $moduleService,
        private readonly ModuleQueryService $queryService
    ) {
        $this->authorizeResource(Module::class, 'module');
    }

    /**
     * Query for the modules with search and ordering.
     *
     * @param   \App\Http\Requests\Module\IndexModuleRequest $request
     *
     * @return  \Illuminate\Http\RedirectResponse|\Inertia\Response
     */
    public function index(IndexModuleRequest $request): RedirectResponse | InertiaResponse
    {
        $filterData = ModuleFilterData::fromRequest($request);
        $result = $this->queryService->getFilteredModules($filterData);

        // TODO: Redirect to first page on error
        // if ($this->shouldRedirectToFirstPage($request, $result['paginator'])) {
        //     return $this->redirectToFirstPage($request);
        // }

        return Inertia::render('modules/Modules', $result);
    }

    /**
     * Show the form to create a new module.
     * 
     * @return  \Inertia\Response
     */
    public function create(): InertiaResponse
    {
        return Inertia::render('modules/Create');
    }

    /**
     * Action to create a new module.
     * 
     * @param   \App\Http\Requests\Module\StoreModuleRequest $request
     * 
     * @return  \Illuminate\Http\RedirectResponse
     */
    public function store(StoreModuleRequest $request): RedirectResponse
    {
        // Create attributes to pass to authorization in policy.
        $attributes = [
            'company_id' => $request->user()->company_id,
            ...$request->safe()->only(['title', 'description', 'status_id']),
        ];

        // Authorize the store action with the attributes.
        $this->authorize('store', [Module::class, $attributes]);

        // Calling the creation service through the dependency injection.
        $module = $this->moduleService->createModule($attributes);

        return redirect()->route('modules.show', [
            'module' => $module,
        ]);
    }

    /**
     * Show the module page.
     * 
     * @param   \App\Models\Module $module
     * 
     * @return  \Inertia\Response
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
     * @param   \App\Http\Requests\Module\UpdateModuleRequest $request
     * @param   \App\Models\Module $module
     * 
     * @return  \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateModuleRequest $request, Module $module): RedirectResponse
    {
        // Calling the update service through the dependency injection.
        $module = $this->moduleService->updateModule($module, $request->validated());

        return redirect()->route('modules.show', [
            'module' => $module
        ])->with([
            'success'   => true,
            'message'   => 'module.update-success'
        ]);
    }
}
