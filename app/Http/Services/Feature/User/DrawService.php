<?php
namespace App\Http\Services\Feature\User;

use App\Models\Draw;
use App\Models\DrawWinner;
use App\Models\PrizeBond;
use App\Traits\FileSaver;
use App\Traits\Request;
use App\Traits\Response;
use App\Imports\WinnerImport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Bitsmind\GraphSql\Facades\QueryAssist;
use Bitsmind\GraphSql\QueryAssist as QueryAssistTrait;

class DrawService
{
    use Request,Response, QueryAssistTrait, FileSaver;

    /**
     * @param array $query
     * @return array
     */
    public function getListData (array $query): array
    {
        try {
            $validationErrorMsg = $this->queryParams($query)->required([]);
            if ($validationErrorMsg) {
                return $this->response()->error($validationErrorMsg);
            }

            if (!array_key_exists('graph', $query)) {
                $query['graph'] = '{id,name,date}';
            }

            $dbQuery = Draw::query();
            $dbQuery = QueryAssist::queryOrderBy($dbQuery, $query);
            $dbQuery = QueryAssist::queryWhere($dbQuery, $query, ['status']);
            $dbQuery = QueryAssist::queryGraphSQL($dbQuery, $query, new Draw);

            $count = $dbQuery->count();
            $draws = $this->queryPagination($dbQuery, $query)->get();

            return $this->response([
                'draws' => $draws,
                'count' => $count,
                ...$query
            ])->success();
        }
        catch (\Exception $exception) {
            return $this->response()->error($exception->getMessage());
        }
    }


    /**
     * @param array $payload
     * @return array
     */
    public function checkWinner(array $payload): array
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return $this->response()->error('Not Authorized');
            }

            if (empty($payload['draw_id'])) {
                return $this->response()->error('Draw ID is required.');
            }

            // Get all winners for the draw (with their details)
            $winners = DrawWinner::where('draw_id', $payload['draw_id'])
                ->select('bond_number', 'prize_type', 'amount')
                ->get();

            // Extract the core 8-digit parts (ignoring 2-letter series)
            $winningMap = collect($winners)->mapWithKeys(function ($w) {
                return [substr($w->bond_number, 2) => [
                    'original_number' => $w->bond_number,
                    'prize_type' => $w->prize_type,
                    'amount' => $w->amount,
                ]];
            });

            // Fetch user bonds
            $userBonds = PrizeBond::where('user_id', $user->id)->get();

            // Filter the user’s bonds that match any winning number (by core digits)
            $matched = $userBonds->filter(function ($bond) use ($winningMap) {
                $core = substr($bond->code, 2);
                return $winningMap->has($core);
            })->map(function ($bond) use ($winningMap) {
                $core = substr($bond->code, 2);
                $winnerInfo = $winningMap[$core];

                return [
                    'bond_number' => $bond->code,
                    'prize_type' => $winnerInfo['prize_type'],
                    'amount' => $winnerInfo['amount'],
                ];
            })->values();

            if ($matched->isEmpty()) {
                return $this->response()->success('No winning bonds found.');
            }

            return $this->response([
                'total_wins' => $matched->count(),
                'winning_bonds' => $matched,
            ])->success('Winning bonds found.');
        }
        catch (\Exception $exception) {
            return $this->response()->error($exception->getMessage());
        }
    }


}
