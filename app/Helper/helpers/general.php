<?php

/**
 * @param array $data
 * @param null $key
 * @return string|string[]
 */
function mapHelperDataSet (array $data, $key=null): array|string
{
    $output = [];
    foreach ($data as $value) {
        $output[$value] =  ucwords(str_replace('_', ' ', $value));
    }
    if (is_null($key)) {
        return $output;
    } else {
        return $output[$key];
    }
}
