<?php
namespace App\Http\Services\Feature\User;

use App\Http\Services\Feature\PaymentGateway\PaystationService;
use App\Models\Subscription;
use App\Models\User;
use App\Traits\Request;
use App\Traits\Response;
use Bitsmind\GraphSql\Facades\QueryAssist;
use Bitsmind\GraphSql\QueryAssist as QueryAssistTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SubscriptionService
{
    use Request,Response, QueryAssistTrait;

    public function __construct(private readonly PaystationService $paymentGateway){}

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

            $paymentInfo = [
                'payment_amount' => (float) $subscription->discount_price > 0  ? $subscription->discount_price : $subscription->base_price,
                'user_name'  => $user->name,
                'user_phone' => $user->phone,
                'cust_email' => $user->email,
            ];

            $response = $this->paymentGateway->payment( $paymentInfo);
            if($response['status_code'] === "200" && $response['status'] === 'success'){
                Cache::put('payment_data', [
                    'subscription_id' => $payload['subscription_id'],
                    'user_id' => $user->id,
                ], now()->addMinutes(10));

                return $this->response(['payment_url' => $response['payment_url']])->success("Payment Link Created Successfully.");
            }

            return $this->response()->error('Payment Link Created Failed.');
        }
        catch (\Exception $exception) {
            return $this->response()->error($exception->getMessage());
        }
    }
}
