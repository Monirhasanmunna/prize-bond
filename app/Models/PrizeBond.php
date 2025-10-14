<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrizeBond extends Model
{
    use Uuid;

    protected $fillable = [
        'user_id',
        'bond_series_id',
        'price',
        'code',
        'status',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function series(): BelongsTo
    {
        return $this->belongsTo(BondSeries::class, 'bond_series_id', 'id');
    }
}
