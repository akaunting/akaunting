<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Traits\Modules;
use App\Models\Module\Module;
use Illuminate\Routing\Route;
use Illuminate\Http\Request;

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
        $installed = Module::all()->pluck('status', 'alias')->toArray();

        return view('modules.tiles.index', compact('title', 'modules', 'installed'));
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
        $installed = Module::all()->pluck('status', 'alias')->toArray();

        return view('modules.tiles.index', compact('title', 'modules', 'installed'));
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
        $installed = Module::all()->pluck('status', 'alias')->toArray();

        return view('modules.tiles.index', compact('title', 'modules', 'installed'));
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
        $installed = Module::all()->pluck('status', 'alias')->toArray();

        return view('modules.tiles.index', compact('title', 'modules', 'installed'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function searchModules(Request $request)
    {
        $this->checkApiToken();

        $keyword = $request['keyword'];

        $data = [
            'query' => [
                'keyword' => $keyword,
            ]
        ];

        $title = trans('modules.search');
        $modules = $this->getSearchModules($data);
        $installed = Module::all()->pluck('status', 'alias')->toArray();

        return view('modules.tiles.index', compact('title', 'modules', 'keyword', 'installed'));
    }
}
