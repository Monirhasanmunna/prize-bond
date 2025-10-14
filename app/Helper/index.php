<?php

use App\Http\Services\Systems\Tool\Autoloader;

Autoloader::loadFilesRecursivelyInDirs([
    __DIR__ . '/core_constants/',
    __DIR__ . '/helpers/'
]);

