<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Common\Media;
use File;
use Storage;

class Uploads extends Controller
{
    /**
     * Get the specified resource.
     *
     * @param  $id
     * @return mixed
     */
    public function get($id)
    {
        try {
            $media = Media::find($id);
        } catch (\Exception $e) {
            return false;
        }

        // Get file path
        if (!$path = $this->getPath($media)) {
            return false;
        }

        return response()->file($path);
    }

    /**
     * Get the specified resource.
     *
     * @param  $id
     * @return mixed
     */
    public function show($id, Request $request)
    {
        $file = false;
        $options = false;
        $column_name = 'attachment';

        if ($request->has('column_name')) {
            $column_name = $request->get('column_name');
        }

        if ($request->has('page')) {
            $options = [
                'page' => $request->get('page'),
                'key' => $request->get('key'),
            ];
        }

        try {
            $media = Media::find($id);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => true,
                'data'    => [],
                'message' => 'null',
                'html'    => '',
            ]);
        }

        // Get file path
        if (!$path = $this->getPath($media)) {
            return response()->json([
                'success' => false,
                'error'   => true,
                'data'    => [],
                'message' => 'null',
                'html'    => '',
            ]);
        }

        $file = $media;

        $html = view('partials.media.file', compact('file', 'column_name', 'options'))->render();

        return response()->json([
            'success' => true,
            'error'   => false,
            'data'    => [],
            'message' => 'null',
            'html'    => $html,
        ]);
    }

    /**
     * Download the specified resource.
     *
     * @param  $id
     * @return mixed
     */
    public function download($id)
    {
        try {
            $media = Media::find($id);
        } catch (\Exception $e) {
            return false;
        }

        // Get file path
        if (!$path = $this->getPath($media)) {
            return false;
        }

        return response()->download($path);
    }

    /**
     * Destroy the specified resource.
     *
     * @param  $id
     * @return callable
     */
    public function destroy($id, Request $request)
    {
        try {
            $media = Media::find($id);
        } catch (\Exception $e) {
            return back();
        }

        // Get file path
        if (!$path = $this->getPath($media)) {
            $message = trans('messages.warning.deleted', ['name' => $media->basename, 'text' => $media->basename]);

            flash($message)->warning();

            return back();
        }

        $media->delete(); //will not delete files

        File::delete($path);

        if (!empty($request->input('page'))) {
            switch ($request->input('page')) {
                case 'setting':
                    setting()->set($request->input('key'), '');

                    setting()->save();
                    break;
            }
        }

        return back();
    }

    /**
     * Get the full path of resource.
     *
     * @param  $media
     * @return boolean|string
     */
    protected function getPath($media)
    {
        if (!is_object($media)) {
            return false;
        }

        $path = $media->basename;

        if (!empty($media->directory)) {
            $folders = explode('/', $media->directory);

            // Check if company can access media
            if ($folders[0] != session('company_id')) {
                return false;
            }

            $path = $media->directory . '/' . $media->basename;
        }

        if (!Storage::exists($path)) {
            return false;
        }

        $full_path = Storage::path($path);

        return $full_path;
    }
}
