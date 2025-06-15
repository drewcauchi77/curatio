<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\YoutubeChannel;

/**
 * Repository for youtube channel data operations.
 */
class YoutubeChannelRepository
{
    /**
     * Find the active channel for a user.
     * 
     * @param User $user
     * @return YoutubeChannel|null
     */
    public function findByUser(User $user): ?YoutubeChannel
    {
        return YoutubeChannel::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Find channel by channel ID.
     * 
     * @param string $channelId
     * @return YoutubeChannel|null
     */
    public function findByChannelId(string $channelId): ?YoutubeChannel
    {
        return YoutubeChannel::where('channel_id', $channelId)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Create a new channel or update existing one.
     * 
     * @param User $user
     * @param array $data
     * @return YoutubeChannel
     */
    public function createOrUpdate(User $user, array $data): YoutubeChannel
    {
        return YoutubeChannel::updateOrCreate(
            [
                'user_id' => $user->id,
                'channel_id' => $data['channel_id']
            ],
            array_merge($data, ['is_active' => true])
        );
    }

    /**
     * Deactivate user's YouTube channels.
     * 
     * @param User $user
     * @return bool
     */
    public function disconnect(User $user): bool
    {
        return YoutubeChannel::where('user_id', $user->id)
            ->update(['is_active' => false]);
    }

    /**
     * Update last synced timestamp.
     * 
     * @param YoutubeChannel $channel
     * @return bool
     */
    public function updateLastSynced(YoutubeChannel $channel): bool
    {
        return $channel->update(['last_synced_at' => now()]);
    }
}
