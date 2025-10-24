<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Series\SeriesStoreRequest;
use App\Http\Requests\Admin\Series\SeriesUpdateRequest;
use App\Http\Requests\User\PrizeBond\PrizeBondBulkStoreRequest;
use App\Http\Requests\User\PrizeBond\PrizeBondStoreRequest;
use App\Http\Requests\User\PrizeBond\PrizeBondUpdateRequest;
use App\Http\Services\Feature\User\PrizeBondService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PrizeBondController extends Controller
{

    public function __construct( private readonly PrizeBondService $service){}

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getList(Request $request): JsonResponse
    {
        return response()->json( $this->service->getListData( $request->query()));
    }

    /**
     * @param PrizeBondStoreRequest $request
     * @return JsonResponse
     */
    public function store(PrizeBondStoreRequest $request): JsonResponse
    {
        return response()->json( $this->service->storeData( $request->all()));
    }

    /**
     * @param PrizeBondBulkStoreRequest $request
     * @return JsonResponse
     */
    public function bulkStore(PrizeBondBulkStoreRequest $request): JsonResponse
    {
        return response()->json( $this->service->bulkStoreData( $request->all()));
    }

    /**
     * @param PrizeBondUpdateRequest $request
     * @return JsonResponse
     */
    public function update(PrizeBondUpdateRequest $request): JsonResponse
    {
        return response()->json( $this->service->updateData( $request->all()));
    }


    /**
     * @param string $id
     * @return JsonResponse
     */
    public function destroy (string $id): JsonResponse
    {
        return response()->json( $this->service->deleteData( $id));
    }
}
