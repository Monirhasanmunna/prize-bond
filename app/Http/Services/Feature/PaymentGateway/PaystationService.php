<?php
namespace App\Http\Services\Feature\PaymentGateway;
use App\Models\Subscription;
use App\Models\User;
use App\Traits\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class PaystationService
{
    use Response;
    public function payment(array $payload)
    {
        try {
            $data = [
                'invoice_number' => substr(str_replace('.', '', microtime(true)), 0, 14),
                'payment_amount' => $payload['payment_amount'],
                'cust_name'      => $payload['user_name'],
                'cust_phone'     => $payload['user_phone'],
                'cust_email'     => $payload['cust_email'],
                'callback_url'   => route('paystation.success'),
                'merchantId'     => '104-1653730183',
                'password'       => 'gamecoderstorepass',
            ];

            $response = Http::asForm()->post(env('PAYSTATION_PAYMENT_GATEWAY').'/initiate-payment', $data);
            return $response->json();
        }
        catch (\Exception $e) {
            return $this->response()->error($e->getMessage());
        }
    }


    /**
     * @param array $query
     * @return array
     */
    public function paymentSuccess(array $query): array
    {
        try {
            $data = Cache::get('payment_data');

            if(!$data) {
                throw new \Exception('Payment Link Created Failed.Try Again.');
            }

            $user = User::where('id', $data['user_id'])->first();
            if(!$user) {
                throw new \Exception('User Not Found.');
            }

            $subscription = Subscription::where('id', $data['subscription_id'])->first();
            if(!$subscription) {
                throw new \Exception('Subscription Not Found.');
            }

            $user->update(['subscription_id' => $subscription->id]);

            Cache::forget('payment_data');

            return $this->response([
                    'data' => [
                        'amount' => $subscription->price,
                        'trx_id' => $query['invoice_number'],
                    ]
            ])->success("Subscription Purchased Successfully.");
        }
        catch (\Exception $e) {
            return $this->response()->error($e->getMessage());
        }
    }
}
