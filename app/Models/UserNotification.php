<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserNotification extends Pivot
{
    use HasFactory, Uuid;

    protected $table = 'user_notifications';

    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'notification_id',
        'is_read',
    ];
}
