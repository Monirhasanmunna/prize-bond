<?php

namespace App\Models;

 use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
 use Illuminate\Database\Eloquent\Relations\BelongsToMany;
 use Illuminate\Database\Eloquent\Relations\HasMany;
 use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, Uuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'name',
        'phone',
        'email',
        'image',
        'nid',
        'referral_code',
        'role',
        'password',
        'email_verified_at',
        'fcm_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
     * @return BelongsToMany
     */
    public function notifications(): BelongsToMany
    {
        return $this->belongsToMany(Notification::class, 'user_notifications', 'user_id', 'notification_id')
            ->using(UserNotification::class)
            ->withTimestamps();
    }
}
