<?php

namespace App\Http\Controllers\Api\Common;

use App\Abstracts\Http\ApiController;
use App\Models\Module\Module;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class Translations extends ApiController
{
    /**
     * Display the specified resource.
     *
     * @param  string  $locale
     * @param  string  $file
     * @return \Illuminate\Http\JsonResponse
     */
    public function file($locale, $file)
    {
        // Security: validate locale format (e.g. en-US, tr-TR) to prevent path traversal.
        if (! preg_match('/^[a-z]{2}-[A-Z]{2}$/', $locale)) {
            abort(404);
        }

        // Security: validate file name format — only alphanumeric, underscores,
        // hyphens, and optional module namespace (e.g. "my-blog::general").
        // This prevents path traversal via trans('../../../config/app').
        if (! preg_match('/^([a-z0-9_-]+)(::[a-z0-9_-]+)?$/', $file)) {
            abort(404);
        }

        App::setLocale($locale);

        return response()->json([
            'success' => true,
            'error' => false,
            'message' => 'Get file translation',
            'data' => trans($file),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $locale
     * @return \Illuminate\Http\JsonResponse
     */
    public function all($locale)
    {
        // Security: validate locale format (e.g. en-US, tr-TR) to prevent
        // path traversal in the filesystem glob below.
        if (! preg_match('/^[a-z]{2}-[A-Z]{2}$/', $locale)) {
            abort(404);
        }

        App::setLocale($locale);

        $translations = [];

        $filesystem = app(Filesystem::class);

        $path = base_path('resources/lang/' . $locale);

        foreach ($filesystem->glob("{$path}/*") as $file_system) {
            $file = str_replace('.php', '', basename($file_system));

            $translations[$file] = trans($file);
        }

        $modules = Module::enabled()->get();

        foreach ($modules as $module) {
            $path = base_path('modules/' . Str::studly($module->alias) . '/Resources/lang/' . $locale);

            foreach ($filesystem->glob("{$path}/*") as $file_system) {
                $file = str_replace('.php', '', basename($file_system));

                $translations[$module->alias . '::' . $file] = trans($file);
            }
        }

        return response()->json([
            'success' => true,
            'error' => false,
            'message' => 'Get all translation',
            'data' => $translations,
        ]);
    }
}
