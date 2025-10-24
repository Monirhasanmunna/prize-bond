<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Services\Feature\User\SeriesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BondSeriesController extends Controller
{
    public function __construct( private readonly SeriesService $service){}

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getList(Request $request): JsonResponse
    {
        return response()->json( $this->service->getListData( $request->query()));
    }
}
