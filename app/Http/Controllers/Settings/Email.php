<?php

namespace App\Http\Controllers\Settings;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Setting\Setting as Request;
use App\Models\Common\Company;
use App\Models\Common\EmailTemplate;
use App\Utilities\Installer;
use Illuminate\Support\Str;

class Email extends Controller
{
    public $skip_keys = ['company_id', '_method', '_token', '_prefix'];

    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // No need to check for permission in console
        if (app()->runningInConsole()) {
            return;
        }

        // Add CRUD permission check
        $this->middleware('permission:create-settings-settings')->only('create', 'store', 'duplicate', 'import');
        $this->middleware('permission:read-settings-email')->only('index', 'show', 'edit', 'export');
        $this->middleware('permission:update-settings-settings')->only('update', 'enable', 'disable');
        $this->middleware('permission:delete-settings-settings')->only('destroy');
    }

    public function edit()
    {
        $templates = EmailTemplate::all();

        $email_protocols = [
            'mail' => trans('settings.email.php'),
            'smtp' => trans('settings.email.smtp.name'),
            'sendmail' => trans('settings.email.sendmail'),
            'log' => trans('settings.email.log'),
        ];

        return view('settings.email.edit', compact(
            'templates',
            'email_protocols'
        ));
    }

    public function update(Request $request)
    {
        $fields = $request->all();
        $prefix = $request->get('_prefix', 'email');

        $total_companies = Company::count();

        foreach ($fields as $key => $value) {
            $real_key = $prefix . '.' . $key;

            // Don't process unwanted keys
            if (in_array($key, $this->skip_keys)) {
                continue;
            }

            if (Str::startsWith($key, 'template_')) {
                $this->updateEmailTemplate($key, $fields);

                continue;
            }

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

    public function updateEmailTemplate($key, &$fields)
    {
        $alias = str_replace(['template_', '_subject', '_body'], '', $key);
        $subject_key = 'template_' . $alias . '_subject';
        $body_key = 'template_' . $alias . '_body';

        if (empty($fields[$subject_key]) || empty($fields[$body_key])) {
            return;
        }

        $template = EmailTemplate::alias($alias)->first();

        $template->update([
            'subject' => $fields[$subject_key],
            'body' => $fields[$body_key],
        ]);

        unset($fields[$subject_key]);
        unset($fields[$body_key]);
    }

    protected function oneCompany($real_key, $value)
    {
        if (empty($value)) {
            return;
        }

        switch ($real_key) {
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
