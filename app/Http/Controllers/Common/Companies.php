<?php

namespace App\Http\Controllers\Common;

use App\Events\CompanySwitched;
use App\Http\Controllers\Controller;
use App\Http\Requests\Common\Company as Request;
use App\Models\Common\Company;
use App\Models\Setting\Currency;
use App\Traits\Uploads;
use App\Utilities\Overrider;

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
        $companies = Company::collect();

        foreach ($companies as $company) {
            $company->setSettings();
        }

        return view('common.companies.index', compact('companies'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function show()
    {
        return redirect('common/companies');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $currencies = Currency::enabled()->pluck('name', 'code');

        return view('common.companies.create', compact('currencies'));
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
        $company_id = session('company_id');

        // Create company
        $company = Company::create($request->input());
        
        // Create settings
        setting()->set('general.company_name', $request->get('company_name'));
        setting()->set('general.company_email', $request->get('company_email'));
        setting()->set('general.company_address', $request->get('company_address'));

        if ($request->file('company_logo')) {
            $company_logo = $this->getMedia($request->file('company_logo'), 'settings', $company->id);

            if ($company_logo) {
                $company->attachMedia($company_logo, 'company_logo');

                setting()->set('general.company_logo', $company_logo->id);
            }
        }

        setting()->set('general.default_currency', $request->get('default_currency'));
        setting()->set('general.default_locale', session('locale'));
        setting()->save();

        setting()->forgetAll();

        session(['company_id' => $company_id]);

        Overrider::load('settings');

        // Redirect
        $message = trans('messages.success.added', ['type' => trans_choice('general.companies', 1)]);

        flash($message)->success();

        return redirect('common/companies');
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
        if (!$this->isUserCompany($company)) {
            $message = trans('companies.error.not_user_company');

            flash($message)->error();

            return redirect('common/companies');
        }

        $company->setSettings();

        $currencies = Currency::enabled()->pluck('name', 'code');

        return view('common.companies.edit', compact('company', 'currencies'));
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
        $company_id = session('company_id');

        // Check if user can update company
        if (!$this->isUserCompany($company)) {
            $message = trans('companies.error.not_user_company');

            flash($message)->error();

            return redirect('common/companies');
        }

        // Update company
        $company->update($request->input());

        // Get the company settings
        setting()->forgetAll();
        setting()->setExtraColumns(['company_id' => $company->id]);
        setting()->load(true);
        
        // Update settings
        setting()->set('general.company_name', $request->get('company_name'));
        setting()->set('general.company_email', $request->get('company_email'));
        setting()->set('general.company_address', $request->get('company_address'));

        if ($request->file('company_logo')) {
            $company_logo = $this->getMedia($request->file('company_logo'), 'settings', $company->id);

            if ($company_logo) {
                $company->attachMedia($company_logo, 'company_logo');

                setting()->set('general.company_logo', $company_logo->id);
            }
        }

        setting()->set('general.default_payment_method', 'offlinepayment.cash.1');
        setting()->set('general.default_currency', $request->get('default_currency'));

        setting()->save();

        setting()->forgetAll();

        session(['company_id' => $company_id]);

        Overrider::load('settings');

        // Redirect
        $message = trans('messages.success.updated', ['type' => trans_choice('general.companies', 1)]);

        flash($message)->success();

        return redirect('common/companies');
    }

    /**
     * Enable the specified resource.
     *
     * @param  Company  $company
     *
     * @return Response
     */
    public function enable(Company $company)
    {
        $company->enabled = 1;
        $company->save();

        $message = trans('messages.success.enabled', ['type' => trans_choice('general.companies', 1)]);

        flash($message)->success();

        return redirect()->route('companies.index');

    }

    /**
     * Disable the specified resource.
     *
     * @param  Company  $company
     *
     * @return Response
     */
    public function disable(Company $company)
    {
        // Check if user can update company
        if (!$this->isUserCompany($company)) {
            $message = trans('companies.error.not_user_company');

            Overrider::load('settings');

            flash($message)->error();

            return redirect()->route('companies.index');
        }

        $company->enabled = 0;
        $company->save();

        $message = trans('messages.success.disabled', ['type' => trans_choice('general.companies', 1)]);

        flash($message)->success();

        return redirect()->route('companies.index');
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

            return redirect('common/companies');
        }

        $company->delete();

        $message = trans('messages.success.deleted', ['type' => trans_choice('general.companies', 1)]);

        flash($message)->success();

        return redirect('common/companies');
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

            Overrider::load('settings');

            event(new CompanySwitched($company));
        }

        // Check wizard
        if (!setting('general.wizard', false)) {
            return redirect('wizard');
        }

        return redirect('/');
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
        $companies = auth()->user()->companies()->pluck('id')->toArray();

        if (in_array($company->id, $companies)) {
            return true;
        }

        return false;
    }
}
