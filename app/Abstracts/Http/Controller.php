<?php

namespace App\Abstracts\Http;

use App\Traits\Jobs;
use App\Traits\Relationships;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Routing\Route;
use Illuminate\Support\Str;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, Jobs, Relationships, ValidatesRequests;

    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        $this->setPermissions();
    }

    /**
     * Assign permissions to methods.
     *
     * @return void
     */
    public function setPermissions()
    {
        // No need to check for permission in console
        if (app()->runningInConsole()) {
            return;
        }

        $route = app(Route::class);

        // Get the controller array
        $arr = array_reverse(explode('\\', explode('@', $route->getAction()['uses'])[0]));

        $controller = '';

        // Add folder
        if (strtolower($arr[1]) != 'controllers') {
            $controller .= Str::kebab($arr[1]) . '-';
        }

        // Add module
        if (isset($arr[3]) && isset($arr[4]) && (strtolower($arr[4]) == 'modules')) {
            $controller .= Str::kebab($arr[3]) . '-';
        }

        // Add file
        $controller .= Str::kebab($arr[0]);

        // Skip ACL
        $skip = ['portal-dashboard'];
        if (in_array($controller, $skip)) {
            return;
        }

        // Add CRUD permission check
        $this->middleware('permission:create-' . $controller)->only(['create', 'store', 'duplicate', 'import']);
        $this->middleware('permission:read-' . $controller)->only(['index', 'show', 'edit', 'export']);
        $this->middleware('permission:update-' . $controller)->only(['update', 'enable', 'disable']);
        $this->middleware('permission:delete-' . $controller)->only('destroy');
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
}
