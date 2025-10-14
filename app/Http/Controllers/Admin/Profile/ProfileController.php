<?php

namespace App\Http\Controllers\Admin\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Profile\ProfileUpdateRequest;
use App\Http\Services\Feature\Admin\ProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{

    public function __construct( private readonly ProfileService $service){}

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function getData(Request $request): Response|RedirectResponse
    {
        $response = $this->handleSession( $this->service->getData());

        return $response['success'] ?
            Inertia::render('Admin/Profile/Page', $response)
            : back()->withErrors($response['message']);
    }


    /**
     * @param ProfileUpdateRequest $request
     * @return RedirectResponse
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $response = $this->handleSession( $this->service->updateData( $request->all()));

        return $response['success'] ?
            back()->with($response)
            : back()->withErrors($response['message']);
    }
}
