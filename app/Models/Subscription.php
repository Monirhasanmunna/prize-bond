<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

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
}
