<?php

namespace Tests\Feature;

use Tests\TestCase;

class AssetLoadingTest extends TestCase
{
    public function test_blade_assets_do_not_use_public_prefix()
    {
        $viewsPath = resource_path('views');
        $filesWithPublicPrefix = [];

        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($viewsPath));
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $content = file_get_contents($file->getPathname());
                if (preg_match("/asset\(\s*['\"]\/?public\//", $content)) {
                    $filesWithPublicPrefix[] = str_replace(base_path() . '/', '', $file->getPathname());
                }
            }
        }

        $this->assertEmpty(
            $filesWithPublicPrefix,
            "Found blade files using asset('public/...') which breaks php artisan serve (document root is public/):\n" . implode("\n", $filesWithPublicPrefix)
        );
    }

    public function test_no_double_slash_in_asset_paths()
    {
        $viewsPath = resource_path('views');
        $filesWithDoubleSlash = [];

        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($viewsPath));
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $content = file_get_contents($file->getPathname());
                if (strpos($content, "asset('public/css//") !== false || strpos($content, 'asset("public/css//') !== false) {
                    $filesWithDoubleSlash[] = str_replace(base_path() . '/', '', $file->getPathname());
                }
            }
        }

        $this->assertEmpty($filesWithDoubleSlash, "Found asset paths with double slash: " . implode(", ", $filesWithDoubleSlash));
    }
}
