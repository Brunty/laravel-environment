<?php
namespace Brunty\LaravelEnvironment\Helpers;

use Illuminate\Filesystem\Filesystem;

/**
 * Class FileSystemHelper
 * @package Brunty\LaravelEnvironment\Helpers
 */
class FileSystemHelper extends Filesystem
{

    /**
     * @param $path
     * @return mixed
     */
    public function includeFile($path)
    {
        return include($path);
    }

}