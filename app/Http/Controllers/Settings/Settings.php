<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\Setting as Request;
use App\Models\Banking\Account;
use App\Models\Common\Company;
use App\Models\Setting\Currency;
use App\Models\Setting\Setting;
use App\Models\Common\Media;
use App\Models\Setting\Tax;
use App\Traits\DateTime;
use App\Traits\Uploads;
use App\Utilities\Installer;
use App\Utilities\Modules;

class Settings extends Controller
{
    use DateTime, Uploads;

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit()
    {
        /*$setting = Setting::all()->pluck('value', 'key');*/
        $setting = Setting::all()->map(function ($s) {
            $s->key = str_replace('general.', '', $s->key);

            return $s;
        })->pluck('value', 'key');

        $company_logo = $setting->pull('company_logo');

        $setting['company_logo'] = Media::find($company_logo);

        $invoice_logo = $setting->pull('invoice_logo');

        $setting['invoice_logo'] = Media::find($invoice_logo);

        $timezones = $this->getTimezones();

        $accounts = Account::enabled()->orderBy('name')->pluck('name', 'id');

        $currencies = Currency::enabled()->orderBy('name')->pluck('name', 'code');

        $taxes = Tax::enabled()->orderBy('rate')->get()->pluck('title', 'id');

        $payment_methods = Modules::getPaymentMethods();

        $date_formats = [
            'd M Y' => '31 Dec 2017',
            'd F Y' => '31 December 2017',
            'd m Y' => '31 12 2017',
            'm d Y' => '12 31 2017',
            'Y m d' => '2017 12 31',
        ];

        $date_separators = [
            'dash' => trans('settings.localisation.date.dash'),
            'slash' => trans('settings.localisation.date.slash'),
            'dot' => trans('settings.localisation.date.dot'),
            'comma' => trans('settings.localisation.date.comma'),
            'space' => trans('settings.localisation.date.space'),
        ];

        $email_protocols = [
            'mail' => trans('settings.email.php'),
            'smtp' => trans('settings.email.smtp.name'),
            'sendmail' => trans('settings.email.sendmail'),
            'log' => trans('settings.email.log'),
        ];

        $percent_positions = [
            'before' => trans('settings.localisation.percent.before'),
            'after' => trans('settings.localisation.percent.after'),
        ];

        return view('settings.settings.edit', compact(
            'setting',
            'timezones',
            'accounts',
            'currencies',
            'taxes',
            'payment_methods',
            'date_formats',
            'date_separators',
            'email_protocols',
            'percent_positions'
        ));
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
        $fields = $request->all();
        $company_id = $request->get('company_id');

        if (empty($company_id)) {
            $company_id = session('company_id');
        }

        $company = Company::find($company_id);

        $skip_keys = ['company_id', '_method', '_token'];
        $file_keys = ['company_logo', 'invoice_logo'];

        $companies = Company::all()->count();
        
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

            // If only 1 company
            if ($companies == 1) {
                $this->oneCompany($key, $value);
            }

            setting()->set('general.' . $key, $value);
        }

        // Save all settings
        setting()->save();

        $message = trans('messages.success.updated', ['type' => trans_choice('general.settings', 2)]);

        flash($message)->success();

        return redirect('settings/settings');
    }

    protected function oneCompany($key, $value)
    {
        switch ($key) {
            case 'default_locale':
                // Change default locale
                Installer::updateEnv([
                    'APP_LOCALE' => $value
                ]);
                break;
            case 'session_handler':
                // Change session handler
                Installer::updateEnv([
                    'SESSION_DRIVER' => $value
                ]);
                break;
        }
    }
}
