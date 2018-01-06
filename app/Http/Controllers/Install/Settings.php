<?php

namespace App\Http\Controllers\Install;

use App\Http\Requests\Install\Setting as Request;
use App\Utilities\AppConfigurer;
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
     * @param  Request  $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        // Create company
	    $companyName = $request['company_name'];
	    $companyEmail= $request['company_email'];
	    $locale= session('locale');
	    AppConfigurer::createCompany($companyName, $companyEmail, $locale);

        // Create user
	    $adminEmail = $request['user_email'];
	    $adminPassword = $request['user_password'];
	    $locale= session('locale');
	    AppConfigurer::createUser($adminEmail, $adminPassword, $locale);

        // Make the final touches
	    AppConfigurer::finalTouches();

        // Redirect to dashboard
        return redirect('auth/login');
    }
}
