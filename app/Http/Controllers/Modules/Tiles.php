<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Traits\Modules;
use Illuminate\Routing\Route;

class Tiles extends Controller
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
     * Show the form for viewing the specified resource.
     *
     * @param  $alias
     *
     * @return Response
     */
    public function category($alias)
    {
        $data = $this->getModulesByCategory($alias);

        $title = $data->category->name;
        $modules = $data->modules;

        return view('modules.tiles.index', compact('title', 'modules'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function paid()
    {
        $title = trans('modules.top_paid');
        $modules = $this->getPaidModules();

        return view('modules.tiles.index', compact('title', 'modules'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function new()
    {
        $title = trans('modules.new');
        $modules = $this->getNewModules();

        return view('modules.tiles.index', compact('title', 'modules'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function free()
    {
        $title = trans('modules.top_free');
        $modules = $this->getFreeModules();

        return view('modules.tiles.index', compact('title', 'modules'));
    }
}
