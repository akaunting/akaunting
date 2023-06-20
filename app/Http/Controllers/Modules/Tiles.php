<?php

namespace App\Http\Controllers\Modules;

use App\Traits\Modules;
use App\Models\Module\Module;
use App\Abstracts\Http\Controller;
use Illuminate\Support\Str;
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
        $page = request('page', 1);

        $data = [
            'query' => [
                'page' => $page,
            ]
        ];

        $data = $this->getModulesByCategory($alias, $data);

        if (empty($data)) {
            return redirect()->route('apps.home.index')->send();
        }

        $title = !empty($data->category) ? $data->category->name : Str::studly($alias);
        $modules = !empty($data->modules) ? $data->modules : [];
        $installed = Module::all()->pluck('enabled', 'alias')->toArray();

        return $this->response('modules.tiles.index', compact('modules', 'title', 'installed'));
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

        $data = [
            'query' => [
                'page' => $page,
            ]
        ];

        $data = $this->getModulesByVendor($alias, $data);

        $title = !empty($data->vendor) ? $data->vendor->name : Str::studly($alias);
        $modules = !empty($data->modules) ? $data->modules : [];
        $installed = Module::all()->pluck('enabled', 'alias')->toArray();

        return $this->response('modules.tiles.index', compact('modules', 'title', 'installed'));
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

        return $this->response('modules.tiles.index', compact('modules', 'title', 'installed'));
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

        return $this->response('modules.tiles.index', compact('modules', 'title', 'installed'));
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

        return $this->response('modules.tiles.index', compact('modules', 'title','installed'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function searchModules(Request $request)
    {
        $keyword = $request->get('keyword');
        $page = $request->get('page', 1);

        $data = [
            'query' => [
                'keyword' => $keyword,
                'page' => $page,
            ]
        ];

        $title = trans('general.search');
        $modules = $this->getSearchModules($data);
        $installed = Module::all()->pluck('enabled', 'alias')->toArray();

        return $this->response('modules.tiles.index', compact('modules', 'title', 'keyword', 'installed'));
    }

    public function loadMore($type, Request $request)
    {
        $page = $request->get('page', 1);

        $modules = [];

        $data = [
            'query' => [
                'page' => $page,
            ]
        ];

        $last_page = 1;

        switch ($type) {
            case 'categories':
                $alias = $request->get('alias');
                $response = $this->getModulesByCategory($alias, $data);

                $response = !empty($response->modules) ? $response->modules : [];
                $last_page = ! empty($response) ? $response->last_page : 1;

                $modules = $this->prepareModules($response);
                break;
            case 'vendors':
                $alias = $request->get('alias');
                $response = $this->getModulesByVendor($alias, $data);

                $response = !empty($response->modules) ? $response->modules : [];
                $last_page = ! empty($response) ? $response->last_page : 1;

                $modules = $this->prepareModules($response);
                break;
            case 'paid':
                $response = $this->getPaidModules($data);

                $last_page = ! empty($response) ? $response->last_page : 1;
                $modules = $this->prepareModules($response);
                break;
            case 'new':
                $response = $this->getNewModules($data);

                $last_page = ! empty($response) ? $response->last_page : 1;
                $modules = $this->prepareModules($response);
                break;
            case 'free':
                $response = $this->getFreeModules($data);

                $last_page = ! empty($response) ? $response->last_page : 1;
                $modules = $this->prepareModules($response);
                break;
            case 'search':
                $data['query']['keyword'] = $request->get('keyword');

                $response = $this->getSearchModules($data);

                $last_page = ! empty($response) ? $response->last_page : 1;
                $modules = $this->prepareModules($response);
                break;
        }

        $html = view('components.modules.raw_items', compact('modules'))->render();

        return response()->json([
            'success'   => true,
            'error'     => false,
            'message'   => 'null',
            'modules'   => $modules,
            'last_page' => $last_page,
            'html'      => $html,
        ]);
    }

    protected function prepareModules($response)
    {
        if (! empty($response->data)) {
            return $response->data;
        }

        return $response;
    }
}
