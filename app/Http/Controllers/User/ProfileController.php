<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Profile\ProfileUpdateRequest;
use App\Http\Services\Feature\User\ProfileService;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    public function __construct( private readonly ProfileService $service){}

    /**
     * @param ProfileUpdateRequest $request
     * @return JsonResponse
     */
    public function update(ProfileUpdateRequest $request): JsonResponse
    {
        return response()->json( $this->service->update( $request->all()));
    }
}
