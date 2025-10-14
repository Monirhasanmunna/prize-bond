<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Series\SeriesStatusChangeRequest;
use App\Http\Requests\Admin\Series\SeriesStoreRequest;
use App\Http\Requests\Admin\Series\SeriesUpdateRequest;
use App\Http\Requests\Backend\Destination\DestinationStatusChangeRequest;
use App\Http\Requests\Backend\Destination\DestinationStoreRequest;
use App\Http\Requests\Backend\Destination\DestinationUpdateRequest;
use App\Http\Services\Feature\Admin\SeriesService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SeriesController extends Controller
{

    public function __construct( private readonly SeriesService $service){}

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function getList(Request $request): Response|RedirectResponse
    {
        $response = $this->handleSession( $this->service->getListData( $request->query()));

        return $response['success'] ?
            Inertia::render('Admin/BondSeries/Page', $response)
            : back()->withErrors($response['message']);

    }

    /**
     * @param SeriesStoreRequest $request
     * @return RedirectResponse
     */
    public function store(SeriesStoreRequest $request): RedirectResponse
    {
        $response = $this->handleSession( $this->service->storeData( $request->all()));

        return $response['success'] ?
            back()->with($response)
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
     * @param SeriesStatusChangeRequest $request
     * @return RedirectResponse
     */
    public function changeStatus (SeriesStatusChangeRequest $request): RedirectResponse
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
