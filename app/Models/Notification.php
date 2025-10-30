<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Notification extends Model
{
    use Uuid;

    protected $fillable = ['title', 'description'];


    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_notifications', 'notification_id', 'user_id')
            ->using(UserNotification::class)
            ->withTimestamps();
    }
}
