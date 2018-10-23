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
    public function edit()
    {
        if (setting(setting('general.wizard', false))) {
            return redirect('/');
        }

        $company = Company::find(session('company_id'));

        $company->setSettings();

        return view('wizard.companies.edit', compact('company', 'currencies'));
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

        // Redirect
        $message = trans('messages.success.updated', ['type' => trans_choice('general.companies', 1)]);

        flash($message)->success();

        return redirect('common/companies');
    }
}
