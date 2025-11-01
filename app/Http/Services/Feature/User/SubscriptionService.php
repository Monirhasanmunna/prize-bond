<?php
namespace App\Http\Services\Feature\User;

use App\Models\Subscription;
use App\Models\User;
use App\Traits\Request;
use App\Traits\Response;
use Bitsmind\GraphSql\Facades\QueryAssist;
use Bitsmind\GraphSql\QueryAssist as QueryAssistTrait;
use Illuminate\Support\Facades\Auth;

class SubscriptionService
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
                $query['graph'] = '{id,name,duration_type,duration,base_price,discount_price}';
            }

            $dbQuery = Subscription::where('status', STATUS_ACTIVE);
            $dbQuery = QueryAssist::queryOrderBy($dbQuery, $query);
            $dbQuery = QueryAssist::queryWhere($dbQuery, $query, ['status']);
            $dbQuery = QueryAssist::queryGraphSQL($dbQuery, $query, new Subscription);

            $subscriptions = $dbQuery->get();

            return $this->response([
                'subscriptions' => $subscriptions,
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
    public function purchaseSubscription (array $payload): array
    {
        try {
            $user = User::with('subscription')->where('id', Auth::id())->first();
            if (!$user) {
                return $this->response()->error("User not found");
            }

            if($user->subscription_id){
                return $this->response()->error("Subscription already purchased");
            }

            $subscription = Subscription::where('id', $payload['subscription_id'])->first();
            if (!$subscription) {
                return $this->response()->error("Subscription not found");
            }

            $user->update(['subscription_id' => $subscription->id]);

            return $this->response(['user' => $user->fresh(['subscription'])])->success('Subscription purchased successfully');
        }
        catch (\Exception $exception) {
            return $this->response()->error($exception->getMessage());
        }
    }
}
