<?php

namespace App\Http\Controllers\Common;

use App\Abstracts\Http\Controller;
use App\Models\Common\Media;
use App\Traits\Uploads as Helper;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class Uploads extends Controller
{
    use Helper;

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
            return response(null, 204);
        }

        // Get file path
        if (!$this->getMediaPathOnStorage($media)) {
            return response(null, 204);
        }

        return $this->streamMedia($media);
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
        if (!$this->getMediaPathOnStorage($media)) {
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
        if (!$this->getMediaPathOnStorage($media)) {
            return false;
        }

        return $this->streamMedia($media);
    }

    /**
     * Destroy the specified resource.
     *
     * @param  $id
     * @return callable
     */
    public function destroy($id, Request $request)
    {
        $return = back();

        if ($request->has('ajax') && $request->get('ajax')) {
            $return = [
                'success' => true,
                'errors' => false,
                'message' => '',
                'redirect' => $request->get('redirect')
            ];
        }

        try {
            $media = Media::find($id);
        } catch (\Exception $e) {
            return $return;
        }

        // Get file path
        if (!$path = $this->getMediaPathOnStorage($media)) {
            $message = trans('messages.warning.deleted', ['name' => $media->basename, 'text' => $media->basename]);

            flash($message)->warning()->important();

            return $return;
        }

        $media->delete(); //will not delete files

        Storage::delete($path);

        if (!empty($request->input('page'))) {
            switch ($request->input('page')) {
                case 'setting':
                    setting()->set($request->input('key'), '');

                    setting()->save();
                    break;
            }
        }

        return $return;
    }
}
