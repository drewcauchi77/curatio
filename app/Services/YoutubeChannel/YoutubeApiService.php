<?php

namespace App\Services\YoutubeChannel;

use Google\Client;
use Google\Service\YouTube;
use GuzzleHttp\Client as GuzzleHttpClient;
use Exception;

/**
 * Service for handling YouTube API operations.
 */
class YoutubeApiService
{
    private Client $googleClient;

    public function __construct()
    {
        $this->initialiseGoogleClient();
    }

    /**
     * Initialize Google client for API operations.
     */
    private function initialiseGoogleClient(): void
    {
        $this->googleClient = new Client();
        $apiKey = config('app.youtube.api_key');
        $this->googleClient->setDeveloperKey($apiKey);

        if (config('app.env') === 'local') {
            $httpClient = new GuzzleHttpClient(['verify' => false]);
            $this->googleClient->setHttpClient($httpClient);
        }
    }

    /**
     * Get channel ID from authenticated user.
     * 
     * @param array $token
     * @return string
     * @throws Exception
     */
    public function getChannelId(array $token): string
    {
        $client = new Client();
        $client->setAccessToken($token);

        if (config('app.env') === 'local') {
            $httpClient = new GuzzleHttpClient(['verify' => false]);
            $client->setHttpClient($httpClient);
        }

        $youtube = new YouTube($client);
        $channelsResponse = $youtube->channels->listChannels('snippet,statistics', [
            'mine' => true
        ]);

        if (empty($channelsResponse->getItems())) {
            throw new Exception('No YouTube channel found for this account');
        }

        return $channelsResponse->getItems()[0]->getId();
    }

    /**
     * Get channel data by channel ID.
     * 
     * @param string $channelId
     * @return array|null
     */
    public function getChannelData(string $channelId): ?array
    {
        $youtube = new YouTube($this->googleClient);

        $response = $youtube->channels->listChannels('snippet,statistics', [
            'id' => $channelId
        ]);

        if (empty($response->getItems())) {
            return null;
        }

        $channelData = $response->getItems()[0];
        $snippet = $channelData->getSnippet();
        $statistics = $channelData->getStatistics();

        return [
            'name' => $snippet->getTitle(),
            'profilePicture' => $snippet->getThumbnails()->getHigh()->getUrl(),
            'videoCount' => $statistics->getVideoCount(),
            'subscriberCount' => $statistics->getSubscriberCount(),
            'viewCount' => $statistics->getViewCount(),
        ];
    }

    public function getChannelVideos() {}
}
