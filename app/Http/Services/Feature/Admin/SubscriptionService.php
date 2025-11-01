<?php
namespace App\Http\Services\Feature\Admin;

use App\Models\Subscription;
use App\Traits\FileSaver;
use App\Traits\Request;
use App\Traits\Response;
use Bitsmind\GraphSql\Facades\QueryAssist;
use Bitsmind\GraphSql\QueryAssist as QueryAssistTrait;

class SubscriptionService
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

            $dbQuery = Subscription::query();
            $dbQuery = QueryAssist::queryOrderBy($dbQuery, $query);
            $dbQuery = QueryAssist::queryWhere($dbQuery, $query, ['status']);
            $dbQuery = QueryAssist::queryGraphSQL($dbQuery, $query, new Subscription);

            if (array_key_exists('search', $query)) {
                $dbQuery = $dbQuery->where('name', 'like', '%'.$query['search'].'%');
            }

            $count = $dbQuery->count();
            $subscriptions = $this->queryPagination($dbQuery, $query)->get();

            return $this->response([
                'subscriptions' => $subscriptions,
                'count' => $count,
                'subscriptionStatus' => commonStatus(),
                'subscriptionType' => subscriptionType(),
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
            Subscription::create( $this->_formatedSubscriptionCreatedData( $payload));

            return $this->response()->success('Subscription created successfully');

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
            $subscription = Subscription::where('id', $payload['id'])->first();
            if(!$subscription) {
                return $this->response()->error('Subscription not found');
            }

            $subscription->update( $this->_formatedSubscriptionUpdatedData( $payload));

            return $this->response()->success('Subscription updated successfully');

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
            $subscription = Subscription::where('id', $payload['id'])->first();
            if (!$subscription) {
                return $this->response()->error("Subscription not found");
            }

            $subscription->update(['status' => $payload['status']]);

            return $this->response(['subscription' => $subscription])->success('Subscription Status Updated Successfully');
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
            $subscription = Subscription::where('id', $id)->first();
            if (!$subscription) {
                return $this->response()->error("Subscription not found");
            }

            $subscription->delete();

            return $this->response()->success('Subscription Deleted Successfully');
        }
        catch (\Exception $exception) {
            return $this->response()->error($exception->getMessage());
        }
    }


    /**
     * @param array $payload
     * @return array
     */
    private function _formatedSubscriptionCreatedData(array $payload): array
    {
        $data = [
            'name' => $payload['name'],
            'duration_type' => $payload['duration_type'],
            'base_price' => $payload['base_price'],
        ];

        if(!empty($payload['discount_price']))    $data['discount_price']   = $payload['discount_price'];
        if(!empty($payload['duration']))          $data['duration']         = $payload['duration'];

        return $data;
    }


    /**
     * @param array $payload
     * @return array
     */
    private function _formatedSubscriptionUpdatedData(array $payload): array
    {
        $data = [
            'name' => $payload['name'],
            'duration_type' => $payload['duration_type'],
            'base_price' => $payload['base_price'],
        ];

        if(!empty($payload['discount_price']))    $data['discount_price']   = $payload['discount_price'];
        if(!empty($payload['duration']))          $data['duration']         = $payload['duration'];

        return $data;
    }
}
