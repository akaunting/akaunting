<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \App\Http\Middleware\TrustProxies::class,
        \MisterPhilip\MaintenanceMode\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            'cookies.encrypt',
            'cookies.response',
            'session.start',
            // 'session.auth',
            'session.errors',
            'csrf',
            'bindings',
            'install.redirect',
            'header.x',
            'company.settings',
            'company.currencies',
            'language',
            'firewall.all',
        ],

        'install' => [
            'web',
            'install.can',
        ],

        'api' => [
            'api.auth',
            'auth.disabled',
            'throttle:60,1',
            'bindings',
            'api.company',
            'permission:read-api',
            'company.settings',
            'company.currencies',
            'language',
            'firewall.all',
        ],

        'common' => [
            'web',
            'wizard.redirect',
        ],

        'guest' => [
            'web',
            'auth.redirect',
        ],

        'admin' => [
            'web',
            'auth',
            'auth.disabled',
            'wizard.redirect',
            'menu.admin',
            'permission:read-admin-panel',
        ],

        'wizard' => [
            'web',
            'auth',
            'auth.disabled',
            'permission:read-admin-panel',
        ],

        'portal' => [
            'web',
            'auth',
            'auth.disabled',
            'menu.portal',
            'permission:read-client-portal',
        ],

        'signed' => [
            'cookies.encrypt',
            'cookies.response',
            'session.start',
            'session.errors',
            'csrf',
            'signature',
            'company.signed',
            'bindings',
            'header.x',
            'company.settings',
            'company.currencies',
            'language',
            'firewall.all',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        // Laravel
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'cookies.encrypt' => \App\Http\Middleware\EncryptCookies::class,
        'cookies.response' => \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        'csrf' => \App\Http\Middleware\VerifyCsrfToken::class,
        'session.auth' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'session.errors' => \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        'session.start' => \Illuminate\Session\Middleware\StartSession::class,
        //'signature' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'signature' => \App\Http\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        // Akaunting
        'api.company' => \App\Http\Middleware\ApiCompany::class,
        'auth.disabled' => \App\Http\Middleware\LogoutIfUserDisabled::class,
        'auth.redirect' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'company.currencies' => \App\Http\Middleware\LoadCurrencies::class,
        'company.settings' => \App\Http\Middleware\LoadSettings::class,
        'company.signed' => \App\Http\Middleware\SignedCompany::class,
        'header.x' => \App\Http\Middleware\AddXHeader::class,
        'menu.admin' => \App\Http\Middleware\AdminMenu::class,
        'menu.portal' => \App\Http\Middleware\PortalMenu::class,
        'date.format' => \App\Http\Middleware\DateFormat::class,
        'install.can' => \App\Http\Middleware\CanInstall::class,
        'install.redirect' => \App\Http\Middleware\RedirectIfNotInstalled::class,
        'money' => \App\Http\Middleware\Money::class,
        'wizard.redirect' => \App\Http\Middleware\RedirectIfWizardNotCompleted::class,
        'api.key' => \App\Http\Middleware\CanApiKey::class,

        // Vendor
        'ability' => \Laratrust\Middleware\LaratrustAbility::class,
        'role' => \Laratrust\Middleware\LaratrustRole::class,
        'permission' => \Laratrust\Middleware\LaratrustPermission::class,
    ];

    /**
     * The priority-sorted list of middleware.
     *
     * This forces non-global middleware to always be in the given order.
     *
     * @var array
     */
    protected $middlewarePriority = [
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\Authenticate::class,
        \Illuminate\Routing\Middleware\ThrottleRequests::class,
        \Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Illuminate\Auth\Middleware\Authorize::class,
    ];
}
