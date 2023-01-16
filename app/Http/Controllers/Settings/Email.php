<?php

namespace App\Http\Controllers\Settings;

use App\Abstracts\Http\SettingController;
use App\Http\Requests\Setting\Setting as Request;
use App\Models\Common\Company;
use App\Utilities\Installer;

class Email extends SettingController
{
    public function edit()
    {
        $email_protocols = [
            'mail' => trans('settings.email.php'),
            'smtp' => trans('settings.email.smtp.name'),
            'sendmail' => trans('settings.email.sendmail'),
            'log' => trans('settings.email.log'),
        ];

        return view('settings.email.edit', compact('email_protocols'));
    }
}
