<?php

namespace App\Traits;

trait Response
{
    private array $response = [
      'success' => false,
      'message' => '',
      'data' => [],
    ];


    /**
     * @param array $data
     * @return object
     */
    protected function response (array $data = []): object
    {
        $this->response['data'] = $data;

        return $this;
    }

    /**
     * @param string|null $message
     * @return array
     */
    protected function success (string $message = null): array
    {
        $this->response['success'] = true;
        $this->response['message'] = $message;

        return $this->response;
    }


    /**
     * @param string|null $message
     * @return array
     */
    protected function error (string $message = null): array
    {
        $this->response['success'] = false;
        $this->response['message'] = $message;

        return $this->response;
    }
}
