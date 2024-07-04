<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 *
 * Model for the users table.
 * @package App\Models
 * @property int $id
 * @property string $uuid
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $password
 * @property string $address
 * @property string $phone_number
 * @property bool $is_marketing
 * @property \Carbon\Carbon $last_login_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids;

    protected $primaryKey = 'uuid';
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'address',
        'phone_number',
        'is_marketing',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's JWT tokens.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\JWTToken>
     */
    public function jwtTokens(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(JWTToken::class, 'user_id', 'id');
    }
}
