<?php

namespace App\Http\Controllers\Install;

use App\Abstracts\Http\Controller;
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

        $rows = module()->all();

        if ($rows) {
            foreach ($rows as $row) {
                $alias = $row->get('alias');

                if (!isset($updates[$alias])) {
                    continue;
                }

                $m = new \stdClass();
                $m->name = $row->getName();
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
            $module = module($alias);

            $name = $module->getName();

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
        $steps = [];

        $name = $request['name'];

        // Download
        $steps[] = [
            'text' => trans('modules.installation.download', ['module' => $name]),
            'url'  => url('install/updates/download')
        ];

        // Unzip
        $steps[] = [
            'text' => trans('modules.installation.unzip', ['module' => $name]),
            'url'  => url('install/updates/unzip')
        ];

        // File Copy
        $steps[] = [
            'text' => trans('modules.installation.file_copy', ['module' => $name]),
            'url'  => url('install/updates/file-copy')
        ];

        // Finish installation
        $steps[] = [
            'text' => trans('modules.installation.finish', ['module' => $name]),
            'url'  => url('install/updates/finish')
        ];

        // Redirect
        $steps[] = [
            'text' => trans('modules.installation.redirect', ['module' => $name]),
            'url'  => url('install/updates/redirect')
        ];

        return response()->json([
            'success' => true,
            'error' => false,
            'data' => $steps,
            'message' => null
        ]);
    }

    /**
     * Download the file
     *
     * @param  $request
     *
     * @return Response
     */
    public function download(Request $request)
    {
        set_time_limit(600); // 10 minutes

        $json = Updater::download($request['alias'], $request['version'], $request['installed']);

        return response()->json($json);
    }

    /**
     * Unzip the downloaded file
     *
     * @param  $request
     *
     * @return Response
     */
    public function unzip(Request $request)
    {
        set_time_limit(600); // 10 minutes

        $json = Updater::unzip($request['path'], $request['alias'], $request['version'], $request['installed']);

        return response()->json($json);
    }

    /**
     * Copy files
     *
     * @param  $request
     *
     * @return Response
     */
    public function fileCopy(Request $request)
    {
        set_time_limit(600); // 10 minutes

        $json = Updater::fileCopy($request['path'], $request['alias'], $request['version'], $request['installed']);

        return response()->json($json);
    }

    /**
     * Finish the update
     *
     * @param  $request
     *
     * @return Response
     */
    public function finish(Request $request)
    {
        $json = Updater::finish($request['alias'], $request['version'], $request['installed']);

        return response()->json($json);
    }

    /**
     * Redirect back
     *
     * @param  $request
     *
     * @return Response
     */
    public function redirect(Request $request)
    {
        return response()->json([
            'success' => true,
            'errors' => false,
            'redirect' => route('updates.index'),
            'data' => [],
        ]);
    }
}
