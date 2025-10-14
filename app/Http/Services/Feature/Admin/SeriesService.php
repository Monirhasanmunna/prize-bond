<?php
namespace App\Http\Services\Feature\Admin;

use App\Models\BondSeries;
use App\Traits\FileSaver;
use App\Traits\Request;
use App\Traits\Response;
use Bitsmind\GraphSql\Facades\QueryAssist;
use Bitsmind\GraphSql\QueryAssist as QueryAssistTrait;

class SeriesService
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
                $query['graph'] = '{*}';
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
                'seriesStatus' => commonStatus(),
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
    public function storeData (array $payload): array
    {
        try {
            BondSeries::create( $this->_formatedBondSeriesCreatedData( $payload));

            return $this->response()->success('Series created successfully');

        } catch (\Exception $exception) {
            return $this->response()->error($exception->getMessage());
        }
    }


    /**
     * @param array $payload
     * @return array
     */
    public function updateData (array $payload): array
    {
        try {
            $series = BondSeries::where('id', $payload['id'])->first();
            if(!$series) {
                return $this->response()->error('Series not found');
            }

            $series->update( $this->_formatedBondSeriesUpdatedData( $payload));

            return $this->response()->success('Series updated successfully');

        } catch (\Exception $exception) {
            return $this->response()->error($exception->getMessage());
        }
    }


    /**
     * @param array $payload
     * @return array
     */
    public function changeStatus (array $payload): array
    {
        try {
            $series = BondSeries::where('id', $payload['id'])->first();
            if (!$series) {
                return $this->response()->error("Series not found");
            }

            $series->update(['status' => $payload['status']]);

            return $this->response(['series' => $series])->success('Series Status Updated Successfully');
        }
        catch (\Exception $exception) {
            return $this->response()->error($exception->getMessage());
        }
    }

    /**
     * @param string $id
     * @return array
     */
    public function deleteData (string $id): array
    {
        try {
            $series = BondSeries::where('id', $id)->first();
            if (!$series) {
                return $this->response()->error("Series not found");
            }

            $series->delete();

            return $this->response()->success('Series Deleted Successfully');
        }
        catch (\Exception $exception) {
            return $this->response()->error($exception->getMessage());
        }
    }


    /**
     * @param array $payload
     * @return array
     */
    private function _formatedBondSeriesCreatedData(array $payload): array
    {
        return [
            'name' => $payload['name'],
            'code' => $payload['code'],
        ];
    }


    /**
     * @param array $payload
     * @return array
     */
    private function _formatedBondSeriesUpdatedData(array $payload): array
    {
        $data = [];

        if(array_key_exists('name', $payload)) $data['name']    = $payload['name'];
        if(array_key_exists('code', $payload)) $data['code']    = $payload['code'];

        return $data;
    }
}
