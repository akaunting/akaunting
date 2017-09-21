<?php

namespace App\Http\Controllers\Companies;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\Company as Request;
use App\Models\Company\Company;
use App\Models\Setting\Currency;
use App\Traits\Uploads;
use Auth;
use Redirect;
use Setting;

class Companies extends Controller
{
    use Uploads;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $companies = Auth::user()->companies()->collect();

        foreach ($companies as $company) {
            $company->setSettings();
        }

        return view('companies.companies.index', compact('companies'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $currencies = Currency::enabled()->pluck('name', 'code');

        return view('companies.companies.create', compact('currencies'));
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
        Setting::forgetAll();

        // Create company
        $company = Company::create(['domain' => $request->get('domain')]);

        // Create settings
        Setting::set('general.company_name', $request->get('company_name'));
        Setting::set('general.company_email', $request->get('company_email'));
        Setting::set('general.company_address', $request->get('company_address'));

        $logo_path = $this->getUploadedFilePath($request->file('company_logo'), 'settings', $company->id);
        if ($logo_path) {
            Setting::set('general.company_logo', $logo_path);
        }

        Setting::set('general.default_currency', $request->get('default_currency'));
        Setting::set('general.default_locale', session('locale'));

        Setting::setExtraColumns(['company_id' => $company->id]);
        Setting::save();

        // Redirect
        $message = trans('messages.success.added', ['type' => trans_choice('general.companies', 1)]);

        flash($message)->success();

        return redirect('companies/companies');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Company  $company
     *
     * @return Response
     */
    public function edit(Company $company)
    {
        // Check if user can edit company
        $this->authorizeUserOrRedirect($company);

        $company->setSettings();

        $currencies = Currency::enabled()->pluck('name', 'code');

        return view('companies.companies.edit', compact('company', 'currencies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Company  $company
     * @param  Request  $request
     *
     * @return Response
     */
    public function update(Company $company, Request $request)
    {
        // Check if user can update company
        $this->authorizeUserOrRedirect($company);

        // Update company
        $company->update(['domain' => $request->get('domain')]);

        // Get the company settings
        Setting::forgetAll();
        Setting::setExtraColumns(['company_id' => $company->id]);
        Setting::load(true);
        
        // Update settings
        Setting::set('general.company_name', $request->get('company_name'));
        Setting::set('general.company_email', $request->get('company_email'));
        Setting::set('general.company_address', $request->get('company_address'));

        $logo_path = $this->getUploadedFilePath($request->file('company_logo'), 'settings', $company->id);
        if ($logo_path) {
            Setting::set('general.company_logo', $logo_path);
        }

        Setting::set('general.default_payment_method', 'cash');
        Setting::set('general.default_currency', $request->get('default_currency'));

        Setting::save();

        // Redirect
        $message = trans('messages.success.updated', ['type' => trans_choice('general.companies', 1)]);

        flash($message)->success();

        return redirect('companies/companies');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Company  $company
     *
     * @return Response
     */
    public function destroy(Company $company)
    {
        // Can't delete active company
        if ($company->id == session('company_id')) {
            $message = trans('companies.error.delete_active');

            flash($message)->error();

            return redirect('companies/companies');
        }

        $company->delete();

        $message = trans('messages.success.deleted', ['type' => trans_choice('general.companies', 1)]);

        flash($message)->success();

        return redirect('companies/companies');
    }

    /**
     * Change the active company.
     *
     * @param  Company  $company
     *
     * @return Response
     */
    public function set(Company $company)
    {
        // Check if user can manage company
        if ($this->isUserCompany($company)) {
            session(['company_id' => $company->id]);
        }

        //return redirect('/');
        return redirect()->back();
    }

    /**
     * Check user company assignment
     *
     * @param  Company  $company
     *
     * @return boolean
     */
    public function isUserCompany(Company $company)
    {
        $companies = Auth::user()->companies()->pluck('id')->toArray();

        if (in_array($company->id, $companies)) {
            return true;
        }

        return false;
    }

    /**
     * Check user company permission and redirect if not
     *
     * @param  Company  $company
     *
     * @return boolean
     */
    public function authorizeUserOrRedirect(Company $company)
    {
        if ($this->isUserCompany($company)) {
            return true;
        }

        $message = trans('companies.error.not_user_company');

        flash($message)->error();

        Redirect::away(url('companies/companies'))->send();
    }
}
