<?php

namespace App\Http\Controllers\Api\Common;

use App\Abstracts\Http\ApiController;
use App\Models\Module\Module;
use Dingo\Api\Http\Response;
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
     * @return \Dingo\Api\Http\Response
     */
    public function file($locale, $file)
    {
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
     * @return \Dingo\Api\Http\Response
     */
    public function all($locale)
    {
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
