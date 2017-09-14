<?php

namespace App\Traits;

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

        $path = 'storage/app/' . $file->storeAs('uploads/' . $company_id . '/' . $folder, $file_name);

        return $path;
    }
}