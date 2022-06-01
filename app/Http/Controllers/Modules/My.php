<?php

namespace App\Http\Controllers\Modules;

use App\Abstracts\Http\Controller;

class My extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->response('modules.my.index');
    }
}
