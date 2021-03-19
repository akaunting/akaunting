<?php

namespace App\Abstracts\Http;

use App\Abstracts\Http\Response;
use App\Traits\Jobs;
use App\Traits\Permissions;
use App\Traits\Relationships;
use Exception;
use ErrorException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Exceptions\SheetNotFoundException;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

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
     * @param $url
     *
     * @return mixed
     */
    public function importExcel($class, $request)
    {
        try {
            Excel::import($class, $request->file('import'));

            $response = [
                'success'   => true,
                'error'     => false,
                'data'      => null,
                'message'   => '',
            ];
        } catch (SheetNotFoundException | ErrorException | Exception | Throwable $e) {
            $message = $e->getMessage();

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
     * @param $file_name
     *
     * @return mixed
     */
    public function exportExcel($class, $file_name, $extension = 'xlsx')
    {
        try {
            return Excel::download($class, Str::filename($file_name) . '.' . $extension);
        } catch (ErrorException | Exception | Throwable $e) {
            flash($e->getMessage())->error()->important();

            return back();
        }
    }
}
