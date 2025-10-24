<?php
namespace App\Http\Services\Feature\User;

use App\Models\BondSeries;
use App\Traits\Request;
use App\Traits\Response;
use Bitsmind\GraphSql\Facades\QueryAssist;
use Bitsmind\GraphSql\QueryAssist as QueryAssistTrait;

class SeriesService
{
    use Request,Response, QueryAssistTrait;

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
                $query['graph'] = '{id,name,code}';
            }

            $dbQuery = BondSeries::query();
            $dbQuery = QueryAssist::queryOrderBy($dbQuery, $query);
            $dbQuery = QueryAssist::queryWhere($dbQuery, $query, ['status']);
            $dbQuery = QueryAssist::queryGraphSQL($dbQuery, $query, new BondSeries);

            if (array_key_exists('search', $query)) {
                $dbQuery = $dbQuery->where('name', 'like', '%'.$query['search'].'%')
                                    ->orWhere('code', 'like', '%'.$query['search'].'%');
            }

            $count = $dbQuery->count();
            $serieses = $this->queryPagination($dbQuery, $query)->get();

            return $this->response([
                'serieses' => $serieses,
                'count' => $count,
                ...$query
            ])->success();
        }
        catch (\Exception $exception) {
            return $this->response()->error($exception->getMessage());
        }
    }
}
