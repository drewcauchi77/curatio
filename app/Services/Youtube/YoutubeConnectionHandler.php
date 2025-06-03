<?php

namespace App\Services\Youtube;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class YoutubeConnectionHandler
{
    private string $redirectUrl = "https://redirectmeto.com/http://curatio.com/modules/generate?auth=successful";

    public function __construct(
        private readonly YoutubeAuthService $authService
    ) {}

    public function handleRequest(Request $request): ?RedirectResponse
    {
        if ($request->has('code')) {
            $this->authService->handleAuthCallback($request->input('code'));
            return redirect($this->redirectUrl);
        }

        if ($request->session()->has('disconnect')) {
            $this->authService->disconnect();
            return redirect($this->redirectUrl);
        }

        return null;
    }
}
