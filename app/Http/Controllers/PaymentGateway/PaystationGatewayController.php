<?php

namespace App\Http\Controllers\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Services\Feature\PaymentGateway\PaystationService;
use App\Http\Services\Feature\User\SeriesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PaystationGatewayController extends Controller
{
    public function __construct( private readonly PaystationService $service){}

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function payment(Request $request): JsonResponse
    {
        return response()->json( $this->service->payment( $request->all()));
    }


    /**
     * @param Request $request
     * @return array|Response
     */
    public function paymentSuccess(Request $request): array|Response
    {
        $response = $this->service->paymentSuccess( $request->query());
        dd( $response );
        return $response['success'] ?
            Inertia::render('PaymentGateway/Paystation/Success', $response):
            $this->response()->error($response['message']);

    }
}
