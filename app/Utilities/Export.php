<?php

namespace App\Utilities;

use App\Jobs\Common\CreateMediableForExport;
use Illuminate\Support\Str;
use Throwable;

class Export
{
    /**
     * Export the excel file or catch errors
     *
     * @param $class
     * @param $translation
     * @param $extension
     *
     * @return mixed
     */
    public static function toExcel($class, $translation, $extension = 'xlsx')
    {
        try {
            $file_name = Str::filename($translation) . '-' . time() . '.' . $extension;

            //Todo: This improvement solves the filter issue on multiple excel sheets. This solution is a temporary solution.
            if (empty($class->ids) && method_exists($class, 'sheets') && is_array($sheets = $class->sheets())) {
                $class->ids = ! empty($ids = (new $sheets[array_key_first($sheets)])->collection()->pluck('id')->toArray()) ? $ids : [0];
            }

            if (should_queue()) {
                $disk = 'temp';

                if (config('excel.temporary_files.remote_disk') !== null) {
                    $disk = config('excel.temporary_files.remote_disk');
                    $file_name = config('excel.temporary_files.remote_prefix') . $file_name;
                }

                $class->queue($file_name, $disk)->onQueue('exports')->chain([
                    new CreateMediableForExport(user(), $file_name, $translation),
                ]);

                $message = trans('messages.success.export_queued', ['type' => $translation]);

                flash($message)->success();

                return back();
            } else {
                return $class->download($file_name);
            }
        } catch (Throwable $e) {
            report($e);
            flash($e->getMessage())->error()->important();

            return back();
        }
    }
}
