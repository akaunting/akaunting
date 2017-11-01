<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\Module\Module as Request;

class Token extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('modules.token.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        // Set Api Token
        setting()->set('general.api_token', $request['api_token']);

        setting()->save();

        return redirect('apps/home');
    }
}
