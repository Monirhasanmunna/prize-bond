<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Series\SeriesStatusChangeRequest;
use App\Http\Requests\Admin\Series\SeriesStoreRequest;
use App\Http\Requests\Admin\Series\SeriesUpdateRequest;
use App\Http\Services\Feature\User\PrizeBondService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PrizeBondController extends Controller
{

    public function __construct( private readonly PrizeBondService $service){}


    /**
     * @param Request $request
     * @return array
     */
    public function getList(Request $request): array
    {
        return $this->service->getListData( $request->query());
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
