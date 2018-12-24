<?php

namespace App\Http\Controllers\Install;

use App\Http\Controllers\Controller;
use App\Events\UpdateFinished;
use App\Utilities\Updater;
use App\Utilities\Versions;
use Illuminate\Http\Request;
use Artisan;
use Module;
use File;

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

        $status = true;

        if ($request['alias'] != 'core') {
            $this->checkApiToken();
        }

        // Download file
        if (!$data = Updater::download($request['alias'], $request['version'])) {
            $status = false;

            $message = trans('modules.errors.download', ['module' => $request['name']]);
        }

        // Create temp directory
        $path = 'temp-' . md5(mt_rand());
        $temp_path = storage_path('app/temp') . '/' . $path;

        if (!File::isDirectory($temp_path)) {
            File::makeDirectory($temp_path);
        }

        $file = $temp_path . '/upload.zip';

        // Add content to the Zip file
        $uploaded = is_int(file_put_contents($file, $data)) ? true : false;

        if (!$uploaded) {
            $status = false;

            $message = trans('modules.errors.upload', ['module' => $request['name']]);
        }

        $json = [
            'success' => ($status) ? true : false,
            'errors' => (!$status) ? $message : false,
            'data' => [
                'path' => $path
            ]
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
    public function unzip(Request $request)
    {
        set_time_limit(600); // 10 minutes

        if ($request['alias'] != 'core') {
            $this->checkApiToken();
        }

        $path = storage_path('app/temp') . '/' . $request['path'];

        $file =  $path . '/upload.zip';

        $result = Updater::unzip($file, $path);

        $json = [
            'success' => ($result) ? true : false,
            'errors' => (!$result) ? trans('modules.errors.unzip', ['module' => $request['name']]) : false,
            'data' => [
                'path' => $request['path']
            ]
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
    public function fileCopy(Request $request)
    {
        set_time_limit(600); // 10 minutes

        if ($request['alias'] != 'core') {
            $this->checkApiToken();
        }

        $path = storage_path('app/temp') . '/' . $request['path'];

        $result = Updater::fileCopy($request['alias'], $path, $request['version']);

        $json = [
            'success' => ($result) ? true : false,
            'errors' => (!$result) ? trans('modules.errors.file_copy', ['module' => $request['name']]) : false,
            'data' => [
                'path' => $request['path']
            ]
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
    public function migrate(Request $request)
    {
        // Check if the file mirror was successful
        if (($request['alias'] == 'core') && (version('short') != $request['version'])) {
            $json = [
                'success' => false,
                'errors' => trans('modules.errors.migrate core', ['module' => $request['name']]),
                'data' => []
            ];

            return response()->json($json);
        }

        // Clear cache after update
        Artisan::call('cache:clear');

        try {
            event(new UpdateFinished($request['alias'], $request['installed'], $request['version']));

            $json = [
                'success' => true,
                'errors' => false,
                'data' => []
            ];
        } catch (\Exception $e) {
            $json = [
                'success' => false,
                'errors' => trans('modules.errors.migrate', ['module' => $request['name']]),
                'data' => []
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
    public function finish(Request $request)
    {
        $json =  [
            'success' => true,
            'errors' => false,
            'redirect' => url("install/updates"),
            'data' => [],
        ];

        return response()->json($json);
    }
}
