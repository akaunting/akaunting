<?php

namespace App\Http\Controllers\Modules;

use App\Traits\Modules;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Module\Module;
use App\Abstracts\Http\Controller;

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
        $page = request('page', 1);

        $request = [
            'query' => [
                'page' => $page,
            ]
        ];

        $data = $this->getModulesByCategory($alias, $request);

        if (empty($data)) {
            return redirect()->route('apps.home.index')->send();
        }

        $title = !empty($data->category) ? $data->category->name : Str::studly($alias);
        $modules = !empty($data->modules) ? $data->modules : [];
        $installed = Module::all()->pluck('enabled', 'alias')->toArray();

        return view('modules.tiles.index', compact('title', 'modules', 'installed'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  $alias
     *
     * @return Response
     */
    public function vendorModules($alias)
    {
        $page = request('page', 1);

        $request = [
            'query' => [
                'page' => $page,
            ]
        ];

        $data = $this->getModulesByVendor($alias, $request);

        $title = !empty($data->vendor) ? $data->vendor->name : Str::studly($alias);
        $modules = !empty($data->modules) ? $data->modules : [];
        $installed = Module::all()->pluck('enabled', 'alias')->toArray();

        return view('modules.tiles.index', compact('title', 'modules', 'installed'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function paidModules()
    {
        $page = request('page', 1);

        $data = [
            'query' => [
                'page' => $page,
            ]
        ];

        $title = trans('modules.top_paid');
        $modules = $this->getPaidModules($data);
        $installed = Module::all()->pluck('enabled', 'alias')->toArray();

        return view('modules.tiles.index', compact('title', 'modules', 'installed'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function newModules()
    {
        $page = request('page', 1);

        $data = [
            'query' => [
                'page' => $page,
            ]
        ];

        $title = trans('modules.new');
        $modules = $this->getNewModules($data);
        $installed = Module::all()->pluck('enabled', 'alias')->toArray();

        return view('modules.tiles.index', compact('title', 'modules', 'installed'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function freeModules()
    {
        $page = request('page', 1);

        $data = [
            'query' => [
                'page' => $page,
            ]
        ];

        $title = trans('modules.top_free');
        $modules = $this->getFreeModules($data);
        $installed = Module::all()->pluck('enabled', 'alias')->toArray();

        return view('modules.tiles.index', compact('title', 'modules', 'installed'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function searchModules(Request $request)
    {
        $keyword = $request['keyword'];
        $page = request('page', 1);

        $data = [
            'query' => [
                'keyword' => $keyword,
                'page' => $page,
            ]
        ];

        $title = trans('general.search');
        $modules = $this->getSearchModules($data);
        $installed = Module::all()->pluck('enabled', 'alias')->toArray();

        return view('modules.tiles.index', compact('title', 'modules', 'keyword', 'installed'));
    }
}
