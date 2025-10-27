<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DrawWinner extends Model
{
    use Uuid;

    protected $fillable = [
        'draw_id',
        'bond_number',
        'prize_type',
        'amount'
    ];

    /**
     * @return BelongsTo
     */
    public function draw(): BelongsTo
    {
        return $this->belongsTo(Draw::class, 'draw_id', 'id');
    }
}
