<?php

namespace App\Abstracts\Http;

use App\Abstracts\Http\Controller;
use App\Events\Setting\SettingUpdated;
use App\Events\Setting\SettingUpdating;
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

        event(new SettingUpdating($request));

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
                if (! in_array($value, config('language.allowed'))) {
                    continue;
                }

                user()->setAttribute('locale', $value)->save();
            }

            if ($real_key == 'default.currency') {
                $currencies = Currency::enabled()->pluck('code')->toArray();

                if (! in_array($value, $currencies)) {
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

        event(new SettingUpdated($request));

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

    protected function oneCompany($real_key, $value): void
    {
        $key = match($real_key) {
            'default.locale'            => 'APP_LOCALE',
            'schedule.time'             => 'APP_SCHEDULE_TIME',
            'company.name'              => 'MAIL_FROM_NAME',
            'company.email'             => 'MAIL_FROM_ADDRESS',
            'email.protocol'            => 'MAIL_MAILER',
            'email.smtp_host'           => 'MAIL_HOST',
            'email.smtp_port'           => 'MAIL_PORT',
            'email.smtp_username'       => 'MAIL_USERNAME',
            'email.smtp_password'       => 'MAIL_PASSWORD',
            'email.smtp_encryption'     => 'MAIL_ENCRYPTION',
            default                     => '',
        };

        if (empty($key)) {
            return;
        }

        Installer::updateEnv([$key => '"' . $value . '"']);
    }
}
