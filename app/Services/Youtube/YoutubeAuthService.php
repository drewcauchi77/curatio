<?php

namespace App\Services\Youtube;

use Google\Client;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Support\Facades\Session;

class YoutubeAuthService
{
    private Client $googleClient;
    private string $redirectUrl = "https://redirectmeto.com/http://curatio.com/modules/generate?auth=successful";

    function __construct()
    {
        $this->initialiseGoogleClient();
    }

    private function initialiseGoogleClient(): void
    {
        $this->googleClient = new Client();
        $this->googleClient->setAuthConfig(base_path('youtube.json'));
        $this->googleClient->setRedirectUri($this->redirectUrl);
        $this->googleClient->addScope('https://www.googleapis.com/auth/youtube');

        // TODO this is unsafe
        $httpClient = new GuzzleHttpClient([
            'verify' => false,
        ]);
        $this->googleClient->setHttpClient($httpClient);
    }

    public function getAuthUrl(): string
    {
        $codeVerifier = $this->googleClient->getOAuth2Service()->generateCodeVerifier();
        Session::put('code_verifier', $codeVerifier);
        return $this->googleClient->createAuthUrl();
    }

    public function handleAuthCallback(string $code): array
    {
        $token = $this->googleClient->fetchAccessTokenWithAuthCode(
            $code,
            Session::get('code_verifier')
        );

        $this->googleClient->setAccessToken($token);
        Session::put('google_oauth_token', $token);

        return $token;
    }

    public function isConnected(): bool
    {
        if (!Session::has('google_oauth_token')) {
            return false;
        }

        $this->googleClient->setAccessToken(Session::get('google_oauth_token'));

        if ($this->googleClient->isAccessTokenExpired()) {
            $this->disconnect();
            return false;
        }

        return true;
    }

    public function disconnect(): void
    {
        Session::forget(['google_oauth_token', 'code_verifier']);
    }

    public function getConnectionStatus(): array
    {
        $connected = $this->isConnected();

        return [
            'connected' => $connected,
            'authUrl' => $connected ? null : $this->getAuthUrl()
        ];
    }
}
