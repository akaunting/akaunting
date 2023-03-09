<?php

namespace App\Utilities;

use App\Abstracts\Import as AbstractsImport;
use App\Abstracts\ImportMultipleSheets;
use App\Jobs\Auth\NotifyUser;
use App\Notifications\Common\ImportCompleted;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Validators\ValidationException;
use Throwable;

class Import
{
    /**
     * Import the excel file or catch errors
     *
     * @param AbstractsImport|ImportMultipleSheets $class
     *
     * @return array
     */
    public static function fromExcel($class, Request $request, string $translation): array
    {
        $success = true;

        try {
            $should_queue = should_queue();

            $file = $request->file('import');

            if ($should_queue) {
                self::importQueue($class, $file, $translation);
            } else {
                $class->import($file);
            }

            $message = trans(
                'messages.success.' . ($should_queue ? 'import_queued' : 'imported'),
                ['type' => $translation]
            );
        } catch (Throwable $e) {
            report($e);

            $message = self::flashFailures($e);

            $success = false;
        }

        return [
            'success'   => $success,
            'error'     => ! $success,
            'data'      => null,
            'message'   => $message,
        ];
    }

    /**
     * Import the excel file
     *
     * @param AbstractsImport|ImportMultipleSheets $class
     */
    protected static function importQueue($class, $file, string $translation): void
    {
        $rows = $class->toArray($file);

        $total_rows = 0;

        if (! empty($rows[0])) {
            $total_rows = count($rows[0]);
        } else if (! empty($sheets = $class->sheets())) {
            $total_rows = count($rows[array_keys($sheets)[0]]);
        }

        $class->queue($file)->onQueue('imports')->chain([
            new NotifyUser(user(), new ImportCompleted($translation, $total_rows))
        ]);
    }

    protected static function flashFailures(Throwable $e): string
    {
        if (! $e instanceof ValidationException) {
            return $e->getMessage();
        }

        foreach ($e->failures() as $failure) {
            $message = trans('messages.error.import_column', [
                'message'   => collect($failure->errors())->first(),
                'column'    => $failure->attribute(),
                'line'      => $failure->row(),
            ]);

            flash($message)->error()->important();
        }

        return '';
    }
}
