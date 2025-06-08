<?php

namespace App\Http\Controllers;

use App\DTO\Module\ModuleFilterData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Module\IndexModuleRequest;
use App\Services\Module\ModuleQueryService;
use App\Services\Youtube\YoutubeAuthService;
use App\Services\Youtube\YoutubeConnectionHandler;
use App\Services\Youtube\YoutubeDataService;
use App\Traits\Module\HandlesModulePageRedirect;
use Illuminate\Contracts\Session\Session as SessionSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class YoutubeController extends Controller
{
    use HandlesModulePageRedirect;

    /**
     * Constructor to define the dependency injection.
     */
    function __construct(
        private readonly ModuleQueryService $queryService,
        private readonly YoutubeDataService $dataService,
        private readonly YoutubeAuthService $authService,
        private readonly YoutubeConnectionHandler $connectionHandler
    ) {}

    /**
     * Query for the modules with search and ordering + sending props for modal opening.
     *
     * @param   \App\Http\Requests\Module\IndexModuleRequest $request
     *
     * @return  \Illuminate\Http\RedirectResponse|\Inertia\Response
     */
    public function index(IndexModuleRequest $request): RedirectResponse | InertiaResponse
    {
        if ($redirect = $this->connectionHandler->handleRequest($request)) {
            return $redirect;
        }

        $filterData = ModuleFilterData::fromRequest($request);
        $result = $this->queryService->getFilteredModulesWithModal($filterData, 'VideoGenerateModal');
        $youtubeConnection = $this->authService->getConnectionStatus();

        if ($result['modules']->currentPage() > $result['modules']->lastPage() && $result['modules']->lastPage() > 0) {
            return $this->redirectToFirstPage($request, 'modules.index');
        }

        $data = array_merge($result, ['connection' => $youtubeConnection]);

        if (Session::has('google_oauth_token')) {
            $channelData = $this->dataService->getChannelData();
            if ($channelData) {
                $data['channelData'] = $channelData;
                // Optionally store channel data in session for future use
                session(['youtube_channel_data' => $channelData]);
            }
        }

        return Inertia::render('modules/Modules', $data);
    }
}
