<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route as Facade;

class Route extends Provider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        Facade::macro('module', function ($alias, $routes, $attrs) {
            $attributes = [
                'middleware' => $attrs['middleware'],
            ];

            if (isset($attrs['namespace'])) {
                // null means don't add
                if (!is_null($attrs['namespace'])) {
                    $attributes['namespace'] = $attrs['namespace'];
                }
            } else {
                $attributes['namespace'] = 'Modules\\' . module($alias)->getStudlyName() . '\Http\Controllers';
            }

            if (isset($attrs['prefix'])) {
                // null means don't add
                if (!is_null($attrs['prefix'])) {
                    $attributes['prefix'] = '{company_id}/' . $attrs['prefix'];
                }
            } else {
                $attributes['prefix'] = '{company_id}/' . $alias;
            }

            if (isset($attrs['as'])) {
                // null means don't add
                if (!is_null($attrs['as'])) {
                    $attributes['as'] = $attrs['as'];
                }
            } else {
                $attributes['as'] = $alias . '.';
            }

            return Facade::group($attributes, $routes);
        });

        Facade::macro('admin', function ($alias, $routes, $attributes = []) {
            return Facade::module($alias, $routes, array_merge([
                'middleware'    => 'admin',
            ], $attributes));
        });

        Facade::macro('portal', function ($alias, $routes, $attributes = []) {
            return Facade::module($alias, $routes, array_merge([
                'middleware'    => 'portal',
                'prefix'        => 'portal/' . $alias,
                'as'            => 'portal.' . $alias . '.',
            ], $attributes));
        });

        Facade::macro('signed', function ($alias, $routes, $attributes = []) {
            return Facade::module($alias, $routes, array_merge([
                'middleware'    => 'signed',
                'prefix'        => 'signed/' . $alias,
                'as'            => 'signed.' . $alias . '.',
            ], $attributes));
        });

        Facade::macro('api', function ($alias, $routes, $attrs = []) {
            $attributes = array_merge([
                'namespace'     => 'Modules\\' . module($alias)->getStudlyName() . '\Http\Controllers\Api',
                'prefix'        => $alias,
                'as'            => 'api.' . $alias,
            ], $attrs);

            $api = app('Dingo\Api\Routing\Router');

            return $api->version(config('api.version'), ['middleware' => ['api']], function($api) use ($attributes, $routes) {
                $api->group($attributes, $routes);
            });
        });
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapInstallRoutes();

        $this->mapApiRoutes();

        $this->mapCommonRoutes();

        $this->mapGuestRoutes();

        $this->mapWizardRoutes();

        $this->mapAdminRoutes();

        $this->mapPortalRoutes();

        $this->mapSignedRoutes();
    }

    /**
     * Define the "install" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapInstallRoutes()
    {
        Facade::prefix('install')
            ->middleware('install')
            ->namespace($this->namespace)
            ->group(base_path('routes/install.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        $this->configureRateLimiting();

        Facade::prefix('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }

    /**
     * Define the "common" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapCommonRoutes()
    {
        Facade::prefix('{company_id}')
            ->middleware('common')
            ->namespace($this->namespace)
            ->group(base_path('routes/common.php'));
    }

    /**
     * Define the "guest" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapGuestRoutes()
    {
        Facade::middleware('guest')
            ->namespace($this->namespace)
            ->group(base_path('routes/guest.php'));
    }

    /**
     * Define the "wizard" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWizardRoutes()
    {
        Facade::prefix('{company_id}/wizard')
            ->middleware('wizard')
            ->namespace($this->namespace)
            ->group(base_path('routes/wizard.php'));
    }

    /**
     * Define the "admin" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapAdminRoutes()
    {
        Facade::prefix('{company_id}')
            ->middleware('admin')
            ->namespace($this->namespace)
            ->group(base_path('routes/admin.php'));
    }

    /**
     * Define the "portal" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapPortalRoutes()
    {
        Facade::prefix('{company_id}/portal')
            ->middleware('portal')
            ->namespace($this->namespace)
            ->group(base_path('routes/portal.php'));
    }

    /**
     * Define the "signed" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapSignedRoutes()
    {
        Facade::prefix('{company_id}/signed')
            ->middleware('signed')
            ->namespace($this->namespace)
            ->group(base_path('routes/signed.php'));
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60);
        });
    }
}
