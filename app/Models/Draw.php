<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Draw extends Model
{
    use Uuid;

    protected $fillable = [
        'name',
        'date',
        'status',
    ];
}
