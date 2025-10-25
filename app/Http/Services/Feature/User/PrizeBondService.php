<?php
namespace App\Http\Services\Feature\User;

use App\Models\PrizeBond;
use App\Traits\FileSaver;
use App\Traits\Request;
use App\Traits\Response;
use Bitsmind\GraphSql\Facades\QueryAssist;
use Bitsmind\GraphSql\QueryAssist as QueryAssistTrait;
use Illuminate\Support\Facades\Auth;

class PrizeBondService
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
                $query['graph'] = '{price,code,series{name}}';
            }

            $dbQuery = PrizeBond::where('user_id', Auth::id());
            $dbQuery = QueryAssist::queryOrderBy($dbQuery, $query);
            $dbQuery = QueryAssist::queryWhere($dbQuery, $query, ['status']);
            $dbQuery = QueryAssist::queryGraphSQL($dbQuery, $query, new PrizeBond);

            if (array_key_exists('search', $query)) {
                $dbQuery = $dbQuery->where('price', 'like', '%'.$query['search'].'%')
                                    ->orWhere('code', 'like', '%'.$query['search'].'%');
            }

            $count = $dbQuery->count();
            $bonds = $this->queryPagination($dbQuery, $query)->get();

            return $this->response([
                'bonds' => $bonds,
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
    public function storeData (array $payload): array
    {
        try {
            PrizeBond::create( $this->_formatedPrizeBondCreatedData( $payload));

            return $this->response()->success('Prize bond created successfully');

        } catch (\Exception $exception) {
            return $this->response()->error($exception->getMessage());
        }
    }



    /**
     * @param array $payload
     * @return array
     */
    public function bulkStoreData (array $payload): array
    {
        try {
            $parts = preg_split('/\s+/', trim($payload['start_prize_bond_number']));

            if(is_array($parts) && count($parts) > 1) {
                $seriesCode = reset($parts);
                $startNumber = end($parts);
            }
            else {
                $seriesCode = substr($payload['start_prize_bond_number'], 0, 2);
                $startNumber = substr($payload['start_prize_bond_number'], 2);
            }

            if(!empty($startNumber)){
                for ($i = 0; $i < (int) $payload['total_bond']; $i++) {
                    PrizeBond::create( $this->_formatedPrizeBondCreatedData( [
                        'bond_series_id' => $payload['bond_series_id'],
                        'price' => $payload['price'],
                        'code' => $seriesCode . (int) $startNumber + $i,
                    ]));
                }
            }

            return $this->response()->success('Prize bond created successfully');

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
            $bond = PrizeBond::where('id', $payload['id'])->where('user_id', Auth::id())->first();
            if(!$bond) {
                return $this->response()->error('Prize bond not found');
            }

            $bond->update( $this->_formatedPrizeBondUpdatedData( $payload));

            return $this->response()->success('Prize bond updated successfully');

        } catch (\Exception $exception) {
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
            $bond = PrizeBond::where('id', $id)->first();
            if (!$bond) {
                return $this->response()->error("Prize bond not found");
            }

            $bond->delete();

            return $this->response()->success('Prize bond Deleted Successfully');
        }
        catch (\Exception $exception) {
            return $this->response()->error($exception->getMessage());
        }
    }


    /**
     * @param array $payload
     * @return array
     */
    private function _formatedPrizeBondCreatedData(array $payload): array
    {
        return [
            'user_id'           => Auth::id(),
            'bond_series_id'    => $payload['bond_series_id'],
            'price'             => $payload['price'],
            'code'              => $payload['code'],
        ];
    }


    /**
     * @param array $payload
     * @return array
     */
    private function _formatedPrizeBondUpdatedData(array $payload): array
    {
        $data = [];

        if(array_key_exists('bond_series_id', $payload)) $data['bond_series_id']    = $payload['bond_series_id'];
        if(array_key_exists('price', $payload)) $data['price']                      = $payload['price'];
        if(array_key_exists('code', $payload)) $data['code']                        = $payload['code'];

        return $data;
    }
}
