<?php

namespace App\Http\Controllers\Settings;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Setting\Setting as Request;
use App\Models\Common\Company;
use App\Models\Module\Module;
use App\Models\Setting\Currency;
use App\Traits\DateTime;
use App\Traits\Uploads;
use App\Utilities\Installer;
use Illuminate\Support\Str;

class Settings extends Controller
{
    use DateTime, Uploads;

    public $skip_keys = ['company_id', '_method', '_token', '_prefix'];

    public $file_keys = ['company.logo', 'invoice.logo'];

    public $uploaded_file_keys = ['company.uploaded_logo', 'invoice.uploaded_logo'];

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function index()
    {
        $modules = new \stdClass();
        $modules->settings = [];

        // Get enabled modules
        $enabled_modules = Module::enabled()->get();

        foreach ($enabled_modules as $module) {
            $m = module($module->alias);

            // Check if the module exists and has settings
            if (!$m || empty($m->get('settings'))) {
                continue;
            }

            $modules->settings[$m->getAlias()] = [
                'name' => $m->getName(),
                'description' => $m->getDescription(),
                'url' => route('settings.module.edit', ['alias' => $m->getAlias()]),
                'icon' => $m->get('icon', 'fa fa-cog'),
            ];
        }

        event(new \App\Events\Module\SettingShowing($modules));

        $settings = [];

        foreach ($modules->settings as $alias => $setting) {
            $permission = !empty($setting['permission']) ? $setting['permission'] : 'read-' . $alias . '-settings';

            if (!user()->can($permission)) {
                continue;
            }

            $settings[$alias] = $setting;
        }

        return $this->response('settings.settings.index', ['modules' => $settings]);
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
            $company_id = company_id();
        }

        $company = Company::find($company_id);

        $total_companies = Company::count();

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

        $response = [
            'status' => null,
            'success' => true,
            'error' => false,
            'message' => $message,
            'data' => null,
            'redirect' => route('settings.index'),
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
        }
    }
}