<?php

namespace App\Http\Controllers;

use App\DTO\Module\ModuleFilterData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Module\IndexModuleRequest;
use App\Services\Module\ModuleQueryService;
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
        private readonly ModuleQueryService $queryService
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
        $filterData = ModuleFilterData::fromRequest($request);
        $result = $this->queryService->getFilteredModules($filterData);

        // TODO: Redirect to first page on error
        // if ($this->shouldRedirectToFirstPage($request, $result['paginator'])) {
        //     return $this->redirectToFirstPage($request);
        // }

        return Inertia::render('modules/Modules', $result);
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

    public function auth(Request $request)
    {
        $redirectUrl = "https://redirectmeto.com/http://curatio.com/modules/authe";
        $client = new Client();
        $client->setAuthConfig(base_path('youtube.json'));
        $client->setRedirectUri($redirectUrl);
        $client->addScope('https://www.googleapis.com/auth/youtube');
        // TODO this is unsafe
        $httpClient = new GuzzleHttpClient([
            'verify' => false,
        ]);
        $client->setHttpClient($httpClient);

        // Initialize variables
        $connected = false;
        $authUrl = null;

        if (!$request->has('code') && !Session::has('google_oauth_token')) {
            Session::put('code_verifier', $client->getOAuth2Service()->generateCodeVerifier());
            $authUrl = $client->createAuthUrl();
            $connected = false;
        }

        if ($request->has('code')) {
            $token = $client->fetchAccessTokenWithAuthCode($request->input('code'), Session::get('code_verifier'));
            $client->setAccessToken($token);
            Session::put('google_oauth_token', $token);
            return redirect($redirectUrl);
        }

        if (Session::has('google_oauth_token')) {
            $client->setAccessToken(Session::get('google_oauth_token'));
            if ($client->isAccessTokenExpired()) {
                Session::forget('google_oauth_token');
                $connected = false;
            } else {  // Added else to only set connected=true if token is not expired
                $connected = true;
            }
        }

        if (Session::has('disconnect')) {
            Session::forget('google_oauth_token');
            Session::forget('code_verifier');
            return redirect($redirectUrl);
        }

        // If connected is true but we don't have authUrl, we need to generate it
        if (!$connected && !$authUrl) {
            Session::put('code_verifier', $client->getOAuth2Service()->generateCodeVerifier());
            $authUrl = $client->createAuthUrl();
        }

        return Inertia::render('modules/Auth', [
            'connected' => $connected,
            'authUrl' => $authUrl
        ]);
    }
}
