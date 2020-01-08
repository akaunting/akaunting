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
        $this->middleware('permission:create-common-companies')->only(['create', 'store', 'duplicate', 'import']);
        $this->middleware('permission:read-common-companies')->only(['index', 'show', 'edit', 'export']);
        $this->middleware('permission:update-common-companies')->only(['update', 'enable', 'disable']);
        $this->middleware('permission:delete-common-companies')->only('destroy');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function edit()
    {
        $company = Company::find(session('company_id'));

        return view('wizard.companies.edit', compact('company'));
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
        $company = Company::find(session('company_id'));

        $fields = $request->all();

        $skip_keys = ['company_id', '_method', '_token'];
        $file_keys = ['company.logo'];

        foreach ($fields as $key => $value) {
            // Don't process unwanted keys
            if (in_array($key, $skip_keys)) {
                continue;
            }

            switch ($key) {
                case 'api_key':
                    $real_key = 'apps.' . $key;
                    break;
                case 'financial_start':
                    $real_key = 'localisation.' . $key;
                    break;
                default:
                    $real_key = 'company.' . $key;
            }

            // Process file uploads
            if (in_array($real_key, $file_keys)) {
                // Upload attachment
                if ($request->file($key)) {
                    $media = $this->getMedia($request->file($key), 'settings');

                    $company->attachMedia($media, Str::snake($key));

                    $value = $media->id;
                }

                // Prevent reset
                if (empty($value)) {
                    continue;
                }
            }

            setting()->set($real_key, $value);
        }

        // Save all settings
        setting()->save();

        $message = trans('messages.success.updated', ['type' => trans_choice('general.companies', 2)]);

        $response = [
            'status' => null,
            'success' => true,
            'error' => false,
            'message' => $message,
            'data' => null,
            'redirect' => route('wizard.currencies.index'),
        ];

        flash($message)->success();

        return response()->json($response);
    }
}
