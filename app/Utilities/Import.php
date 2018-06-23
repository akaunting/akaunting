<?php

namespace App\Utilities;

use Illuminate\Validation\ValidationException;

class Import
{

    public static function createFromFile($import, $slug)
    {
        $success = true;

        // Loop through all sheets
        $import->each(function ($sheet) use (&$success, $slug) {
            if (!$success = static::createFromSheet($sheet, $slug)) {
                return false;
            }
        });

        return $success;
    }

    public static function createFromSheet($sheet, $slug)
    {
        $success = true;

        $model = '\App\Models\\' . $slug;
        $request = '\App\Http\Requests\\' . $slug;

        if (!class_exists($model) || !class_exists($request)) {
            return false;
        }

        // Loop through all rows
        $sheet->each(function ($row, $index) use ($sheet, &$success, $model, $request) {
            $data = $row->toArray();

            // Set the line values so that request class could validate
            request()->merge($data);

            try {
                app($request);

                $data['company_id'] = session('company_id');

                $model::create($data);
            } catch (ValidationException $e) {
                $message = trans('messages.error.import_failed', [
                    'message' => $e->validator->errors()->first(),
                    'sheet' => $sheet->getTitle(),
                    'line' => $index + 2,
                ]);

                flash($message)->error()->important();

                $success = false;

                // Break the import process
                return false;
            }

            // Unset added line values
            foreach ($data as $key => $value) {
                request()->offsetUnset($key);
            }
        });

        return $success;
    }

}