<?php

namespace App\Utilities;

use Maatwebsite\Excel\Files\ExcelFile;
use Storage;

class ImportFile extends ExcelFile
{

    public function getFile()
    {
        $request = request();

        if (!$request->hasFile('import')) {
            flash(trans('messages.error.no_file'))->error();

            redirect()->back()->send();
        }

        $folder = session('company_id') . '/imports';

        // Upload file
        $path = Storage::path($request->file('import')->store($folder));

        return $path;
    }

}