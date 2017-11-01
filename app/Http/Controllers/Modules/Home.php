<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Traits\Modules;
use Illuminate\Routing\Route;

class Home extends Controller
{
    use Modules;

    /**
     * Instantiate a new controller instance.
     *
     * @param  Route  $route
     */
    public function __construct(Route $route)
    {
        if (!setting('general.api_token')) {
            return redirect('apps/token/create')->send();
        }

        parent::__construct($route);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $paid = $this->getPaidModules();
        $new = $this->getNewModules();
        $free = $this->getFreeModules();

        return view('modules.home.index', compact('paid', 'new', 'free'));
    }
}
