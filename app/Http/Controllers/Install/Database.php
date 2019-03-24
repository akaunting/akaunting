<?php

namespace App\Http\Controllers\Install;

use Artisan;
use App\Http\Requests\Install\Database as Request;
use App\Utilities\Installer;
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
     * @param  Request $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $host = $request['hostname'];
        $database = $request['database'];
        $username = $request['username'];
        $password = $request['password'];
        $driver = $request['driver'];
        $prefix = $request['prefix'];
        $port = $request['port'];

        // Check database connection
        if (!Installer::createDbTables($host, $port, $database, $username, $password, $driver, $prefix)) {
            $message = trans('install.error.connection');

            flash($message)->error()->important();

            return redirect('install/database')->withInput();
        }

        return redirect('install/settings');
    }
}
