<?php

namespace App\Http\Controllers;

use App\DTO\Module\ModuleFilterData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Module\IndexModuleRequest;
use App\Services\Module\ModuleQueryService;
use App\Services\YoutubeChannel\YoutubeAuthService;
use App\Traits\Module\HandlesModuleListing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

/**
 * Manages youtube channel CRUD operations.
 */
class YoutubeChannelController extends Controller
{
    use HandlesModuleListing;

    /**
     * Constructor to define the dependency injection.
     * 
     * @param   ModuleQueryService $queryService
     * @param   YoutubeAuthService $authService
     */
    function __construct(
        private readonly ModuleQueryService $queryService,
        private readonly YoutubeAuthService $authService,
    ) {}

    /**
     * Query for the modules with search,ordering and can send props for modal opening.
     *
     * @param   IndexModuleRequest $request
     * @return  RedirectResponse|Response
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

        $youtubeConnection = $this->authService->getConnectionStatus($request->user());

        return $this->renderModulesList($request, [
            'connection' => $youtubeConnection
        ], 'VideoGenerateModal');
    }

    /**
     * Disconnect the Google API token based on the user details.
     *
     * @param   Request $request
     * @return  RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        $this->authService->disconnect($request->user());
        return redirect()->route('modules.youtube.index');
    }
}
