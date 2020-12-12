<?php

namespace App\Http\Controllers\Modules;

use App\Abstracts\Http\Controller;
use App\Models\Module\Module;
use App\Traits\Modules;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Item extends Controller
{
    use Modules;

    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-modules-item')->only('install');
        $this->middleware('permission:update-modules-item')->only('update', 'enable', 'disable');
        $this->middleware('permission:delete-modules-item')->only('uninstall');
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
            return redirect()->route('apps.home.index')->send();
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
        $alias = $request['alias'];

        $module_path = config('module.paths.modules') . '/' . Str::studly($alias);

        if (!File::isDirectory($module_path)) {
            // Download
            $steps[] = [
                'text' => trans('modules.installation.download', ['module' => $name]),
                'url'  => route('apps.download')
            ];

            // Unzip
            $steps[] = [
                'text' => trans('modules.installation.unzip', ['module' => $name]),
                'url'  => route('apps.unzip')
            ];

            // Copy
            $steps[] = [
                'text' => trans('modules.installation.file_copy', ['module' => $name]),
                'url'  => route('apps.copy')
            ];

            // Install
            $steps[] = [
                'text' => trans('modules.installation.install', ['module' => $name]),
                'url'  => route('apps.install')
            ];
        } else {
            // Install
            $steps[] = [
                'text' => trans('modules.installation.install', ['module' => $name]),
                'url'  => route('apps.install')
            ];
        }

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
    public function copy(Request $request)
    {
        $path = $request['path'];

        $json = $this->copyModule($path);

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
        $alias = $request['alias'];

        $json = $this->installModule($alias);

        if ($json['success']) {
            $message = trans('modules.installed', ['module' => $json['data']['name']]);

            flash($message)->success();
        } else {
            flash($json['message'])->error();
        }

        return response()->json($json);
    }

    public function uninstall($alias)
    {
        $json = $this->uninstallModule($alias);

        if ($json['success']) {
            $message = trans('modules.uninstalled', ['module' => $json['data']['name']]);

            flash($message)->success();
        } else {
            flash($json['message'])->error();
        }

        return redirect()->route('apps.app.show', $alias)->send();
    }

    public function enable($alias)
    {
        $json = $this->enableModule($alias);

        if ($json['success']) {
            $message = trans('modules.enabled', ['module' => $json['data']['name']]);

            flash($message)->success();
        } else {
            flash($json['message'])->error();
        }

        return redirect()->route('apps.app.show', $alias)->send();
    }

    public function disable($alias)
    {
        $json = $this->disableModule($alias);

        if ($json['success']) {
            $message = trans('modules.disabled', ['module' => $json['data']['name']]);

            flash($message)->success();
        } else {
            flash($json['message'])->error();
        }

        return redirect()->route('apps.app.show', $alias)->send();
    }

    public function reviews($alias, Request $request)
    {
        $data = [
            'query' => [
                'page' => $request->get('page', 1),
            ]
        ];

        $reviews = $this->getModuleReviews($alias, $data);

        $html = view('partials.modules.reviews', compact('reviews'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'data' => $reviews,
            'message' => null,
            'html' => $html,
        ]);
    }

    public function documentation($alias)
    {
        $documentation = $this->getDocumentation($alias);

        $back = route('apps.app.show', $alias);

        if (empty($documentation)) {
            return redirect()->route($back)->send();
        }

        return view('modules.item.documentation', compact('documentation', 'back'));
    }
}
