<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class YoutubeChannel extends Model
{
    protected $fillable = [
        'user_id',
        'channel_id',
        'access_token',
        'refresh_token',
        'token_expires_at',
        'last_synced_at',
        'is_active'
    ];

    protected $casts = [
        'token_expires_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isTokenExpired(): bool
    {
        return $this->token_expires_at && $this->token_expires_at->isPast();
    }
}
