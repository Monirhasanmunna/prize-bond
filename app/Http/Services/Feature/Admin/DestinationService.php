<?php
namespace App\Http\Services\Feature\Admin;

use App\Models\Destination;
use App\Traits\FileSaver;
use App\Traits\Request;
use App\Traits\Response;
use Bitsmind\GraphSql\Facades\QueryAssist;
use Bitsmind\GraphSql\QueryAssist as QueryAssistTrait;
use Illuminate\Support\Str;
use const App\Http\Services\Backend\STATUS_DELETED;

class DestinationService
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

            $dbQuery = Destination::query();
            $dbQuery = QueryAssist::queryOrderBy($dbQuery, $query);
            $dbQuery = QueryAssist::queryWhere($dbQuery, $query, ['status']);
            $dbQuery = QueryAssist::queryGraphSQL($dbQuery, $query, new Destination);

            if (array_key_exists('search', $query)) {
                $dbQuery = $dbQuery->where('name', 'like', '%'.$query['search'].'%');
            }

            $count = $dbQuery->count();
            $destinations = $this->queryPagination($dbQuery, $query)->get();

            return $this->response([
                'destinations' => $destinations,
                'count' => $count,
                'destinationStatus' => commonStatus(),
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
            $imageName = $this->upload_file( $payload['image'], 'tour','destination');

            Destination::create( $this->_formatedDestinationCreatedData( $payload, $imageName));

            return $this->response()->success('Destination created successfully');

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
            $destination = Destination::where('id', $payload['id'])->first();
            if(!$destination) {
                return $this->response()->error('Destination not found');
            }

            $imageName = null;
            if($payload['image']){
                $imageName = $this->upload_file( $payload['image'], 'tour', 'destination', $destination->image);
            }

            $destination->update( $this->_formatedDestinationUpdatedData( $payload, $imageName));

            return $this->response()->success('Destination updated successfully');

        } catch (\Exception $exception) {
            return $this->response()->error($exception->getMessage());
        }
    }


    /**
     * @param array $payload
     * @return array
     */
    public function changeDestinationStatus (array $payload): array
    {
        try {
            $destination = Destination::where('id', $payload['id'])->first();
            if (!$destination) {
                return $this->response()->error("Destination not found");
            }

            $destination->update(['status' => $payload['status']]);

            return $this->response(['destination' => $destination])->success('Destination Status Updated Successfully');
        }
        catch (\Exception $exception) {
            return $this->response()->error($exception->getMessage());
        }
    }

    /**
     * @param string $id
     * @return array
     */
    public function deleteDestination (string $id): array
    {
        try {
            $destination = Destination::where('id', $id)->first();
            if (!$destination) {
                return $this->response()->error("Destination not found");
            }

            $destination->delete();

            return $this->response()->success('Brand Deleted Successfully');
        }
        catch (\Exception $exception) {
            return $this->response()->error($exception->getMessage());
        }
    }

    public function restoreDestination (string $id): array
    {
        try {
            $destination = Destination::where('id', $id)->where('status', STATUS_DELETED)->first();
            if (!$destination) {
                return $this->response()->error("Destination not found");
            }

            $destination->update( ['status' => STATUS_ACTIVE]);

            return $this->response()->success('Destination Restored Successfully');
        } catch (\Exception $exception) {
            return $this->response()->error($exception->getMessage());
        }
    }


    /**
     * @param array $payload
     * @param string $imageName
     * @return array
     */
    private function _formatedDestinationCreatedData(array $payload, string $imageName): array
    {
        return [
            'name' => $payload['name'],
            'slug' => Str::slug($payload['name']),
            'image' => $imageName
        ];
    }


    /**
     * @param array $payload
     * @param string $imageName
     * @return array
     */
    private function _formatedDestinationUpdatedData(array $payload, string $imageName = null): array
    {
        $data = [];

        if(array_key_exists('name', $payload)) $data['name']    = $payload['name'];
        if(array_key_exists('name', $payload)) $data['slug']    = Str::slug($payload['name']);
        if($imageName)                              $data['image']   = $imageName;

        return $data;
    }
}
