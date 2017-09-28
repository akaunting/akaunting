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

        // Upload file
        $file->storeAs($company_id . '/' . $folder, $file_name);

        // Prepare db path
        $path = $folder . '/' . $file_name;

        return $path;
    }
}