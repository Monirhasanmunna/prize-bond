<?php

/**
 * @param $key
 * @return string|string[]
 */
function subscriptionType ($key=null): array|string
{
    return mapHelperDataSet([
        SUBSCRIPTION_MONTH,
        SUBSCRIPTION_YEAR,
        SUBSCRIPTION_LIFE_TIME,
    ], $key);
}
