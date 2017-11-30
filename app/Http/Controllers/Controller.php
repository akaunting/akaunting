<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Route;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Instantiate a new controller instance.
     *
     * @param  Route  $route
     */
    public function __construct(Route $route)
    {
        // Get the controller array
        $arr = array_reverse(explode('\\', explode('@', $route->getAction()['uses'])[0]));

        $controller = '';

        // Add folder
        if ($arr[1] != 'controllers') {
            $controller .= kebab_case($arr[1]) . '-';
        }

        // Add file
        $controller .= kebab_case($arr[0]);

        // Skip ACL
        $skip = ['dashboard-dashboard', 'customers-dashboard'];
        if (in_array($controller, $skip)) {
            return;
        }

        // Add CRUD permission check
        $this->middleware('permission:create-' . $controller)->only(['create', 'store', 'duplicate', 'import']);
        $this->middleware('permission:read-' . $controller)->only(['index', 'show', 'edit']);
        $this->middleware('permission:update-' . $controller)->only(['update']);
        $this->middleware('permission:delete-' . $controller)->only('destroy');
    }

    public function countRelationships($model, $relationships)
    {
        $counter = array();

        foreach ($relationships as $relationship => $text) {
            if ($c = $model->$relationship()->count()) {
                $counter[] = $c . ' ' . strtolower(trans_choice('general.' . $text, ($c > 1) ? 2 : 1));
            }
        }

        return $counter;
    }

    /**
     * Check for api token and redirect if empty.
     *
     * @return mixed
     */
    public function checkApiToken()
    {
        if (setting('general.api_token')) {
            return;
        }

        redirect('apps/token/create')->send();
    }
}
