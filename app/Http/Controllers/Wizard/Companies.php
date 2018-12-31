<?php

namespace App\Http\Controllers\Wizard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Wizard\Company as Request;
use App\Models\Common\Company;
use App\Traits\Uploads;
use Date;

class Companies extends Controller
{
    use Uploads;

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function edit()
    {
        if (setting('general.wizard', false)) {
            return redirect('/');
        }

        $company = Company::find(session('company_id'));

        $company->setSettings();

        return view('wizard.companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Company  $company
     * @param  Request  $request
     *
     * @return Response
     */
    public function update(Request $request)
    {
        // Company
        $company = Company::find(session('company_id'));

        $fields = $request->all();

        $skip_keys = ['company_id', '_method', '_token'];
        $file_keys = ['company_logo', 'invoice_logo'];

        foreach ($fields as $key => $value) {
            // Don't process unwanted keys
            if (in_array($key, $skip_keys)) {
                continue;
            }

            // Process file uploads
            if (in_array($key, $file_keys)) {
                // Upload attachment
                if ($request->file($key)) {
                    $media = $this->getMedia($request->file($key), 'settings');

                    $company->attachMedia($media, $key);

                    $value = $media->id;
                }

                // Prevent reset
                if (empty($value)) {
                    continue;
                }
            }

            // Format financial year
            if ($key == 'financial_start') {
                $value = Date::parse($value)->format('d-m');
            }

            setting()->set('general.' . $key, $value);
        }

        // Save all settings
        setting()->save();

        return redirect('wizard/currencies');
    }
}
