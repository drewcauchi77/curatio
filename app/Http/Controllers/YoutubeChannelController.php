<?php

namespace App\Http\Controllers;

use App\DTO\Module\ModuleFilterData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Module\IndexModuleRequest;
use App\Services\Module\ModuleQueryService;
use App\Services\YoutubeChannel\YoutubeAuthService;
use App\Traits\Module\HandlesModulePageRedirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class YoutubeChannelController extends Controller
{
    use HandlesModulePageRedirect;

    /**
     * Constructor to define the dependency injection.
     */
    function __construct(
        private readonly ModuleQueryService $queryService,
        private readonly YoutubeAuthService $authService,
    ) {}

    /**
     * Query for the modules with search and ordering + sending props for modal opening.
     *
     * @param   \App\Http\Requests\Module\IndexModuleRequest $request
     * @return  \Illuminate\Http\RedirectResponse|\Inertia\Response
     */
    public function index(IndexModuleRequest $request): RedirectResponse | InertiaResponse
    {
        if ($request->has('code')) {
            try {
                $this->authService->handleAuthCallback($request->input('code'), $request->user());
                // Where we get to here? TODO
                return redirect()->route('modules.index');
            } catch (\Exception $e) {
                // Check errors here? TODO
                return redirect()->route('modules.index')->withErrors(['youtube' => 'Authentication failed']);
            }
        }

        $filterData = ModuleFilterData::fromRequest($request);
        $result = $this->queryService->getFilteredModulesWithModal($filterData, 'VideoGenerateModal');
        $youtubeConnection = $this->authService->getConnectionStatus($request->user());

        if ($result['modules']->currentPage() > $result['modules']->lastPage() && $result['modules']->lastPage() > 0) {
            return $this->redirectToFirstPage($request, 'modules.index');
        }

        return Inertia::render('modules/ListModulesPage', array_merge($result, [
            'connection' => $youtubeConnection
        ]));
    }

    public function connect(): RedirectResponse
    {
        $authUrl = $this->authService->getAuthUrl();
        return redirect($authUrl);
    }

    public function destroy(Request $request): RedirectResponse
    {
        $this->authService->disconnect($request->user());
        return redirect()->route('modules.index');
    }
}
