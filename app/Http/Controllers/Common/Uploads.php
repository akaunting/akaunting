<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Common\Media;
use Storage;
use File;

class Uploads extends Controller
{
    /**
     * Get the specified resource.
     *
     * @param  $folder
     * @param  $file
     * @return boolean|Response
     */
    public function get($id)
    {
        $media = Media::find($id);

        // Get file path
        if (!$path = $this->getPath($media)) {
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
    public function download($id)
    {
        $media = Media::find($id);

        // Get file path
        if (!$path = $this->getPath($media)) {
            return false;
        }

        return response()->download($path);
    }

    /**
     * Destroy the specified resource.
     *
     * @param  $folder
     * @param  $file
     * @return callable
     */
    public function destroy($id)
    {
        $media = Media::find($id);

        // Get file path
        if (!$path = $this->getPath($media)) {
            $message = trans('messages.warning.deleted', ['name' => $media->basename, 'text' => $media->basename]);

            flash($message)->warning();

            return back();
        }

        $media->delete(); //will not delete files

        File::delete($path);

        return back();
    }

    /**
     * Get the full path of resource.
     *
     * @param  $folder
     * @param  $file
     * @return boolean|string
     */
    protected function getPath($media)
    {
        $path = $media->basename;

        if (!empty($media->directory)) {
            $path = $media->directory . '/' . $media->basename;
        }

        if (!Storage::exists($path)) {
            return false;
        }

        $full_path = Storage::path($path);

        return $full_path;
    }
}
