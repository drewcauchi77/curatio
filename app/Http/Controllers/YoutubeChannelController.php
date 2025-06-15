<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Module\IndexModuleRequest;
use App\Services\Module\ModuleQueryService;
use App\Services\YoutubeChannel\YoutubeAuthService;
use App\Services\YoutubeChannel\YoutubeChannelService;
use App\Traits\Module\HandlesModuleListing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response as InertiaResponse;

/**
 * Manages YouTube channel CRUD operations.
 */
class YoutubeChannelController extends Controller
{
    use HandlesModuleListing;

    /**
     * Constructor to define the dependency injection.
     */
    public function __construct(
        private readonly ModuleQueryService $queryService,
        private readonly YoutubeAuthService $authService,
        private readonly YoutubeChannelService $channelService
    ) {}

    /**
     * Display modules list and handle OAuth callback.
     *
     * @param IndexModuleRequest $request
     * @return RedirectResponse|InertiaResponse
     */
    public function index(IndexModuleRequest $request): RedirectResponse | InertiaResponse
    {
        if ($request->has('code')) {
            try {
                $this->channelService->handleAuthCallback($request->input('code'), $request->user());
                $this->authService->clearCodeVerifier();

                return redirect()->route('modules.index')->with('success', 'YouTube channel connected successfully!');
            } catch (\Exception $e) {
                $this->authService->clearCodeVerifier();
                return redirect()->route('modules.index')->withErrors(['youtube' => 'Authentication failed: ' . $e->getMessage()]);
            }
        }

        $youtubeConnection = $this->channelService->getConnectionStatus($request->user());

        return $this->renderModulesList($request, [
            'connection' => $youtubeConnection
        ], 'VideoGenerateModal');
    }

    /**
     * Disconnect the YouTube channel.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        try {
            $success = $this->channelService->disconnect($request->user());

            if ($success) {
                return redirect()->route('modules.youtube.index')->with('success', 'YouTube channel disconnected successfully!');
            }

            return redirect()->route('modules.youtube.index')->withErrors(['youtube' => 'Failed to disconnect channel']);
        } catch (\Exception $e) {
            return redirect()->route('modules.youtube.index')->withErrors(['youtube' => 'An error occurred while disconnecting']);
        }
    }
}
