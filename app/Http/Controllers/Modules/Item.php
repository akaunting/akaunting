<?php

namespace App\Http\Controllers\Modules;

use App\Abstracts\Http\Controller;
use App\Jobs\Install\CopyFiles;
use App\Jobs\Install\DisableModule;
use App\Jobs\Install\DownloadFile;
use App\Jobs\Install\EnableModule;
use App\Jobs\Install\InstallModule;
use App\Jobs\Install\UninstallModule;
use App\Jobs\Install\UnzipFile;
use App\Models\Module\Module;
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

        if ($this->moduleExists($alias)) {
            // Install
            $steps[] = [
                'text' => trans('modules.installation.install', ['module' => $name]),
                'url'  => route('apps.install')
            ];
        } else {
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
        try {
            $path = $this->dispatch(new DownloadFile($request['alias'], $request['version']));

            $json = [
                'success' => true,
                'error' => false,
                'message' => null,
                'data' => [
                    'path' => $path,
                ],
            ];
        } catch (\Exception $e) {
            $json = [
                'success' => false,
                'error' => true,
                'message' => $e->getMessage(),
                'data' => [],
            ];
        }

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
        try {
            $path = $this->dispatch(new UnzipFile($request['alias'], $request['path']));

            $json = [
                'success' => true,
                'error' => false,
                'message' => null,
                'data' => [
                    'path' => $path,
                ],
            ];
        } catch (\Exception $e) {
            $json = [
                'success' => false,
                'error' => true,
                'message' => $e->getMessage(),
                'data' => [],
            ];
        }

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
        try {
            $this->dispatch(new CopyFiles($request['alias'], $request['path']));

            event(new \App\Events\Module\Copied($request['alias'], company_id()));

            $json = [
                'success' => true,
                'error' => false,
                'message' => null,
                'data' => [
                    'alias' => $request['alias'],
                ],
            ];
        } catch (\Exception $e) {
            $json = [
                'success' => false,
                'error' => true,
                'message' => $e->getMessage(),
                'data' => [],
            ];
        }

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
        try {
            event(new \App\Events\Module\Installing($request['alias'], company_id()));

            $this->dispatch(new InstallModule($request['alias'], company_id()));

            $name = module($request['alias'])->getName();

            $message = trans('modules.installed', ['module' => $name]);

            flash($message)->success();

            $json = [
                'success' => true,
                'error' => false,
                'message' => null,
                'redirect' => route('apps.app.show', $request['alias']),
                'data' => [
                    'name' => $name,
                    'alias' => $request['alias'],
                ],
            ];
        } catch (\Exception $e) {
            $message = $e->getMessage();

            flash($message)->error()->important();

            $json = [
                'success' => false,
                'error' => true,
                'message' => $message,
                'data' => [],
            ];
        }

        return response()->json($json);
    }

    public function uninstall($alias)
    {
        try {
            $name = module($alias)->getName();

            $this->dispatch(new UninstallModule($alias, company_id()));

            $message = trans('modules.uninstalled', ['module' => $name]);

            flash($message)->success();
        } catch (\Exception $e) {
            $message = $e->getMessage();

            flash($message)->error()->important();
        }

        return redirect()->route('apps.app.show', $alias)->send();
    }

    public function enable($alias)
    {
        try {
            $name = module($alias)->getName();

            $this->dispatch(new EnableModule($alias, company_id()));

            $message = trans('modules.enabled', ['module' => $name]);

            flash($message)->success();
        } catch (\Exception $e) {
            $message = $e->getMessage();

            flash($message)->error()->important();
        }

        return redirect()->route('apps.app.show', $alias)->send();
    }

    public function disable($alias)
    {
        try {
            $name = module($alias)->getName();

            $this->dispatch(new DisableModule($alias, company_id()));

            $message = trans('modules.disabled', ['module' => $name]);

            flash($message)->success();
        } catch (\Exception $e) {
            $message = $e->getMessage();

            flash($message)->error()->important();
        }

        return redirect()->route('apps.app.show', $alias)->send();
    }

    public function releases($alias, Request $request)
    {
        $data = [
            'query' => [
                'page' => $request->get('page', 1),
            ]
        ];

        $releases = $this->getModuleReleases($alias, $data);

        $html = view('partials.modules.releases', compact('releases'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'data' => $releases,
            'message' => null,
            'html' => $html,
        ]);
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
        $documentation = $this->getModuleDocumentation($alias);

        $back = route('apps.app.show', $alias);

        if (empty($documentation)) {
            return redirect()->route($back)->send();
        }

        return view('modules.item.documentation', compact('documentation', 'back'));
    }
}
