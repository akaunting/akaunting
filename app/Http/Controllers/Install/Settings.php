<?php

namespace App\Http\Controllers\Install;

use Artisan;
use App\Http\Requests\Install\Setting as Request;
use App\Models\Auth\User;
use App\Models\Company\Company;
use DotenvEditor;
use File;
use Illuminate\Routing\Controller;
use Setting;

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
        $this->createCompany($request);

        // Create user
        $this->createUser($request);

        // Make the final touches
        $this->finalTouches();

        // Redirect to dashboard
        return redirect('auth/login');
    }

    private function createCompany($request)
    {
        // Create company
        $company = Company::create([
            'domain' => '',
        ]);

        // Set settings
        Setting::set([
            'general.company_name'          => $request['company_name'],
            'general.company_email'         => $request['company_email'],
            'general.default_currency'      => 'USD',
            'general.default_locale'        => session('locale'),
        ]);
        Setting::setExtraColumns(['company_id' => $company->id]);
        Setting::save();
    }

    private function createUser($request)
    {
        // Create the user
        $user = User::create([
            'name' => $request[''],
            'email' => $request['user_email'],
            'password' => $request['user_password'],
            'locale' => session('locale'),
        ]);

        // Attach admin role
        $user->roles()->attach('1');

        // Attach company
        $user->companies()->attach('1');
    }

    private function finalTouches()
    {
        // Caching the config and route
        //Artisan::call('config:cache');
        //Artisan::call('route:cache');

        // Update .env file
        DotenvEditor::setKeys([
            [
                'key'       => 'APP_LOCALE',
                'value'     => session('locale'),
            ],
            [
                'key'       => 'APP_INSTALLED',
                'value'     => 'true',
            ],
            [
                'key'       => 'APP_DEBUG',
                'value'     => 'false',
            ],
        ])->save();

        // Rename the robots.txt file
        try {
            File::move(base_path('robots.txt.dist'), base_path('robots.txt'));
        } catch (\Exception $e) {
            // nothing to do
        }
    }
}
