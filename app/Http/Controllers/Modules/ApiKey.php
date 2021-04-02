<?php

namespace App\Http\Controllers\Modules;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Module\Module as Request;

class ApiKey extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('modules.api_key.create');
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
        // Set Api Key
        setting()->set('apps.api_key', $request['api_key']);

        setting()->save();

        return response()->json([
            'success' => true,
            'error' => false,
            'redirect' => route('apps.home.index'),
            'message' => '',
        ]);
    }
}
