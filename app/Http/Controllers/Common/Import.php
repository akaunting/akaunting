<?php

namespace App\Http\Controllers\Common;

use App\Abstracts\Http\Controller;

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

        if (module($group) instanceof \Akaunting\Module\Module) {
            $namespace = $group . '::';
        } else {
            $namespace = '';
        }

        return view('common.import.create', compact('group', 'type', 'path', 'namespace'));
    }
}
