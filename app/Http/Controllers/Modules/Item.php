<?php

namespace App\Http\Controllers\Modules;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Module\Module;
use App\Models\Module\ModuleHistory;
use App\Traits\Modules;
use Illuminate\Routing\Route;

class Item extends Controller
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
            return redirect('modules/token/create')->send();
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
    public function show($alias)
    {
        $installed = false;
        $enable = false;

        $module = $this->getModule($alias);

        $check = Module::where('alias', $alias)->first();

        if ($check) {
            $installed = true;

            if ($check->status) {
                $enable = true;
            }
        }

        return view('modules.item.show', compact('module', 'about', 'installed', 'enable'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  $path
     *
     * @return Response
     */
    public function steps(Request $request)
    {
        $json = array();
        $json['step'] = array();

        $name = $request['name'];
        $version = $request['version'];

        // Download
        $json['step'][] = array(
            'text' => trans('modules.installation.download', ['module' => $name]),
            'url'  => url('modules/item/download')
        );

        // Unzip
        $json['step'][] = array(
            'text' => trans('modules.installation.unzip', ['module' => $name]),
            'url'  => url('modules/item/unzip')
        );

        // Download
        $json['step'][] = array(
            'text' => trans('modules.installation.install', ['module' => $name]),
            'url'  => url('modules/item/install')
        );

        return response()->json($json);
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  $path
     *
     * @return Response
     */
    public function download(Request $request)
    {
        $path = $request['path'];

        $version = $request['version'];

        $path .= '/' . $version . '/' . version('short') . '/' . setting('general.api_token');

        $json = $this->downloadModule($path);

        return response()->json($json);
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  $path
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
            Artisan::call('module:install ' . $json['data']['alias'] . ' ' . session('company_id'));

            $message = trans('messages.success.added', ['type' => trans('modules.installed', ['module' => $json['data']['name']])]);

            flash($message)->success();
        }

        return response()->json($json);
    }

    public function uninstall($alias)
    {
        $json = $this->uninstallModule($alias);

        $module = Module::where('alias', $alias)->first();

        $data = array(
            'company_id' => session('company_id'),
            'module_id' => $module->id,
            'category' => $json['data']['category'],
            'version' => $json['data']['version'],
            'description' => trans('modules.history.uninstalled', ['module' => $json['data']['name']]),
        );

        ModuleHistory::create($data);

        $module->delete();

        $message = trans('messages.success.added', ['type' => trans('modules.uninstalled', ['module' => $json['data']['name']])]);

        flash($message)->success();

        return redirect('modules/item/' . $alias)->send();
    }

    public function update($alias)
    {
        $json = $this->updateModule($alias);

        $module = Module::where('alias', $alias)->first();

        $data = array(
            'company_id' => session('company_id'),
            'module_id' => $module->id,
            'category' => $json['data']['category'],
            'version' => $json['data']['version'],
            'description' => trans_choice('modules.history.updated', $json['data']['name']),
        );

        ModuleHistory::create($data);

        $message = trans('messages.success.added', ['type' => trans('modules.updated', ['module' => $json['data']['name']])]);

        flash($message)->success();

        return redirect('modules/' . $alias)->send();
    }

    public function enable($alias)
    {
        $json = $this->enableModule($alias);

        $module = Module::where('alias', $alias)->first();

        $data = array(
            'company_id' => session('company_id'),
            'module_id' => $module->id,
            'category' => $json['data']['category'],
            'version' => $json['data']['version'],
            'description' => trans('modules.history.enabled', ['module' => $json['data']['name']]),
        );

        $module->status = 1;

        $module->save();

        ModuleHistory::create($data);

        $message = trans('messages.success.added', ['type' => trans('modules.enabled', ['module' => $json['data']['name']])]);

        flash($message)->success();

        return redirect('modules/' . $alias)->send();
    }

    public function disable($alias)
    {
        $json = $this->disableModule($alias);

        $module = Module::where('alias', $alias)->first();

        $data = array(
            'company_id' => session('company_id'),
            'module_id' => $module->id,
            'category' => $json['data']['category'],
            'version' => $json['data']['version'],
            'description' => trans('modules.history.disabled', ['module' => $json['data']['name']]),
        );

        $module->status = 0;

        $module->save();

        ModuleHistory::create($data);

        $message = trans('messages.success.added', ['type' => trans('modules.disabled', ['module' => $json['data']['name']])]);

        flash($message)->success();

        return redirect('modules/' . $alias)->send();
    }
}
