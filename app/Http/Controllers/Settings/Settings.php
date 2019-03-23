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
use Date;

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
        $setting = Setting::all()->map(function ($s) {
            $s->key = str_replace('general.', '', $s->key);

            return $s;
        })->pluck('value', 'key');

        $setting->put('company_logo', Media::find($setting->pull('company_logo')));
        $setting->put('invoice_logo', Media::find($setting->pull('invoice_logo')));

        $timezones = $this->getTimezones();

        $accounts = Account::enabled()->orderBy('name')->pluck('name', 'id');

        $currencies = Currency::enabled()->orderBy('name')->pluck('name', 'code');

        $taxes = Tax::enabled()->orderBy('name')->get()->pluck('title', 'id');

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

        $item_names = [
            'settings.invoice.item' => trans('settings.invoice.item'),
            'settings.invoice.product' => trans('settings.invoice.product'),
            'settings.invoice.service' =>  trans('settings.invoice.service'),
            'custom' => trans('settings.invoice.custom'),
        ];

        $price_names = [
            'settings.invoice.price' => trans('settings.invoice.price'),
            'settings.invoice.rate' => trans('settings.invoice.rate'),
            'custom' => trans('settings.invoice.custom'),
        ];

        $quantity_names = [
            'settings.invoice.quantity' => trans('settings.invoice.quantity'),
            'custom' => trans('settings.invoice.custom'),
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
            'item_names',
            'price_names',
            'quantity_names',
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
            case 'company_name':
                Installer::updateEnv(['MAIL_FROM_NAME' => '"' . $value . '"']);
                break;
            case 'company_email':
                Installer::updateEnv(['MAIL_FROM_ADDRESS' => $value]);
                break;
            case 'default_locale':
                Installer::updateEnv(['APP_LOCALE' => $value]);
                break;
            case 'session_handler':
                Installer::updateEnv(['SESSION_DRIVER' => $value]);
                break;
            case 'schedule_time':
                Installer::updateEnv(['APP_SCHEDULE_TIME' => '"' . $value . '"']);
                break;
        }
    }
}
