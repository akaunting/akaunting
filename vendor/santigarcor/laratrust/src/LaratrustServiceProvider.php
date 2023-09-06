<?php

namespace Laratrust;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Relations\Relation;

class LaratrustServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->useMorphMapForRelationships();
        $this->registerMiddlewares();
        $this->registerBladeDirectives();
        $this->registerRoutes();
        $this->registerResources();
        $this->registerPermissionsToGate();
        $this->defineAssetPublishing();
    }

    /**
     * If the user wants to use the morphMap it uses the morphMap.
     *
     * @return void
     */
    protected function useMorphMapForRelationships()
    {
        if ($this->app['config']->get('laratrust.use_morph_map')) {
            Relation::morphMap($this->app['config']->get('laratrust.user_models'));
        }
    }

    /**
     * Register the middlewares automatically.
     *
     * @return void
     */
    protected function registerMiddlewares()
    {
        if (!$this->app['config']->get('laratrust.middleware.register')) {
            return;
        }

        $router = $this->app['router'];

        if (method_exists($router, 'middleware')) {
            $registerMethod = 'middleware';
        } elseif (method_exists($router, 'aliasMiddleware')) {
            $registerMethod = 'aliasMiddleware';
        } else {
            return;
        }

        $middlewares = [
            'role' => \Laratrust\Middleware\LaratrustRole::class,
            'permission' => \Laratrust\Middleware\LaratrustPermission::class,
            'ability' => \Laratrust\Middleware\LaratrustAbility::class,
        ];

        foreach ($middlewares as $key => $class) {
            $router->$registerMethod($key, $class);
        }
    }

    /**
     * Register the blade directives.
     *
     * @return void
     */
    private function registerBladeDirectives()
    {
        if (!class_exists('\Blade')) {
            return;
        }

        (new LaratrustRegistersBladeDirectives)->handle($this->app->version());
    }

    /**
     * Register the routes used by the Laratrust admin panel.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        if (!$this->app['config']->get('laratrust.panel.register')) {
            return;
        }

        Route::group([
            'prefix' => config('laratrust.panel.path'),
            'namespace' => 'Laratrust\Http\Controllers',
            'middleware' => config('laratrust.panel.middleware', 'web'),
        ], function () {
            Route::redirect('/', '/'. config('laratrust.panel.path'). '/roles-assignment');
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }

    /**
     * Register all the possible views used by Laratrust.
     *
     * @return void
     */
    protected function registerResources()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laratrust');
    }

    /**
     * Register permissions to Laravel Gate
     *
     * @return void
     */
    protected function registerPermissionsToGate()
    {
        if (!$this->app['config']->get('laratrust.permissions_as_gates')) {
            return;
        }

        app(Gate::class)->before(function (Authorizable $user, string $ability) {
            if (method_exists($user, 'hasPermission')) {
                return $user->hasPermission($ability) ?: null;
            }
        });
    }

    /**
     * Register the assets that are publishable for the admin panel to work.
     *
     * @return void
     */
    protected function defineAssetPublishing()
    {
        if (!$this->app['config']->get('laratrust.panel.register')) {
            return;
        }

        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/laratrust'),
        ], 'laratrust-assets');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->configure();
        $this->offerPublishing();
        $this->registerLaratrust();
        $this->registerCommands();
    }

    /**
     * Setup the configuration for Laratrust.
     *
     * @return void
     */
    protected function configure()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laratrust.php', 'laratrust');
    }

    /**
     * Setup the resource publishing group for Laratrust.
     *
     * @return void
     */
    protected function offerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/laratrust.php' => config_path('laratrust.php'),
            ], 'laratrust');

            $this->publishes([
                __DIR__. '/../config/laratrust_seeder.php' => config_path('laratrust_seeder.php'),
            ], 'laratrust-seeder');
            
            $this->publishes([
                __DIR__. '/../resources/views/panel' => resource_path('views/vendor/laratrust/panel'),
            ], 'laratrust-views');
        }
    }

    /**
     * Register the application bindings.
     *
     * @return void
     */
    protected function registerLaratrust()
    {
        $this->app->bind('laratrust', function ($app) {
            return new Laratrust($app);
        });

        $this->app->alias('laratrust', 'Laratrust\Laratrust');
    }

    /**
     * Register the Laratrusts commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\AddLaratrustUserTraitUseCommand::class,
                Console\MakePermissionCommand::class,
                Console\MakeRoleCommand::class,
                Console\MakeSeederCommand::class,
                Console\MakeTeamCommand::class,
                Console\MigrationCommand::class,
                Console\SetupCommand::class,
                Console\SetupTeamsCommand::class,
                Console\UpgradeCommand::class,
            ]);
        }
    }
}
