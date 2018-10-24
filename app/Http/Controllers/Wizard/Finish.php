<?php

namespace App\Http\Controllers\Wizard;

use Illuminate\Routing\Controller;
use App\Models\Common\Company;

class Finish extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function index()
    {
        if (setting(setting('general.wizard', false))) {
            //return redirect('/');
        }

        $company = Company::find(session('company_id'));

        $company->setSettings();

        return view('wizard.finish.index', compact('company', 'currencies'));
    }
}
