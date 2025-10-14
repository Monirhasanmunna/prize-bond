<?php
namespace App\Http\Services\Systems\Tool;

class Autoloader
{
    public static function loadFilesRecursivelyInDirs($directories): void
    {
        foreach ($directories as $directory) {
            $entities = array_diff(scandir($directory), ['.', '..']);
            foreach ($entities as $entity) {
                $path = str_replace('//', '/', $directory . '/' . $entity);
                if (is_dir($path)) {
                    self::loadFilesRecursivelyInDirs([$path]);
                } elseif (is_file($path) && pathinfo($path, PATHINFO_EXTENSION) === 'php') {
                    require $path;
                }
            }
        }
    }

}
