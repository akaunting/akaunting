<?php

namespace App\Abstracts\Http;

use App\Abstracts\Http\Response;
use App\Jobs\Auth\NotifyUser;
use App\Jobs\Common\CreateMediableForExport;
use App\Notifications\Common\ImportCompleted;
use App\Traits\Jobs;
use App\Traits\Permissions;
use App\Traits\Relationships;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, Jobs, Permissions, Relationships, ValidatesRequests;

    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        $this->assignPermissionsToController();
    }

    /**
     * Generate a pagination collection.
     *
     * @param array|Collection $items
     * @param int $perPage
     * @param int $page
     * @param array $options
     *
     * @return LengthAwarePaginator
     */
    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $perPage = $perPage ?: request('limit', setting('default.list_limit', '25'));

        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    /**
     * Generate a response based on request type like HTML, JSON, or anything else.
     *
     * @param string $view
     * @param array $data
     *
     * @return \Illuminate\Http\Response
     */
    public function response($view, $data = [])
    {
        $class_name = str_replace('Controllers', 'Responses', get_class($this));

        if (class_exists($class_name)) {
            $response = new $class_name($view, $data);
        } else {
            $response = new class($view, $data) extends Response {};
        }

        return $response;
    }

    /**
     * Import the excel file or catch errors
     *
     * @param $class
     * @param $request
     * @param $translation
     *
     * @return mixed
     */
    public function importExcel($class, $request, $translation)
    {
        try {
            $file = $request->file('import');

            if (should_queue()) {
                $class->queue($file)->onQueue('imports')->chain([
                    new NotifyUser(user(), new ImportCompleted),
                ]);

                $message = trans('messages.success.import_queued', ['type' => $translation]);
            } else {
                $class->import($file);

                $message = trans('messages.success.imported', ['type' => $translation]);
            }

            $response = [
                'success'   => true,
                'error'     => false,
                'data'      => null,
                'message'   => $message,
            ];
        } catch (\Throwable $e) {
            if ($e instanceof \Maatwebsite\Excel\Validators\ValidationException) {
                foreach ($e->failures() as $failure) {
                    $message = trans('messages.error.import_column', [
                        'message'   => collect($failure->errors())->first(),
                        'column'    => $failure->attribute(),
                        'line'      => $failure->row(),
                    ]);

                    flash($message)->error()->important();
                }

                $message = '';
            } else {
                $message = $e->getMessage();
            }

            $response = [
                'success'   => false,
                'error'     => true,
                'data'      => null,
                'message'   => $message,
            ];
        }

        return $response;
    }

    /**
     * Export the excel file or catch errors
     *
     * @param $class
     * @param $translation
     * @param $extension
     *
     * @return mixed
     */
    public function exportExcel($class, $translation, $extension = 'xlsx')
    {
        try {
            $file_name = Str::filename($translation) . '.' . $extension;

            if (should_queue()) {
                $class->queue($file_name)->onQueue('exports')->chain([
                    new CreateMediableForExport(user(), $file_name),
                ]);

                $message = trans('messages.success.export_queued', ['type' => $translation]);

                flash($message)->success();

                return back();
            } else {
                return $class->download($file_name);
            }
        } catch (\Throwable $e) {
            flash($e->getMessage())->error()->important();

            return back();
        }
    }
}
