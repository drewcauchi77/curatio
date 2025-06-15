<?php

namespace App\Actions\YoutubeChannel;

use App\Models\User;
use App\Models\YoutubeChannel;
use App\Repositories\YoutubeChannelRepository;

/**
 * Action for creating or updating YouTube channel records.
 */
class CreateOrUpdateYoutubeChannel
{
    public function __construct(
        private readonly YoutubeChannelRepository $channelRepository
    ) {}

    /**
     * Create or update a YouTube channel record.
     * 
     * @param User $user
     * @param string $channelId
     * @param string $accessToken
     * @return YoutubeChannel
     */
    public function handle(User $user, string $channelId, string $accessToken): YoutubeChannel
    {
        return $this->channelRepository->createOrUpdate($user, [
            'channel_id' => $channelId,
            'initial_access_token' => $accessToken,
        ]);
    }
}
