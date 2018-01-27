<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Traits\Modules;
use Illuminate\Routing\Route;

class Tiles extends Controller
{
    use Modules;

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  $alias
     *
     * @return Response
     */
    public function categoryModules($alias)
    {
        $this->checkApiToken();

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
    public function paidModules()
    {
        $this->checkApiToken();

        $title = trans('modules.top_paid');
        $modules = $this->getPaidModules();

        return view('modules.tiles.index', compact('title', 'modules'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function newModules()
    {
        $this->checkApiToken();

        $title = trans('modules.new');
        $modules = $this->getNewModules();

        return view('modules.tiles.index', compact('title', 'modules'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function freeModules()
    {
        $this->checkApiToken();

        $title = trans('modules.top_free');
        $modules = $this->getFreeModules();

        return view('modules.tiles.index', compact('title', 'modules'));
    }
}
