<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\Feature\Admin\DashboardService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(private readonly DashboardService $dashboardService){}


    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function Home(Request $request): Response|RedirectResponse
    {
        $response = $this->dashboardService->Home($request->query());
        return $response['success'] ?
            Inertia::render('Admin/Dashboard/Page', $response) :
            back()->withErrors($response['message']);
    }
}
