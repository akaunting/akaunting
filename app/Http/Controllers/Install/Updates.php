<?php

namespace App\Http\Controllers\Install;

use App\Http\Controllers\Controller;
use App\Utilities\Updater;
use App\Utilities\Versions;
use Illuminate\Http\Request;
use Module;

class Updates extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function index()
    {
        $updates = Updater::all();

        $core = null;

        $modules = array();

        if (isset($updates['core'])) {
            $core = $updates['core'];
        }

        $rows = Module::all();

        if ($rows) {
            foreach ($rows as $row) {
                $alias = $row->get('alias');

                if (!isset($updates[$alias])) {
                    continue;
                }

                $m = new \stdClass();
                $m->name = $row->get('name');
                $m->alias = $row->get('alias');
                $m->category = $row->get('category');
                $m->installed = $row->get('version');
                $m->latest = $updates[$alias];

                $modules[] = $m;
            }
        }

        return view('install.updates.index', compact('core', 'modules'));
    }

    public function changelog()
    {
        return Versions::changelog();
    }

    /**
     * Check for updates.
     *
     * @return Response
     */
    public function check()
    {
        // Clear cache in order to check for updates
        Updater::clear();

        return redirect()->back();
    }

    /**
     * Update the core or modules.
     *
     * @param  $alias
     * @param  $version
     * @return Response
     */
    public function update($alias, $version)
    {
        if ($alias == 'core') {
            $name = 'Akaunting ' . $version;

            $installed = version('short');
        } else {
            // Get module instance
            $module = Module::findByAlias($alias);

            $name = $module->get('name');

            $installed = $module->get('version');
        }

        return view('install.updates.edit', compact('alias', 'name', 'installed', 'version'));
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
        $json = [];
        $json['step'] = [];

        $name = $request['name'];
        $version = $request['version'];

        // Download
        $json['step'][] = [
            'text' => trans('modules.installation.download', ['module' => $name]),
            'url'  => url('install/updates/download')
        ];

        // Unzip
        $json['step'][] = [
            'text' => trans('modules.installation.unzip', ['module' => $name]),
            'url'  => url('install/updates/unzip')
        ];

        // File Copy
        $json['step'][] = [
            'text' => trans('modules.installation.file_copy', ['module' => $name]),
            'url'  => url('install/updates/file-copy')
        ];

        // Migrate DB and trigger event UpdateFinish event
        $json['step'][] = [
            'text' => trans('modules.installation.migrate', ['module' => $name]),
            'url'  => url('install/updates/migrate')
        ];

        // redirect update page
        $json['step'][] = [
            'text' => trans('modules.installation.finish'),
            'url'  => url('install/updates/finish')
        ];

        return response()->json($json);
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
        set_time_limit(600); // 10 minutes

        if ($request['alias'] != 'core') {
            $this->checkApiToken();
        }

        $json = Updater::download($request['name'], $request['alias'], $request['version']);

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
        set_time_limit(600); // 10 minutes

        if ($request['alias'] != 'core') {
            $this->checkApiToken();
        }

        $json = Updater::unzip($request['name'], $request['path']);

        return response()->json($json);
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  $request
     *
     * @return Response
     */
    public function fileCopy(Request $request)
    {
        set_time_limit(600); // 10 minutes

        if ($request['alias'] != 'core') {
            $this->checkApiToken();
        }

        $json = Updater::fileCopy($request['name'], $request['alias'], $request['path'], $request['version']);

        return response()->json($json);
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  $request
     *
     * @return Response
     */
    public function migrate(Request $request)
    {
        $json = Updater::migrate($request['name'], $request['alias'], $request['version'], $request['installed']);

        return response()->json($json);
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  $request
     *
     * @return Response
     */
    public function finish(Request $request)
    {
        return response()->json([
            'success' => true,
            'errors' => false,
            'redirect' => url("install/updates"),
            'data' => [],
        ]);
    }
}
