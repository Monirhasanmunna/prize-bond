<?php

namespace App\Http\Controllers;

use App\Traits\Response;

abstract class Controller
{
    use Response;
    /**
     * @param array $serviceResponse
     * @return array
     */
    protected function handleSession (array $serviceResponse): array
    {
        if (session()->has('success')) {
            if (session('success')) {
                $serviceResponse['message'] = session('message');
            } else {
                return $this->response()->error(session('message'));
            }
        }

        return $serviceResponse;
    }
}
