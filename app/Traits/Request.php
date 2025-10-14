<?php

namespace App\Traits;

trait Request
{
    /**
     * @var array
     */
    private array $queryParams = [];
    /**
     * @var array
     */
    private array $keys = [];
    /**
     * @var bool
     */
    private bool $strict = false;

    /**
     * @param array $queryParams
     * @return $this
     */
    protected function queryParams (array $queryParams): object
    {
        $this->queryParams = $queryParams;
        return $this;
    }

    /**
     * @return object
     */
    protected function strictly (): object
    {
        $this->strict = true;
        return $this;
    }

    /**
     * @param array $keys
     * @return bool
     */
    protected function only (array $keys): bool
    {
        if ($this->strict) {
            foreach ($keys as $key) {
                if (!array_key_exists($key, $this->queryParams)) return false;
            }
        }
        foreach ($this->queryParams as $param => $val) {
            if (!in_array($param, $keys)) return false;
        }
        return true;
    }

    /**
     * @param array $keys
     * @return string|null
     */
    protected function required (array $keys): ?string
    {
        $missing = [];

        foreach ($keys as $key) if (!array_key_exists($key, $this->queryParams)) $missing [] = $key;

        if (count($missing)) {
            $lastMissing = array_pop($missing);

            $errorMessage = count($missing) ?
                implode(', ', $missing) . ' and ' . $lastMissing . ' are'
                : $lastMissing . ' is';
            $errorMessage .= ' mandatory query params';

            return $errorMessage;
        }

        return null;
    }
}
