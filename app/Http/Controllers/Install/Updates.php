<?php

namespace App\Http\Controllers\Install;

use App\Http\Controllers\Controller;
use App\Models\Module\Module as Model;
use App\Utilities\Updater;
use App\Utilities\Versions;
use Illuminate\Routing\Route;
use Module;

class Updates extends Controller
{

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
     * @return Response
     */
    public function update($alias, $version)
    {
        set_time_limit(600); // 10 minutes

        $status = Updater::update($alias, $version);

        // Clear cache in order to check for updates again
        Updater::clear();

        return redirect()->back();
    }
}
