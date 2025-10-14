<?php

namespace App\Http\Controllers\Admin\AboutCompany;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\HeroSection\HeroSectionUpdateRequest;
use App\Http\Services\Backend\HeroSectionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HeroSectionController extends Controller
{

    public function __construct( private readonly HeroSectionService $service){}

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function getData(Request $request): Response|RedirectResponse
    {
        $response = $this->handleSession( $this->service->getData( ['page_name' => ABOUT_COMPANY_PAGE]));

        return $response['success'] ?
            Inertia::render('Admin/AboutCompany/Page', $response)
            : back()->withErrors($response['message']);
    }

    /**
     * @param HeroSectionUpdateRequest $request
     * @return RedirectResponse
     */
    public function update(HeroSectionUpdateRequest $request): RedirectResponse
    {
        $response = $this->handleSession( $this->service->updateData( $request->all()));

        return $response['success'] ?
            back()->with($response)
            : back()->withErrors($response['message']);
    }
}
