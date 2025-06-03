<?php

namespace App\Http\Controllers;

use App\DTO\Module\ModuleFilterData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Module\IndexModuleRequest;
use App\Services\Module\ModuleQueryService;
use App\Services\Youtube\YoutubeAuthService;
use App\Services\Youtube\YoutubeConnectionHandler;
use App\Services\YoutubeService;
use Exception;
use Google\Client;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class YoutubeController extends Controller
{
    /**
     * Constructor to define the dependency injection.
     */
    function __construct(
        private readonly YoutubeService $youtubeService,
        private readonly ModuleQueryService $queryService,
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

        // TODO: Redirect to first page on error
        // if ($this->shouldRedirectToFirstPage($request, $result['paginator'])) {
        //     return $this->redirectToFirstPage($request);
        // }

        $data = array_merge($result, ['connection' => $youtubeConnection]);

        return Inertia::render('modules/Modules', $data);
    }

    public function store()
    {
        try {
            $result = $this->youtubeService->getChannel();
            dd($result->items);
        } catch (Exception $e) {
            dd($e);
        }
    }
}
