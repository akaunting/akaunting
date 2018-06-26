<?php

namespace App\Http\Controllers\Install;

use App\Http\Controllers\Controller;
use App\Events\UpdateFinished;
use App\Utilities\Updater;
use App\Utilities\Versions;
use Artisan;
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
        set_time_limit(600); // 10 minutes

        if (Updater::update($alias, $version)) {
            return redirect('install/updates/post/' . $alias . '/' . version('short') . '/' . $version);
        }

        flash(trans('updates.error'))->error()->important();

        return redirect()->back();
    }

    /**
     * Final actions post update.
     *
     * @param  $alias
     * @param  $old
     * @param  $new
     * @return Response
     */
    public function post($alias, $old, $new)
    {
        // Check if the file mirror was successful
        if (($alias == 'core') && (version('short') != $new)) {
            flash(trans('updates.error'))->error()->important();

            return redirect('install/updates');
        }

        // Clear cache after update
        Artisan::call('cache:clear');

        event(new UpdateFinished($alias, $old, $new));

        flash(trans('updates.success'))->success();

        return redirect('install/updates');
    }
}
