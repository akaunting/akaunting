<?php

namespace App\Http\Middleware;

use Closure;

class LoadSettings
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $company_id = session('company_id');

        if (empty($company_id)) {
            return $next($request);
        }

        // Set the active company settings
        setting()->setExtraColumns(['company_id' => $company_id]);
        setting()->load(true);

        // Timezone
        config(['app.timezone' => setting('general.timezone', 'UTC')]);

        // Email
        $email_protocol = setting('general.email_protocol', 'mail');
        config(['mail.driver' => $email_protocol]);
        config(['mail.from.name' => setting('general.company_name')]);
        config(['mail.from.address' => setting('general.company_email')]);

        if ($email_protocol == 'sendmail') {
            config(['mail.sendmail' => setting('general.email_sendmail_path')]);
        } elseif ($email_protocol == 'smtp') {
            config(['mail.host' => setting('general.email_smtp_host')]);
            config(['mail.port' => setting('general.email_smtp_port')]);
            config(['mail.username' => setting('general.email_smtp_username')]);
            config(['mail.password' => setting('general.email_smtp_password')]);
            config(['mail.encryption' => setting('general.email_smtp_encryption')]);
        }

        // Session
        config(['session.driver' => setting('general.session_handler', 'file')]);
        config(['session.lifetime' => setting('general.session_lifetime', '30')]);

        // Locale
        if (session('locale') == '') {
            //App::setLocale(setting('general.default_language'));
            //Session::put('locale', setting('general.default_language'));
            config(['app.locale' => setting('general.default_locale')]);
        }

        return $next($request);
    }

}