<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

/**
 *
 */
class JWTToken extends Model
{
    use Prunable;

    protected $table = 'jwt_tokens';
    /**
     * @var array<int, string>
     */
    protected $fillable = ['user_id', 'unique_id', 'token_title', 'restrictions', 'permissions', 'expires_at', 'last_used_at', 'refreshed_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User, JWTToken>
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Check if the token is expired
     * @return Attribute<bool, mixed>
     */
    public function isExpired():Attribute
    {
        return Attribute::make(get: fn () => $this->expires_at < now());
    }

    /**
     * Check if the token is expired
     * @return Builder<JWTToken>
     */
    public function scopeExpired(): Builder
    {
        return $this->where('expires_at', '<', now());
    }


    /**
     * Get the prunable model query.
     * @return Builder<JWTToken>
     */
    public function prunable(): Builder
    {
        // Delete all records older than a month
        return static::where('created_at', '<=', now()->subMonth());
    }
}
