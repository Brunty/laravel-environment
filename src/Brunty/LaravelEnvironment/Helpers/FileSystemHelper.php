<?php namespace Brunty\LaravelEnvironment\Helpers;

use Illuminate\Filesystem\Filesystem;

class FileSystemHelper extends Filesystem {

    public function includeFile($path) {
        return include($path);
    }

}