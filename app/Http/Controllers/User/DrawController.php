<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Services\Feature\User\DrawService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DrawController extends Controller
{
    public function __construct( private readonly DrawService $service){}

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
     * @return JsonResponse
     */
    public function checkWinner(Request $request): JsonResponse
    {
        return response()->json( $this->service->checkWinner( $request->all()));
    }
}
