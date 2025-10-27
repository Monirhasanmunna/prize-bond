<?php

namespace App\Imports;

use App\Models\DrawWinner;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class WinnerImport implements ToModel, WithHeadingRow, SkipsEmptyRows, WithValidation
{
    protected $drawId;
    public function __construct($drawId)
    {
        $this->drawId = $drawId;
    }

    public function rules(): array
    {
        return [
            '*.bond_number' => 'required',
            '*.prize_type' => 'required',
            '*.amount' => 'required',
        ];
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new DrawWinner([
            'draw_id'      => $this->drawId ?? null,
            'bond_number'  => $row['bond_number'] ?? null,
            'prize_type'   => $row['prize_type'] ?? null,
            'amount'       => $row['amount'] ?? null,
        ]);
    }
}
