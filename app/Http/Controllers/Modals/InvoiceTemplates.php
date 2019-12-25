<?php

namespace App\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Setting\Setting as Request;
use App\Models\Common\Company;
use App\Models\Module\Module;
use App\Traits\DateTime;
use App\Traits\Uploads;
use Illuminate\Support\Str;

class InvoiceTemplates extends Controller
{
    use DateTime, Uploads;

    public $skip_keys = ['company_id', '_method', '_token', '_prefix', '_template'];

    public $file_keys = ['company.logo', 'invoice.logo'];
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-settings-settings')->only(['create', 'store']);
        $this->middleware('permission:read-settings-settings')->only(['index', 'edit']);
        $this->middleware('permission:update-settings-settings')->only(['update', 'enable', 'disable']);
        $this->middleware('permission:delete-settings-settings')->only('destroy');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     *
     * @return Response
     */
    public function update(Request $request)
    {
        $fields = $request->all();
        $prefix = $request->get('_prefix', 'general');
        $company_id = $request->get('company_id');

        if (empty($company_id)) {
            $company_id = session('company_id');
        }

        $company = Company::find($company_id);

        $companies = Company::all()->count();

        foreach ($fields as $key => $value) {
            $real_key = $prefix . '.' . $key;

            // Don't process unwanted keys
            if (in_array($key, $this->skip_keys)) {
                continue;
            }

            // Process file uploads
            if (in_array($real_key, $this->file_keys)) {
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
                user()->setAttribute('locale', $value)->save();
            }

            // If only 1 company
            if ($companies == 1) {
                $this->oneCompany($real_key, $value);
            }

            setting()->set($real_key, $value);
        }

        // Save all settings
        setting()->save();

        $message = trans('messages.success.updated', ['type' => trans_choice('general.settings', 2)]);

        $response = [
            'status' => null,
            'success' => true,
            'error' => false,
            'message' => $message,
            'data' => null,
            'redirect' => route('settings.invoice.edit'),
        ];

        flash($message)->success();

        return response()->json($response);
    }

    protected function oneCompany($real_key, $value)
    {
        switch ($real_key) {
            case 'company.name':
                Installer::updateEnv(['MAIL_FROM_NAME' => '"' . $value . '"']);
                break;
            case 'company.email':
                Installer::updateEnv(['MAIL_FROM_ADDRESS' => $value]);
                break;
            case 'default.locale':
                Installer::updateEnv(['APP_LOCALE' => $value]);
                break;
            case 'schedule.time':
                Installer::updateEnv(['APP_SCHEDULE_TIME' => '"' . $value . '"']);
                break;
        }
    }
}
