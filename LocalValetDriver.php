<?php

use Valet\Drivers\LaravelValetDriver;

class LocalValetDriver extends LaravelValetDriver
{
    public function serves(string $sitePath, string $siteName, string $uri): bool
    {
        return true;
    }

    public function frontControllerPath(string $sitePath, string $siteName, string $uri): string
    {
        return $sitePath.'/index.php';
    }

    public function isStaticFile(string $sitePath, string $siteName, string $uri)
    {
        if (file_exists($sitePath.$uri)) {
            return $sitePath.$uri;
        }
    }
}
