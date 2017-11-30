<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;

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

        return view('common.import.create', compact('group', 'type', 'path'));
    }
}
