<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class BondSeries extends Model
{
    use Uuid;

    protected $fillable = [
        'name',
        'code',
        'status'
    ];
}
