<?php

namespace App\Http\Controllers\Wizard;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Wizard\Company as Request;
use App\Models\Common\Company;
use App\Traits\Uploads;
use Illuminate\Support\Str;

class Companies extends Controller
{
    use Uploads;

    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-common-companies')->only('create', 'store', 'duplicate', 'import');
        $this->middleware('permission:read-common-companies')->only('index', 'show', 'edit', 'export');
        $this->middleware('permission:update-common-companies')->only('update', 'enable', 'disable');
        $this->middleware('permission:delete-common-companies')->only('destroy');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function edit()
    {
        $company = Company::find(company_id());

        return $this->response('wizard.companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function update(Request $request)
    {
        // Company
        $company = Company::find(company_id());

        $fields = $request->all();

        $skip_keys = ['company_id', '_method', '_token', '_prefix'];
        $file_keys = ['company.logo'];
        $uploaded_file_keys = ['company.uploaded_logo'];

        // Clear setting media
        foreach ($file_keys as $file_key) {
            $keys = explode('.', $file_key);

            if (! setting($file_key, false)) {
                continue;
            }

            $file_old_key = 'uploaded_' . $keys[1];
            if (array_key_exists($file_old_key, $fields)) {
                continue;
            }

            setting()->forget($file_key);
        }

        foreach ($fields as $key => $value) {
            // Don't process unwanted keys
            if (in_array($key, $skip_keys)) {
                continue;
            }

            $real_key = match($key) {
                'api_key'           => 'apps.api_key',
                'financial_start'   => 'localisation.financial_start',
                'locale'            => 'default.locale',
                default             => 'company.' . $key,
            };

            // change dropzone middleware already uploaded file
            if (in_array($real_key, $uploaded_file_keys)) {
                continue;
            }

             // Process file uploads
             if (in_array($real_key, $file_keys)) {
                // Upload attachment
                if ($request->file($key)) {
                    $media = $this->getMedia($request->file($key), 'settings');

                    $company->attachMedia($media, Str::snake($real_key));

                    $value = $media->id;
                }

                // Prevent reset
                if (empty($value)) {
                    continue;
                }
            }

            if ($real_key == 'default.locale') {
                if (! in_array($value, config('language.allowed'))) {
                    continue;
                }

                user()->setAttribute('locale', $value)->save();
            }

            setting()->set($real_key, $value);
        }

        // Save all settings
        setting()->save();

        return response()->json([
            'status' => null,
            'success' => true,
            'error' => false,
            'message' => trans('messages.success.updated', ['type' => trans_choice('general.companies', 2)]),
            'data' => null,
        ]);
    }
}
