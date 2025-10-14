<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Destination\DestinationStatusChangeRequest;
use App\Http\Requests\Backend\Destination\DestinationStoreRequest;
use App\Http\Requests\Backend\Destination\DestinationUpdateRequest;
use App\Http\Services\Feature\Admin\DestinationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DestinationController extends Controller
{

    public function __construct( private readonly DestinationService $service){}

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function getList(Request $request): Response|RedirectResponse
    {
        $response = $this->handleSession( $this->service->getListData( $request->query()));

        return $response ?
            Inertia::render('Admin/Tour/Destination/List/Page', $response)
            : back()->withErrors($response['message']);

    }

    /**
     * @param DestinationStoreRequest $request
     * @return RedirectResponse
     */
    public function store(DestinationStoreRequest $request): RedirectResponse
    {
        $response = $this->handleSession( $this->service->storeData( $request->all(), $request->query()));

        return $response['success'] ?
            back()->with($response)
            : back()->withErrors($response['message']);
    }


    /**
     * @param DestinationUpdateRequest $request
     * @return RedirectResponse
     */
    public function update(DestinationUpdateRequest $request): RedirectResponse
    {
        $response = $this->handleSession( $this->service->updateData( $request->all()));

        return $response['success'] ?
            back()->with($response)
            : back()->withErrors($response['message']);
    }


    /**
     * @param DestinationStatusChangeRequest $request
     * @return RedirectResponse
     */
    public function changeStatus (DestinationStatusChangeRequest $request): RedirectResponse
    {
        $response = $this->service->changeDestinationStatus( $request->all());
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
        $response = $this->service->deleteDestination( $id);
        return $response['success'] ?
            back()->with($response)
            : back()->withErrors($response['message']);
    }


    /**
     * @param string $id
     * @return RedirectResponse
     */
    public function restore(string $id): RedirectResponse
    {
        $response = $this->service->restoreDestination( $id);

        return $response['success'] ?
            back()->with($response)
            : back()->withErrors($response['message']);
    }
}
