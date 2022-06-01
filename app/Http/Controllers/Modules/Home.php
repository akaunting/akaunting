<?php

namespace App\Http\Controllers\Modules;

use App\Abstracts\Http\Controller;

class Home extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->response('modules.home.index');
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function show()
    {
        return redirect()->route('apps.home.index');
    }
}
