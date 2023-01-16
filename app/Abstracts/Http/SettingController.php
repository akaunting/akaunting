<?php

namespace App\Abstracts\Http;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Setting\Setting as Request;
use App\Models\Common\Company;
use App\Models\Setting\Currency;
use App\Traits\DateTime;
use App\Traits\Uploads;
use App\Utilities\Installer;
use Illuminate\Support\Str;

abstract class SettingController extends Controller
{
    use DateTime, Uploads;

    public $redirect_route = '';

    public $skip_keys = ['company_id', '_method', '_token', '_prefix'];

    public $file_keys = ['company.logo', 'invoice.logo'];

    public $uploaded_file_keys = ['company.uploaded_logo', 'invoice.uploaded_logo'];

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
            $company_id = company_id();
        }

        $company = Company::find($company_id);

        $total_companies = Company::count();

        // Clear setting media
        foreach ($this->file_keys as $file_key) {
            $keys = explode('.', $file_key);

            if ($prefix != $keys[0]) {
                continue;
            }

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
            $real_key = $prefix . '.' . $key;

            // Don't process unwanted keys
            if (in_array($key, $this->skip_keys)) {
                continue;
            }

            // change dropzone middleware already uploaded file
            if (in_array($real_key, $this->uploaded_file_keys)) {
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
                if (!in_array($value, config('language.allowed'))) {
                    continue;
                }

                user()->setAttribute('locale', $value)->save();
            }

            if ($real_key == 'default.currency') {
                $currencies = Currency::enabled()->pluck('code')->toArray();

                if (!in_array($value, $currencies)) {
                    continue;
                }

                $currency = Currency::code($value)->first();
                $currency->rate = '1';
                $currency->save();
            }

            // If only 1 company
            if ($total_companies == 1) {
                $this->oneCompany($real_key, $value);
            }

            setting()->set($real_key, $value);
        }

        // Save all settings
        setting()->save();

        $message = trans('messages.success.updated', ['type' => trans_choice('general.settings', 2)]);

        $redirect_url = !empty($this->redirect_route) ? route($this->redirect_route) : url()->previous();

        $response = [
            'status' => null,
            'success' => true,
            'error' => false,
            'message' => $message,
            'data' => null,
            'redirect' => $redirect_url,
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
                Installer::updateEnv(['MAIL_FROM_ADDRESS' => '"' . $value . '"']);
                break;
            case 'default.locale':
                Installer::updateEnv(['APP_LOCALE' => '"' . $value . '"']);
                break;
            case 'schedule.time':
                Installer::updateEnv(['APP_SCHEDULE_TIME' => '"' . $value . '"']);
                break;
            case 'email.protocol':
                Installer::updateEnv(['MAIL_MAILER' => '"' . $value . '"']);
                break;
            case 'email.smtp_host':
                Installer::updateEnv(['MAIL_HOST' => '"' . $value . '"']);
                break;
            case 'email.smtp_port':
                Installer::updateEnv(['MAIL_PORT' => '"' . $value . '"']);
                break;
            case 'email.smtp_username':
                Installer::updateEnv(['MAIL_USERNAME' => '"' . $value . '"']);
                break;
            case 'email.smtp_password':
                Installer::updateEnv(['MAIL_PASSWORD' => '"' . $value . '"']);
                break;
            case 'email.smtp_encryption':
                Installer::updateEnv(['MAIL_ENCRYPTION' => '"' . $value . '"']);
                break;
        }
    }
}
