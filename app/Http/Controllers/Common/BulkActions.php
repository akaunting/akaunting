<?php

namespace App\Http\Controllers\Common;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Common\BulkAction as Request;

class

BulkActions extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @param  $group
     * @param  $type
     * @param  Request $request
     * @return Response
     */
    public function action($group, $type, Request $request)
    {
        // Check is module
        $module = module($group);

        if ($module instanceof \Akaunting\Module\Module) {
            $bulk_actions = app('Modules\\' . $module->getStudlyName() . '\BulkActions\\' . ucfirst($type));
        } else {
            $bulk_actions = app('App\BulkActions\\' .  ucfirst($group) . '\\' . ucfirst($type));
        }

        $bulk_actions->{$request->get('handle')}($request);

        return view('common.import.create', compact('group', 'type', 'path', 'namespace'));
    }
}
