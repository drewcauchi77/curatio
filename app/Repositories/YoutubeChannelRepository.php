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
     * Find the channel through the user id.
     * 
     * @param   User $user
     * @return  YoutubeChannel
     */
    public function findByUser(User $user): ?YoutubeChannel
    {
        return YoutubeChannel::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Create a new channel or update the current channel based on user id and channel id.
     * 
     * @param   User $user
     * @param   array $data
     * @return  YoutubeChannel
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
     * Deactivate the token by setting a falsey value.
     * 
     * @param   User $user
     * @return  bool
     */
    public function disconnect(User $user): bool
    {
        return YoutubeChannel::where('user_id', $user->id)
            ->update(['is_active' => false]);
    }
}
