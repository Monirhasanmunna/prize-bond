<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BondSeries extends Model
{
    use Uuid;

    protected $fillable = [
        'name',
        'code',
        'status'
    ];


    /**
     * @return HasMany
     */
    public function bonds(): HasMany
    {
        return $this->hasMany(PrizeBond::class, 'bond_series_id', 'id');
    }
}
