<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Storage;

class Uploads extends Controller
{
    /**
     * Show the specified resource.
     *
     * @param  $folder
     * @param  $file
     * @return boolean|Response
     */
    public function show($folder, $file)
    {
        // Get file path
        if (!$path = $this->getPath($folder, $file)) {
            return false;
        }

        return response()->file($path);
    }

    /**
     * Download the specified resource.
     *
     * @param  $folder
     * @param  $file
     * @return boolean|Response
     */
    public function download($folder, $file)
    {
        // Get file path
        if (!$path = $this->getPath($folder, $file)) {
            return false;
        }

        return response()->download($path);
    }

    /**
     * Get the full path of resource.
     *
     * @param  $folder
     * @param  $file
     * @return boolean|string
     */
    protected function getPath($folder, $file)
    {
        // Add company id
        if ($folder != 'users') {
            $folder = session('company_id') . '/' . $folder;
        }

        $path = $folder . '/' . $file;

        if (!Storage::exists($path)) {
            return false;
        }

        $full_path = Storage::path($path);

        return $full_path;
    }
}
