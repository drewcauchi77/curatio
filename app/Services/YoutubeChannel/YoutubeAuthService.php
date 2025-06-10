<?php

namespace App\Services\YoutubeChannel;

use App\Models\User;
use App\Models\YoutubeChannel;
use App\Repositories\YoutubeChannelRepository;
use Exception;
use Google\Client;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Support\Facades\Session;

class YoutubeAuthService
{
    private Client $googleClient;
    private string $redirectUrl;

    function __construct(
        private readonly YoutubeChannelRepository $channelRepository
    ) {
        $this->redirectUrl = 'https://redirectmeto.com/http://curatio.com/modules/generate?auth=successful';
        $this->initialiseGoogleClient();
    }

    private function initialiseGoogleClient(): void
    {
        $this->googleClient = new Client();
        $this->googleClient->setClientId('abc'); // ADD SECRET TODO
        $this->googleClient->setClientSecret('abc'); // ADD SECRET TODO
        $this->googleClient->setRedirectUri($this->redirectUrl);
        $this->googleClient->addScope('https://www.googleapis.com/auth/youtube');
        $this->googleClient->setAccessType('offline');
        $this->googleClient->setPrompt('consent');

        // TODO this is unsafe
        $httpClient = new GuzzleHttpClient([
            'verify' => false,
        ]);
        $this->googleClient->setHttpClient($httpClient);
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

            $channelData = $this->getChannelDataFromApi();

            $youtubeChannel = $this->channelRepository->createOrUpdate($user, [
                'channel_id' => $channelData['id'],
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

    private function getChannelDataFromApi(): array
    {
        $youtube = new \Google\Service\YouTube($this->googleClient);

        $channelsResponse = $youtube->channels->listChannels('snippet,statistics', [
            'mine' => true
        ]);

        if (empty($channelsResponse->getItems())) {
            throw new Exception('No YouTube channel found for this account');
        }

        $channel = $channelsResponse->getItems()[0];
        $snippet = $channel->getSnippet();
        $statistics = $channel->getStatistics();

        return [
            'id' => $channel->getId(),
            'name' => $snippet->getTitle(),
            'profilePicture' => $snippet->getThumbnails()->getHigh()->getUrl(),
            'videoCount' => $statistics->getVideoCount(),
            'subscriberCount' => $statistics->getSubscriberCount(),
            'viewCount' => $statistics->getViewCount(),
        ];
    }

    public function refreshToken(YoutubeChannel $channel): YoutubeChannel
    {
        if (!$channel->refresh_token) {
            throw new Exception('No refresh token available');
        }

        $this->googleClient->setAccessToken([
            'access_token' => $channel->access_token,
            'refresh_token' => $channel->refresh_token
        ]);

        $newToken = $this->googleClient->fetchAccessTokenWithRefreshToken($channel->refresh_token);

        if (isset($newToken['error'])) {
            throw new Exception('Token refresh failed: ' . $newToken['error_description']);
        }

        $channel->update([
            'access_token' => $newToken['access_token'],
            'token_expires_at' => now()->addSeconds($newToken['expires_in'])
        ]);

        return $channel;
    }

    public function isUserConnected(User $user): bool
    {
        $channel = $this->channelRepository->findByUser($user);
        return $channel && $channel->is_active;
    }

    public function disconnect(User $user): bool
    {
        return $this->channelRepository->disconnect($user);
    }

    public function getConnectionStatus(User $user): array
    {
        $channel = $this->channelRepository->findByUser($user);
        $connected = $channel && $channel->is_active;

        return [
            'connected' => $connected,
            'authUrl' => $connected ? null : $this->getAuthUrl(),
            'channelData' => $connected ? $this->formatChannelData($channel) : null
        ];
    }

    private function formatChannelData(YoutubeChannel $channel): array
    {
        return [
            'id' => $channel->channel_id,
        ];
    }
}
