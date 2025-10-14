<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\Feature\Admin\AppSettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;
use Inertia\Response;

class AppSettingController extends Controller
{
    public function __construct( private readonly AppSettingService $service){}

    /**
     * @param Request $request
     * @return Response|ResponseFactory|RedirectResponse
     */
    public function showList (Request $request): Response|ResponseFactory|RedirectResponse
    {
        $response = $this->handleSession( $this->service->getListData($request->query()));

        return $response['success'] ?
            inertia('Admin/AppSetting/Page', $response)
            : back()->withErrors($response['message']);
    }


    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function update (Request $request): RedirectResponse
    {
        $response = $this->service->updateAppSetting( $request->all());
        return $response['success'] ?
            back()->with($response)
            : back()->withErrors($response['message']);
    }

}
