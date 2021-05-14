<?php

namespace App\Traits;

use App\Models\Common\Media as MediaModel;
use App\Utilities\Date;
use Illuminate\Support\Facades\Storage;
use MediaUploader;

trait Uploads
{
    public $company_id_index = 3;

    public function getMedia($file, $folder = 'settings', $company_id = null)
    {
        $path = '';

        if (!$file || !$file->isValid()) {
            return $path;
        }

        $path = $this->getMediaFolder($folder, $company_id);

        return MediaUploader::makePrivate()->fromSource($file)->toDirectory($path)->upload();
    }

    public function importMedia($file, $folder = 'settings', $company_id = null, $disk = null)
    {
        $path = '';

        if (!$disk) {
            $disk = config('mediable.default_disk');
        }

        $path = $this->getMediaFolder($folder, $company_id) . '/' . basename($file);

        return MediaUploader::makePrivate()->importPath($disk, $path);
    }

    public function deleteMediaModel($model, $parameter, $request = null)
    {
        $medias = $model->$parameter;

        if (!$medias) {
            return;
        }

        $already_uploaded = [];

        if ($request && isset($request['uploaded_' . $parameter])) {
            $uploaded = $request['uploaded_' . $parameter];

            if (count($medias) == count($uploaded)) {
                return;
            }

            foreach ($uploaded as $old_media) {
                $already_uploaded[] = $old_media['id'];
            }
        }

        foreach ((array)$medias as $media) {
            if (in_array($media->id, $already_uploaded)) {
                continue;
            }

            MediaModel::where('id', $media->id)->delete();
        }
    }

    public function getMediaFolder($folder, $company_id = null)
    {
        if (!$company_id) {
            $company_id = company_id();
        }

        $date = Date::now()->format('Y/m/d');

        // 2021/04/09/34235/invoices
        return $date . '/' . $company_id . '/' . $folder;
    }

    public function getMediaPathOnStorage($media)
    {
        if (!is_object($media)) {
            return false;
        }

        $path = $media->basename;

        if (!empty($media->directory)) {
            // 2021/04/09/34235/invoices
            $folders = explode('/', $media->directory);

            // No company_id in folder path
            if (empty($folders[$this->company_id_index])) {
                return false;
            }

            // Check if company can access media
            if ($folders[$this->company_id_index] != company_id()) {
                return false;
            }

            $path = $media->directory . '/' . $media->basename;
        }

        if (!Storage::exists($path)) {
            return false;
        }

        return Storage::path($path);
    }

    public function streamMedia($media, $path = '')
    {
        return response()->streamDownload(
            function() use ($media) {
                $stream = $media->stream();

                while ($bytes = $stream->read(1024)) {
                    echo $bytes;
                }
            },
            $media->basename,
            [
                'Content-Type'      => $media->mime_type,
                'Content-Length'    => $media->size,
            ],
        );
    }

    public function isLocalStorage()
    {
        return config('filesystems.disks.' . config('filesystems.default') . '.driver') == 'local';
    }
}
