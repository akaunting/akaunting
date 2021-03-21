<?php

namespace Enlightn\Enlightn\Analyzers\Concerns;

use Enlightn\Enlightn\FileParser;
use Illuminate\Support\Str;

trait ParsesConfigurationFiles
{
    /**
     * Record a configuration error.
     *
     * @param  string  $config
     * @param  string  $key
     * @param  array  $after
     * @return void
     */
    public function recordError($config, $key, $after = [])
    {
        if (file_exists(
            $filePath = config(
                'enlightn.config_path',
                config_path()
            ).DIRECTORY_SEPARATOR."{$config}.php"
        )) {
            $key = Str::before($key, '.');

            $this->addTrace(
                $filePath,
                (int) FileParser::getLineNumber($filePath, ["'{$key}'", '"'.$key.'"'], $after)
            );
        } else {
            $this->markFailed();
        }
    }
}
