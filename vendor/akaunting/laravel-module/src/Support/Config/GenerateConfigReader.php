<?php

namespace Akaunting\Module\Support\Config;

class GenerateConfigReader
{
    public static function read(string $value) : GeneratorPath
    {
        return new GeneratorPath(config("module.paths.generator.$value"));
    }
}
