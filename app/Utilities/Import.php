<?php

namespace App\Utilities;

use Date;
use Illuminate\Validation\ValidationException;

class Import
{

    public static function createFromFile($import, $slug, $namespace = 'App')
    {
        $success = true;

        // Loop through all sheets
        $import->each(function ($sheet) use (&$success, $slug, $namespace) {
            if (!static::isValidSheetName($sheet, $slug)) {
                $message = trans('messages.error.import_sheet');

                flash($message)->error()->important();

                return false;
            }

            if (!$success = static::createFromSheet($sheet, $slug, $namespace)) {
                return false;
            }
        });

        return $success;
    }

    public static function createFromSheet($sheet, $slug, $namespace = 'App')
    {
        $success = true;

        $model = '\\' . $namespace . '\Models\\' . $slug;
        $request = '\\' . $namespace . '\Http\Requests\\' . $slug;

        if (!class_exists($model) || !class_exists($request)) {
            return false;
        }

        // Loop through all rows
        $sheet->each(function ($row, $index) use ($sheet, &$success, $model, $request) {
            $data = static::fixRow($row->toArray());

            // Set the line values so that request class could validate
            request()->merge($data);

            try {
                app($request);

                $data['company_id'] = session('company_id');

                $model::create($data);
            } catch (ValidationException $e) {
                $message = trans('messages.error.import_column', [
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

    public static function isValidSheetName($sheet, $slug)
    {
        $t = explode('\\', $slug);

        if (count($t) == 1) {
            $title = $slug;
        } else {
            if (empty($t[1])) {
                return false;
            }

            $title = $t[1];
        }

        if ($sheet->getTitle() != str_plural(snake_case($title))) {
            return false;
        }

        return true;
    }

    protected static function fixRow($data)
    {
        // Fix the date fields
        $date_fields = ['paid_at', 'due_at', 'billed_at', 'invoiced_at'];
        foreach ($date_fields as $date_field) {
            if (empty($data[$date_field])) {
                continue;
            }

            $new_date = Date::parse($data[$date_field])->format('Y-m-d') . ' ' . Date::now()->format('H:i:s');

            $data[$date_field] = $new_date;
        }

        // Make enabled field integer
        if (isset($data['enabled'])) {
            $data['enabled'] = (int) $data['enabled'];
        }

        return $data;
    }
}