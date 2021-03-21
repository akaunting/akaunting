<?php

namespace Akaunting\Module\Support\Config;

class GeneratorPath
{
    private $path;
    private $generate;

    public function __construct($config)
    {
        if (is_array($config)) {
            $this->path = $config['path'];
            $this->generate = $config['generate'];

            return;
        }

        $this->path = $config;
        $this->generate = (bool) $config;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function generate() : bool
    {
        return $this->generate;
    }
}
