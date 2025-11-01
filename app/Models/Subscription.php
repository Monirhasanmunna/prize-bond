<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscription extends Model
{
    use Uuid;

    protected $fillable = [
        'name',
        'duration_type',
        'duration',
        'base_price',
        'discount_price',
        'status',
    ];


    /**
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'subscription_id', 'id');
    }
}
