<?php

namespace App\Services\YoutubeChannel;

use App\Models\User;
use App\Models\YoutubeChannel;
use App\Repositories\YoutubeChannelRepository;
use Exception;
use Google\Client;
use GuzzleHttp\Client as GuzzleHttpClient;
use Google\Service\YouTube;
use Illuminate\Support\Facades\Session;

/**
 * Service for handling authentication with the Google API for Youtube.
 */
class YoutubeAuthService
{
    private Client $googleClient;
    private string $redirectUrl;

    function __construct(
        private readonly YoutubeChannelRepository $channelRepository
    ) {
        $this->redirectUrl = config('app.youtube.redirect_url');
        $this->initialiseGoogleClient();
    }

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
            $httpClient = new GuzzleHttpClient([
                'verify' => false,
            ]);

            $this->googleClient->setHttpClient($httpClient);
        }
    }

    public function getAuthUrl(): string
    {
        $codeVerifier = $this->googleClient->getOAuth2Service()->generateCodeVerifier();
        Session::put('youtube_code_verifier', $codeVerifier);
        return $this->googleClient->createAuthUrl();
    }

    public function handleAuthCallback(string $code, User $user): YoutubeChannel
    {
        try {
            $token = $this->googleClient->fetchAccessTokenWithAuthCode(
                $code,
                Session::get('youtube_code_verifier')
            );

            if (isset($token['error'])) {
                throw new Exception('OAuth error: ' . $token['error_description']);
            }

            $this->googleClient->setAccessToken($token);
            $channelId = $this->getChannelId();

            $youtubeChannel = $this->channelRepository->createOrUpdate($user, [
                'channel_id' => $channelId,
                'access_token' => $token['access_token'],
                'refresh_token' => $token['refresh_token'] ?? null,
                'token_expires_at' => now()->addSeconds($token['expires_in'])
            ]);

            Session::forget('youtube_code_verifier');

            return $youtubeChannel;
        } catch (Exception $e) {
            Session::forget('youtube_code_verifier');
            throw $e;
        }
    }

    private function getChannelId(): string
    {
        $youtube = new YouTube($this->googleClient);

        $channelsResponse = $youtube->channels->listChannels('snippet,statistics', [
            'mine' => true
        ]);

        if (empty($channelsResponse->getItems())) {
            throw new Exception('No YouTube channel found for this account');
        }

        $channel = $channelsResponse->getItems()[0];

        return $channel->getId();
    }

    public function getConnectionStatus(User $user): array
    {
        $channel = $this->channelRepository->findByUser($user);
        $connected = $channel && $channel->is_active;

        return [
            'connected' => $connected,
            'authUrl' => $connected ? null : $this->getAuthUrl(),
            'channelData' => [
                'id' => $connected ? $channel->channel_id : null
            ]
        ];
    }

    public function disconnect(User $user): bool
    {
        return $this->channelRepository->disconnect($user);
    }
}
