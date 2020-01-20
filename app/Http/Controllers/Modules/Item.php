<?php

namespace App\Http\Controllers\Modules;

use App\Abstracts\Http\Controller;
use App\Models\Module\Module;
use App\Models\Module\ModuleHistory;
use App\Traits\Modules;
use Illuminate\Http\Request;

class Item extends Controller
{
    use Modules;

    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-modules-item')->only(['install']);
        $this->middleware('permission:update-modules-item')->only(['update', 'enable', 'disable']);
        $this->middleware('permission:delete-modules-item')->only(['uninstall']);
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  $alias
     *
     * @return Response
     */
    public function show($alias)
    {
        $enable = false;
        $installed = false;

        $module = $this->getModule($alias);

        if (empty($module)) {
            return redirect('apps/home')->send();
        }

        if ($this->moduleExists($alias) && ($model = Module::alias($alias)->first())) {
            $installed = true;

            if ($model->enabled) {
                $enable = true;
            }
        }

        if (request()->get('utm_source')) {
            $parameters = request()->all();

            $character = '?';

            if (strpos($module->action_url, '?') !== false) {
                $character = '&';
            }

            $module->action_url .= $character . http_build_query($parameters);
        }

        if ($module->status_type == 'pre_sale') {
            return view('modules.item.pre_sale', compact('module', 'installed', 'enable'));
        }

        return view('modules.item.show', compact('module', 'installed', 'enable'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  $request
     *
     * @return Response
     */
    public function steps(Request $request)
    {
        $steps = [];

        $name = $request['name'];

        // Download
        $steps[] = [
            'text' => trans('modules.installation.download', ['module' => $name]),
            'url'  => url('apps/download')
        ];

        // Unzip
        $steps[] = [
            'text' => trans('modules.installation.unzip', ['module' => $name]),
            'url'  => url('apps/unzip')
        ];

        // Download
        $steps[] = [
            'text' => trans('modules.installation.install', ['module' => $name]),
            'url'  => url('apps/install')
        ];

        return response()->json([
            'success' => true,
            'error' => false,
            'data' => $steps,
            'message' => null
        ]);
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  $request
     *
     * @return Response
     */
    public function download(Request $request)
    {
        $path = $request['path'];

        $version = $request['version'];

        $path .= '/' . $version . '/' . version('short') . '/' . setting('apps.api_key');

        $json = $this->downloadModule($path);

        return response()->json($json);
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  $request
     *
     * @return Response
     */
    public function unzip(Request $request)
    {
        $path = $request['path'];

        $json = $this->unzipModule($path);

        return response()->json($json);
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  $request
     *
     * @return Response
     */
    public function install(Request $request)
    {
        $path = $request['path'];

        $json = $this->installModule($path);

        if ($json['success']) {
            $message = trans('modules.installed', ['module' => $json['data']['name']]);

            flash($message)->success();
        }

        return response()->json($json);
    }

    public function uninstall($alias)
    {
        $json = $this->uninstallModule($alias);

        $module = Module::alias($alias)->first();

        $data = [
            'company_id' => session('company_id'),
            'module_id' => $module->id,
            'category' => $json['data']['category'],
            'version' => $json['data']['version'],
            'description' => trans('modules.uninstalled', ['module' => $json['data']['name']]),
        ];

        ModuleHistory::create($data);

        $module->delete();

        $message = trans('modules.uninstalled', ['module' => $json['data']['name']]);

        flash($message)->success();

        return redirect('apps/' . $alias)->send();
    }

    public function update($alias)
    {
        $json = $this->updateModule($alias);

        $module = Module::alias($alias)->first();

        $data = [
            'company_id' => session('company_id'),
            'module_id' => $module->id,
            'category' => $json['data']['category'],
            'version' => $json['data']['version'],
            'description' => trans_choice('modules.updated', $json['data']['name']),
        ];

        ModuleHistory::create($data);

        $message = trans('modules.updated', ['module' => $json['data']['name']]);

        flash($message)->success();

        return redirect('apps/' . $alias)->send();
    }

    public function enable($alias)
    {
        $json = $this->enableModule($alias);

        $module = Module::alias($alias)->first();

        $data = [
            'company_id' => session('company_id'),
            'module_id' => $module->id,
            'category' => $json['data']['category'],
            'version' => $json['data']['version'],
            'description' => trans('modules.enabled', ['module' => $json['data']['name']]),
        ];

        $module->enabled = 1;

        $module->save();

        ModuleHistory::create($data);

        $message = trans('modules.enabled', ['module' => $json['data']['name']]);

        flash($message)->success();

        return redirect('apps/' . $alias)->send();
    }

    public function disable($alias)
    {
        $json = $this->disableModule($alias);

        $module = Module::alias($alias)->first();

        $data = [
            'company_id' => session('company_id'),
            'module_id' => $module->id,
            'category' => $json['data']['category'],
            'version' => $json['data']['version'],
            'description' => trans('modules.disabled', ['module' => $json['data']['name']]),
        ];

        $module->enabled = 0;

        $module->save();

        ModuleHistory::create($data);

        $message = trans('modules.disabled', ['module' => $json['data']['name']]);

        flash($message)->success();

        return redirect('apps/' . $alias)->send();
    }

    public function reviews($alias, Request $request)
    {
        $page = $request['page'];

        $data = [
            'query' => [
                'page' => ($page) ? $page : 1,
            ]
        ];

        $reviews = $this->getModuleReviews($alias, $data);

        $html = view('partials.modules.reviews', compact('reviews'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'data' => null,
            'message' => null,
            'html' => $html,
        ]);
    }

    public function documentation($alias)
    {
        $documentation = $this->getDocumentation($alias);

        if (empty($documentation)) {
            return redirect('apps/' . $alias)->send();
        }

        $back = 'apps/' . $alias;

        return view('modules.item.documentation', compact('documentation', 'back'));
    }
}
