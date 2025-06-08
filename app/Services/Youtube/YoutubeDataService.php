<?php

namespace App\Services\Youtube;

use App\Services\Youtube\YoutubeAuthService;
use GuzzleHttp\Client as GuzzleHttpClient;
use Google\Client;
use Google\Service\YouTube;
use Illuminate\Support\Facades\Session;
use Exception;

class YoutubeDataService
{
    private Client $googleClient;
    private YouTube $youtube;

    public function __construct()
    {
        $this->initialiseGoogleClient();
    }

    private function initialiseGoogleClient(): void
    {
        $this->googleClient = new Client();
        $this->googleClient->setAuthConfig(base_path('youtube.json'));
        $this->googleClient->addScope('https://www.googleapis.com/auth/youtube');

        // TODO this is unsafe
        $httpClient = new GuzzleHttpClient([
            'verify' => false,
        ]);
        $this->googleClient->setHttpClient($httpClient);

        // Check if we have a token in session
        if (Session::has('google_oauth_token')) {
            $this->googleClient->setAccessToken(Session::get('google_oauth_token'));
            $this->youtube = new YouTube($this->googleClient);
        }
    }

    /**
     * Get channel data including name, profile picture, and video count
     *
     * @return array|null
     * @throws Exception
     */
    public function getChannelData(): ?array
    {
        // Check if we have a valid session token
        if (!Session::has('google_oauth_token')) {
            throw new Exception('No YouTube authentication token found in session');
        }

        // Set the access token
        $this->googleClient->setAccessToken(Session::get('google_oauth_token'));

        // Check if token is expired
        if ($this->googleClient->isAccessTokenExpired()) {
            throw new Exception('YouTube authentication token has expired');
        }

        // Initialize YouTube service if not already done
        if (!isset($this->youtube)) {
            $this->youtube = new YouTube($this->googleClient);
        }

        try {
            // Get channel information
            $channelsResponse = $this->youtube->channels->listChannels('snippet,statistics', [
                'mine' => true
            ]);

            if (empty($channelsResponse->getItems())) {
                return null;
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
        } catch (Exception $e) {
            throw new Exception('Failed to fetch YouTube channel data: ' . $e->getMessage());
        }
    }
}
