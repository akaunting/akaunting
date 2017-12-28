<?php

namespace App\Traits;

use MediaUploader;

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

    public function getMedia($file, $folder, $company_id = null)
    {
        if (!$company_id) {
            $company_id = session('company_id');
        }

        $path = config('filesystems.disks.uploads.root') . '/' . $company_id . '/' . $folder;

        config(['filesystems.disks.uploads.root' => $path]);

        return MediaUploader::fromSource($file)->upload();
    }
}
