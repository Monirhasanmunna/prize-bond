<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    use Uuid;
    protected $fillable = ['key', 'value'];
}
