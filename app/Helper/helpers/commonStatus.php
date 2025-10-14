<?php

/**
 * @param $key
 * @return string|string[]
 */
function commonStatus ($key=null): array|string
{
    return mapHelperDataSet([
        STATUS_ACTIVE,
        STATUS_INACTIVE,
    ], $key);
}
