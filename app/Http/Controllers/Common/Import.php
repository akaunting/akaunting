<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Module;

class Import extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param  $group
     * @param  $type
     * @return Response
     */
    public function create($group, $type)
    {
        $path = $group . '/' . $type;

        if (Module::findByAlias($group) instanceof \Nwidart\Modules\Module) {
            $namespace = $group . '::';
        } else {
            $namespace = '';
        }

        return view('common.import.create', compact('group', 'type', 'path', 'namespace'));
    }
}
