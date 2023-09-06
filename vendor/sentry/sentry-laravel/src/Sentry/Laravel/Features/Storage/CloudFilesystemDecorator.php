<?php

namespace Sentry\Laravel\Features\Storage;

trait CloudFilesystemDecorator
{
    use FilesystemDecorator;

    public function url($path)
    {
        return $this->withSentry(__FUNCTION__, func_get_args(), $path, compact('path'));
    }
}
