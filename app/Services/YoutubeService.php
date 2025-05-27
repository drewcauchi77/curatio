<?php

namespace App\Services;

use Google\Client;
use Google\Service\YouTube;

class YoutubeService
{
    public function getChannel()
    {
        $apiKey = config('app.youtube_api_key');

        $client = new Client();
        $client->setDeveloperKey($apiKey);
        $service = new YouTube($client);

        $response = $service->search->listSearch('snippet', ['channelId' => 'UCxDZs_ltFFvn0FDHT6kmoXA', 'maxResults' => 50]);
        return $response;
    }
}
