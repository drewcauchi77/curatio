<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\YoutubeChannel;

class YoutubeChannelRepository
{
    public function findByUser(User $user): ?YoutubeChannel
    {
        return YoutubeChannel::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();
    }

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

    public function disconnect(User $user): bool
    {
        return YoutubeChannel::where('user_id', $user->id)
            ->update(['is_active' => false]);
    }
}
