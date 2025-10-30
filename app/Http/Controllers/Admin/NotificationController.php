<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Series\SeriesStatusChangeRequest;
use App\Http\Requests\Admin\Series\SeriesStoreRequest;
use App\Http\Requests\Admin\Series\SeriesUpdateRequest;
use App\Http\Services\Feature\Admin\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class NotificationController extends Controller
{
    public function __construct( private readonly NotificationService $service){}

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function getList(Request $request): Response|RedirectResponse
    {
        $response = $this->handleSession( $this->service->getListData( $request->query()));

        return $response['success'] ?
            Inertia::render('Admin/Notification/List/Page', $response)
            : back()->withErrors($response['message']);
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        $response = $this->handleSession([]);
        return Inertia::render('Admin/Notification/Create/Page', $response);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $response = $this->handleSession( $this->service->storeData( $request->all()));

        return $response['success'] ?
            to_route('admin.notification.list')->with($response)
            : back()->withErrors($response['message']);
    }


    /**
     * @param SeriesUpdateRequest $request
     * @return RedirectResponse
     */
    public function update(SeriesUpdateRequest $request): RedirectResponse
    {
        $response = $this->handleSession( $this->service->updateData( $request->all()));

        return $response['success'] ?
            back()->with($response)
            : back()->withErrors($response['message']);
    }

    /**
     * @param string $id
     * @return RedirectResponse
     */
    public function destroy (string $id): RedirectResponse
    {
        $response = $this->service->deleteData( $id);
        return $response['success'] ?
            back()->with($response)
            : back()->withErrors($response['message']);
    }
}
