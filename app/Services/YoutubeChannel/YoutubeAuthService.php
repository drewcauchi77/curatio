<?php

namespace App\Services\YoutubeChannel;

use Google\Client;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Support\Facades\Session;

/**
 * Service for handling YouTube authentication.
 */
class YoutubeAuthService
{
    private Client $googleClient;
    private string $redirectUrl;

    public function __construct()
    {
        $this->redirectUrl = config('app.youtube.redirect_url');
        $this->initialiseGoogleClient();
    }

    /**
     * Initialize the Google client.
     */
    private function initialiseGoogleClient(): void
    {
        $this->googleClient = new Client();
        $this->googleClient->setClientId(config('app.youtube.client_id'));
        $this->googleClient->setClientSecret(config('app.youtube.client_secret'));
        $this->googleClient->setRedirectUri($this->redirectUrl);
        $this->googleClient->addScope(config('app.youtube.scope_uri'));
        $this->googleClient->setAccessType('offline');
        $this->googleClient->setPrompt('consent');

        if (config('app.env') === 'local') {
            $httpClient = new GuzzleHttpClient(['verify' => false]);
            $this->googleClient->setHttpClient($httpClient);
        }
    }

    /**
     * Get the OAuth authorization URL.
     * 
     * @return string
     */
    public function getAuthUrl(): string
    {
        $codeVerifier = $this->googleClient->getOAuth2Service()->generateCodeVerifier();
        Session::put('youtube_code_verifier', $codeVerifier);
        return $this->googleClient->createAuthUrl();
    }

    /**
     * Fetch access token using authorization code.
     * 
     * @param string $code
     * @param string $codeVerifier
     * @return array
     */
    public function fetchAccessToken(string $code, string $codeVerifier): array
    {
        return $this->googleClient->fetchAccessTokenWithAuthCode($code, $codeVerifier);
    }

    /**
     * Get the stored code verifier from session.
     * 
     * @return string|null
     */
    public function getCodeVerifier(): ?string
    {
        return Session::get('youtube_code_verifier');
    }

    /**
     * Clear the code verifier from session.
     */
    public function clearCodeVerifier(): void
    {
        Session::forget('youtube_code_verifier');
    }
}
