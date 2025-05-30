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

/**
 * Controller for managing module resources.
 * 
 * Handles CRUD operations for modules including listing, creating, viewing and updating modules within a company context. 
 * All actions are authorized through Laravel's resource authorization.
 */
class ModuleController extends Controller
{
    /**
     * Create a new ModuleController instance.
     * 
     * Sets up dependency injection for module services and configures
     * resource-based authorization for all Module model operations.
     *
     * @param   ModuleService $moduleService -> Service for module business logic operations
     * @param   ModuleQueryService $queryService -> Service for module data retrieval and filtering
     */
    function __construct(
        private readonly ModuleService $moduleService,
        private readonly ModuleQueryService $queryService
    ) {
        $this->authorizeResource(Module::class, 'module');
    }

    /**
     * Display a paginated listing of modules with filtering and search capabilities.
     * Processes the request parameters to create filter criteria and returns a paginated list of modules. Supports search, ordering, and status filtering.
     *
     * @param   IndexModuleRequest $request -> Validated request containing filter parameters
     * @return  RedirectResponse|InertiaResponse -> Inertia response with module data or redirect on error
     * 
     * @throws  \Illuminate\Auth\Access\AuthorizationException -> If user cannot view modules
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
     * Show the form for creating a new module.
     * Renders the module creation form using Inertia.js. 
     * Authorization is handled automatically through the resource authorization setup.
     * 
     * @return  InertiaResponse -> The create module form page
     * 
     * @throws  \Illuminate\Auth\Access\AuthorizationException -> If user cannot create modules
     */
    public function create(): InertiaResponse
    {
        return Inertia::render('modules/Create');
    }

    /**
     * Store a newly created module in the database.
     * Creates a new module with the validated data from the request.
     * Performs additional authorization check with the specific attributes before delegating to the module service for creation.
     *
     * @param   StoreModuleRequest $request -> Validated request containing module data
     * @return  RedirectResponse -> Redirect to the newly created module's show page
     * 
     * @throws  \Illuminate\Auth\Access\AuthorizationException -> If user cannot create module with given attributes
     * @throws  \Illuminate\Database\QueryException -> If database operation fails
     */
    public function store(StoreModuleRequest $request): RedirectResponse
    {
        $attributes = [
            'company_id' => $request->user()->company_id,
            ...$request->safe()->only(['title', 'description', 'status_id']),
        ];

        $this->authorize('store', [Module::class, $attributes]);
        $module = $this->moduleService->createModule($attributes);

        return redirect()->route('modules.show', [
            'module' => $module,
        ]);
    }

    /**
     * Display the specified module.
     * Shows the detailed view of a single module including its related status information. 
     * The module is automatically resolved through route model binding.
     *
     * @param   Module $module -> The module instance resolved from route binding
     * @return  InertiaResponse -> The module detail page
     * 
     * @throws  \Illuminate\Auth\Access\AuthorizationException -> If user cannot view this module
     * @throws  \Illuminate\Database\Eloquent\ModelNotFoundException -> If module not found
     */
    public function show(Module $module): InertiaResponse
    {
        $module->load('status');

        return Inertia::render('modules/Show', [
            'module' => $module,
        ]);
    }

    /**
     * Update the specified module in the database.
     * Updates an existing module with the validated data from the request.
     * Returns a redirect with success notification after successful update.
     *
     * @param   UpdateModuleRequest $request -> Validated request containing updated module data
     * @param   Module $module -> The module instance to update (resolved from route binding)
     * @return  RedirectResponse -> Redirect to module show page with success message
     * 
     * @throws  \Illuminate\Auth\Access\AuthorizationException -> If user cannot update this module
     * @throws  \Illuminate\Database\QueryException -> If database operation fails
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
