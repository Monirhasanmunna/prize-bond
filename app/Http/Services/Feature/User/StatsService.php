<?php
namespace App\Http\Services\Feature\User;

use App\Models\BondSeries;
use App\Models\Draw;
use App\Models\PrizeBond;
use App\Traits\Request;
use App\Traits\Response;
use Bitsmind\GraphSql\QueryAssist as QueryAssistTrait;
use Illuminate\Support\Facades\Auth;

class StatsService
{
    use Request,Response, QueryAssistTrait;

    /**
     * @param array $query
     * @return array
     */
    public function getData (array $query): array
    {
        try {
            $userId = Auth::id();

            if(!$userId) {
                return $this->response()->error('Not Authorize');
            }

            $bonds                  = PrizeBond::where('user_id', $userId);
            $totalBond              = $bonds->count();
            $totalSeries            = BondSeries::where('status', STATUS_ACTIVE)->count();
            $totalDraw              = Draw::all()->count();
            $totalPurchaseAmount    = $bonds->sum('price');

            return $this->response([
                'totalBond'          => $totalBond,
                'totalSeries'        => $totalSeries,
                'totalDraw'          => $totalDraw,
                'totalPurchaseAmount' => $totalPurchaseAmount,
                ...$query
            ])->success();
        }
        catch (\Exception $exception) {
            return $this->response()->error($exception->getMessage());
        }
    }
}
