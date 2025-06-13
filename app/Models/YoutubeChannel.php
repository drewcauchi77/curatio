<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * YoutubeChannel model.
 * 
 * @property int $id
 * @property int $user_id
 * @property string $channel_id
 * @property string $access_token
 * @property string $refresh_token
 * @property Carbon|null $token_expires_at
 * @property Carbon|null $last_synced_at
 * @property bool $is_active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class YoutubeChannel extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var     array<string>
     */
    protected $fillable = [
        'user_id',
        'channel_id',
        'access_token',
        'refresh_token',
        'token_expires_at',
        'last_synced_at',
        'is_active'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var     array<string, string>
     */
    protected $casts = [
        'token_expires_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    /**
     * Get the user that owns this YouTube channel.
     *
     * @return  BelongsTo<User, YoutubeChannel>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the access token has expired.
     *
     * @return  bool
     */
    public function isTokenExpired(): bool
    {
        return $this->token_expires_at && $this->token_expires_at->isPast();
    }
}
