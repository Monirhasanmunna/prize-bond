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
                $query['graph'] = '{*}';
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
                'bondStatus' => commonStatus(),
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
     * @param array $payload
     * @return array
     */
    public function changeStatus (array $payload): array
    {
        try {
            $bond = PrizeBond::where('id', $payload['id'])->where('user_id', Auth::id())->first();
            if (!$bond) {
                return $this->response()->error("Prize bond not found");
            }

            $bond->update(['status' => $payload['status']]);

            return $this->response(['series' => $bond])->success('Prize bond Status Updated Successfully');
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
