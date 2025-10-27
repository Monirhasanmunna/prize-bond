<?php
namespace App\Http\Services\Feature\Admin;

use App\Models\Draw;
use App\Traits\FileSaver;
use App\Traits\Request;
use App\Traits\Response;
use App\Imports\WinnerImport;
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
                $query['graph'] = '{*}';
            }

            $dbQuery = Draw::query();
            $dbQuery = QueryAssist::queryOrderBy($dbQuery, $query);
            $dbQuery = QueryAssist::queryWhere($dbQuery, $query, ['status']);
            $dbQuery = QueryAssist::queryGraphSQL($dbQuery, $query, new Draw);

            if (array_key_exists('search', $query)) {
                $dbQuery = $dbQuery->where('name', 'like', '%'.$query['search'].'%');
            }

            $count = $dbQuery->count();
            $draws = $this->queryPagination($dbQuery, $query)->get();

            return $this->response([
                'draws' => $draws,
                'count' => $count,
                'drawStatus' => commonStatus(),
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
            DB::beginTransaction();
            $draw = Draw::create( $this->_formatedDrawCreatedData( $payload));

            Excel::import(new WinnerImport($draw->id), $payload['file']);

            DB::commit();
            return $this->response()->success('Draw created successfully');

        } catch (\Exception $exception) {
            DB::rollBack();
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
            $draw = Draw::where('id', $payload['id'])->first();
            if(!$draw) {
                return $this->response()->error('Draw not found');
            }

            $draw->update( $this->_formatedDrawUpdatedData( $payload));

            return $this->response()->success('Draw updated successfully');

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
            $draw = Draw::where('id', $payload['id'])->first();
            if (!$draw) {
                return $this->response()->error("Draw not found");
            }

            $draw->update(['status' => $payload['status']]);

            return $this->response(['series' => $draw])->success('Draw Status Updated Successfully');
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
            $draw = Draw::where('id', $id)->first();
            if (!$draw) {
                return $this->response()->error("Draw not found");
            }

            $draw->delete();

            return $this->response()->success('Draw Deleted Successfully');
        }
        catch (\Exception $exception) {
            return $this->response()->error($exception->getMessage());
        }
    }


    /**
     * @param array $payload
     * @return array
     */
    private function _formatedDrawCreatedData(array $payload): array
    {
        return [
            'name' => $payload['name'],
            'date' => $payload['date'],
        ];
    }


    /**
     * @param array $payload
     * @return array
     */
    private function _formatedDrawUpdatedData(array $payload): array
    {
        $data = [];

        if(array_key_exists('name', $payload)) $data['name']    = $payload['name'];
        if(array_key_exists('code', $payload)) $data['code']    = $payload['code'];

        return $data;
    }
}
