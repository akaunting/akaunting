<?php

namespace App\Http\Controllers\Install;

use Artisan;
use Config;
use DB;
use DotenvEditor;
use App\Http\Requests\Install\Database as Request;
use Illuminate\Routing\Controller;

class Database extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('install.database.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        // Check database connection
        if (!$this->canConnect($request)) {
            $message = trans('install.error.connection');

            flash($message)->error()->important();

            return redirect('install/database')->withInput();
        }

        // Set database details
        $this->saveVariables($request);

        // Try to increase the maximum execution time
        set_time_limit(300); // 5 minutes

        // Create tables
        Artisan::call('migrate', ['--force' => true]);

        // Create Roles
        Artisan::call('db:seed', ['--class' => 'Database\Seeds\Roles', '--force' => true]);

        return redirect('install/settings');
    }

    private function canConnect($request)
    {
        Config::set('database.connections.install_test', [
            'host'      => $request['hostname'],
            'database'  => $request['database'],
            'username'  => $request['username'],
            'password'  => $request['password'],
            'driver'    => env('DB_CONNECTION', 'mysql'),
            'port'      => env('DB_PORT', '3306'),
            'charset'   => env('DB_CHARSET', 'utf8mb4'),
        ]);

        try {
            DB::connection('install_test')->getPdo();
        } catch (\Exception $e) {
            return false;
        }

        // Purge test connection
        DB::purge('install_test');

        return true;
    }

    private function saveVariables($request)
    {
        $prefix = strtolower(str_random(3) . '_');

        // Save to file
        DotenvEditor::setKeys([
            [
              'key'       => 'DB_HOST',
              'value'     => $request['hostname'],
            ],
            [
              'key'       => 'DB_DATABASE',
              'value'     => $request['database'],
            ],
            [
              'key'       => 'DB_USERNAME',
              'value'     => $request['username'],
            ],
            [
              'key'       => 'DB_PASSWORD',
              'value'     => $request['password'],
            ],
            [
              'key'       => 'DB_PREFIX',
              'value'     => $prefix,
            ],
        ])->save();

        $con = env('DB_CONNECTION', 'mysql');

        // Change current connection
        $db = Config::get('database.connections.' . $con);

        $db['host'] = $request['hostname'];
        $db['database'] = $request['database'];
        $db['username'] = $request['username'];
        $db['password'] = $request['password'];
        $db['prefix'] = $prefix;

        Config::set('database.connections.' . $con, $db);

        DB::purge($con);
        DB::reconnect($con);
    }
}
