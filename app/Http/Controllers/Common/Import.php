<?php

namespace App\Http\Controllers\Common;

use App\Abstracts\Http\Controller;
use App\Traits\Import as ImportTrait;

class Import extends Controller
{
    use ImportTrait;

    /**
     * Show the form for creating a new resource.
     *
     * @param  $group
     * @param  $type
     * @param  $route
     *
     * @return Response
     */
    public function create($group, $type, $route = null)
    {
        list($view, $data) = $this->getImportView($group, $type, $route);

        return view($view, $data);
    }
}
