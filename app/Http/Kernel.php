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
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \Fideloper\Proxy\TrustProxies::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\RedirectIfNotInstalled::class,
            \App\Http\Middleware\AddXHeader::class,
            'company.settings',
            'company.currencies',
            \App\Http\Middleware\RedirectIfWizardCompleted::class,
        ],

        'wizard' => [
            'web',
            'language',
            'auth',
            'permission:read-admin-panel',
        ],

        'admin' => [
            'web',
            'language',
            'auth',
            'adminmenu',
            'permission:read-admin-panel',
        ],

        'customer' => [
            'web',
            'language',
            'auth',
            'customermenu',
            'permission:read-customer-panel',
        ],

        'api' => [
            'api.auth',
            'throttle:60,1',
            'bindings',
            'api.company',
            'permission:read-api',
            'company.settings',
            'company.currencies',
        ],

        'signed' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            'signed-url',
            'signed-url.company',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\AddXHeader::class,
            'company.settings',
            'company.currencies',
        ]
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'adminmenu' => \App\Http\Middleware\AdminMenu::class,
        'customermenu' => \App\Http\Middleware\CustomerMenu::class,
        'role' => \Laratrust\Middleware\LaratrustRole::class,
        'permission' => \Laratrust\Middleware\LaratrustPermission::class,
        'ability' => \Laratrust\Middleware\LaratrustAbility::class,
        'api.company' => \App\Http\Middleware\ApiCompany::class,
        'install' => \App\Http\Middleware\CanInstall::class,
        'company.settings' => \App\Http\Middleware\LoadSettings::class,
        'company.currencies' => \App\Http\Middleware\LoadCurrencies::class,
        'dateformat' => \App\Http\Middleware\DateFormat::class,
        'money' => \App\Http\Middleware\Money::class,
        'signed-url.company' => \App\Http\Middleware\SignedUrlCompany::class,
    ];
}
