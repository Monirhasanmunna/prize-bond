<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Subscription\SubscriptionStatusChangeRequest;
use App\Http\Requests\Admin\Subscription\SubscriptionStoreRequest;
use App\Http\Requests\Admin\Subscription\SubscriptionUpdateRequest;
use App\Http\Services\Feature\Admin\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SubscriptionController extends Controller
{

    public function __construct( private readonly SubscriptionService $service){}

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function getList(Request $request): Response|RedirectResponse
    {
        $response = $this->handleSession( $this->service->getListData( $request->query()));

        return $response['success'] ?
            Inertia::render('Admin/Subscription/Page', $response)
            : back()->withErrors($response['message']);

    }

    /**
     * @param SubscriptionStoreRequest $request
     * @return RedirectResponse
     */
    public function store(SubscriptionStoreRequest $request): RedirectResponse
    {
        $response = $this->handleSession( $this->service->storeData( $request->all()));

        return $response['success'] ?
            back()->with($response)
            : back()->withErrors($response['message']);
    }


    /**
     * @param SubscriptionUpdateRequest $request
     * @return RedirectResponse
     */
    public function update(SubscriptionUpdateRequest $request): RedirectResponse
    {
        $response = $this->handleSession( $this->service->updateData( $request->all()));

        return $response['success'] ?
            back()->with($response)
            : back()->withErrors($response['message']);
    }


    /**
     * @param SubscriptionStatusChangeRequest $request
     * @return RedirectResponse
     */
    public function changeStatus (SubscriptionStatusChangeRequest $request): RedirectResponse
    {
        $response = $this->service->changeStatus( $request->all());
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
