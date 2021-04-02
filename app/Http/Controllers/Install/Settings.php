<?php

namespace App\Http\Controllers\Install;

use App\Http\Requests\Install\Setting as Request;
use App\Http\Requests\Install\Setting;
use App\Utilities\Installer;
use Illuminate\Routing\Controller;

class Settings extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('install.settings.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Setting  $request
     *
     * @return Response
     */
    public function store(Setting $request)
    {
        // Create company
        Installer::createCompany($request->get('company_name'), $request->get('company_email'), session('locale'));

        // Create user
        Installer::createUser($request->get('user_email'), $request->get('user_password'), session('locale'));

        // Make the final touches
        Installer::finalTouches();

        // Redirect to dashboard
        $response['redirect'] = route('login');

        return response()->json($response);
    }
}
