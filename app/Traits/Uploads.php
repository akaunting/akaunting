<?php

namespace App\Traits;

use MediaUploader;
use App\Models\Common\Media as MediaModel;

trait Uploads
{
    public function getUploadedFilePath($file, $folder = 'settings', $company_id = null)
    {
        $path = '';

        if (!$file || !$file->isValid()) {
            return $path;
        }

        if (!$company_id) {
            $company_id = session('company_id');
        }

        $file_name = $file->getClientOriginalName();

        // Upload file
        $file->storeAs($company_id . '/' . $folder, $file_name);

        // Prepare db path
        $path = $folder . '/' . $file_name;

        return $path;
    }

    public function getMedia($file, $folder = 'settings', $company_id = null)
    {
        $path = '';

        if (!$file || !$file->isValid()) {
            return $path;
        }

        if (!$company_id) {
            $company_id = session('company_id');
        }

        $path = $company_id . '/' . $folder;

        return MediaUploader::fromSource($file)->toDirectory($path)->upload();
    }

    public function importMedia($file, $folder = 'settings', $company_id = null, $disk = null)
    {
        $path = '';

        if (!$disk) {
            $disk = config('mediable.default_disk');
        }

        if (!$company_id) {
            $company_id = session('company_id');
        }

        $path = $company_id . '/' . $folder . '/' . basename($file);

        return MediaUploader::importPath($disk, $path);
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
}
