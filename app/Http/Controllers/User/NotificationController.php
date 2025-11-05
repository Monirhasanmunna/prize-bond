<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Services\Feature\User\NotificationService;
use App\Http\Services\Feature\User\SeriesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct( private readonly NotificationService $service){}

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getList(Request $request): JsonResponse
    {
        return response()->json( $this->service->getListData( $request->query()));
    }


    /**
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function getData(Request $request, string $id): JsonResponse
    {
        return response()->json( $this->service->getData( $id));
    }
}
