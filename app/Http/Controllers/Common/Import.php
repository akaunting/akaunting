<?php

namespace App\Http\Controllers\Common;

use App\Abstracts\Http\Controller;
use Illuminate\Http\Request;

class Import extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param  $group
     * @param  $type
     * @return Response
     */
    public function create($group, $type, $route = null)
    {
        $path = company_id() . '/' . $group . '/' . $type;

        if (module($group) instanceof \Akaunting\Module\Module) {
            $namespace = $group . '::';
            $type = str_replace('-', '_', $type);
        } else {
            $namespace = '';
        }

        return view('common.import.create', compact('group', 'type', 'path', 'route', 'namespace'));
    }
}
