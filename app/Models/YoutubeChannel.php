<?php
// PHPSTAN CONFIRMED
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

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
 * 
 * @property-read User $user
 */
class YoutubeChannel extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'channel_id',
        'initial_access_token',
        'last_synced_at',
        'is_active'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'last_synced_at' => 'datetime'
    ];

    /**
     * Get the user that owns this YouTube channel.
     *
     * @return BelongsTo<\App\Models\User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
