<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Module\IndexModuleRequest;
use App\Http\Requests\Module\StoreModuleRequest;
use App\Http\Requests\Module\UpdateModuleRequest;
use App\Models\Module;
use App\Services\ModuleService;
use Inertia\Inertia;
use Illuminate\Http\RedirectResponse;
use Inertia\Response as InertiaResponse;

class ModuleController extends Controller
{
    /**
     * @brief   The module service instance.
     */
    protected ModuleService $moduleService;

    /**
     * @brief   Constructor to define the resource access (viewing).
     *          Injection of ModuleService dependency.
     */
    public function __construct(ModuleService $moduleService)
    {
        $this->authorizeResource(Module::class, 'module');
        $this->moduleService = $moduleService;
    }

    /**
     * @brief   Query for the modules with search and ordering.
     *
     * @param   \App\Http\Requests\Module\IndexModuleRequest $request
     *
     * @return  \Illuminate\Http\RedirectResponse|\Inertia\Response
     */
    public function index(IndexModuleRequest $request): RedirectResponse | InertiaResponse
    {
        $companyId = $request->user()->company_id;

        $validated = $request->validated();

        $search   = $validated['q']        ?? '';
        $order    = $validated['order']    ?? 'asc';
        $orderBy  = $validated['orderBy']  ?? 'created_at';
        $status   = $validated['status']   ?? 'all';
        $perPage  = 12;

        $result = $this->moduleService->listModules(
            $companyId,
            $search,
            $orderBy,
            $order,
            $status,
            $perPage
        );

        $paginator = $result['paginator'];

        if ($request->page && $paginator->lastPage() > 0 && $request->page > $paginator->lastPage()) {
            return redirect()->route('modules.index', array_merge(
                $request->except('page'),
                ['page' => 1]
            ))->with([
                'success'   => false,
                'message'   => 'general.page-not-available'
            ]);
        }

        return Inertia::render('modules/Modules', [
            'modules' => $paginator,
            'q'       => $search,
            'order'   => $order,
            'orderBy' => $orderBy,
            'status'  => $status,
            'counts'  => $result['counts']
        ]);
    }

    /**
     * @brief   Show the form to create a new module.
     * 
     * @return  \Inertia\Response
     */
    public function create(): InertiaResponse
    {
        return Inertia::render('modules/Create');
    }

    /**
     * @brief   Action to create a new module.
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
     * @brief   Show the module page.
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
     * @brief   Update an existing module.
     * 
     * @param   \App\Http\Requests\Module\UpdateModuleRequest $request
     * @param   \App\Models\Module $module
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
