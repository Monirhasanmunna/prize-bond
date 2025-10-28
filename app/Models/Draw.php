<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Draw extends Model
{
    use Uuid;

    protected $fillable = [
        'name',
        'date',
        'status',
    ];


    /**
     * @return HasMany
     */
    public function winners(): HasMany
    {
        return $this->hasMany(DrawWinner::class, 'draw_id', 'id');
    }
}
