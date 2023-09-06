<?php
declare(strict_types=1);

namespace Plank\Mediable;

use CreateMediableTables;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;
use Plank\Mediable\Commands\ImportMediaCommand;
use Plank\Mediable\Commands\PruneMediaCommand;
use Plank\Mediable\Commands\SyncMediaCommand;
use Plank\Mediable\SourceAdapters\SourceAdapterFactory;
use Plank\Mediable\UrlGenerators\UrlGeneratorFactory;

/**
 * Mediable Service Provider.
 *
 * Registers Laravel-Mediable package functionality
 */
class MediableServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot(): void
    {
        $root = dirname(__DIR__);
        $this->publishes(
            [
                $root . '/config/mediable.php' => config_path('mediable.php'),
            ],
            'config'
        );
        
        $this->publishes([
            __DIR__.'/../migrations' => $this->app->databasePath('migrations'),
        ], 'mediable-migrations');

        $this->loadMigrationsFrom($root . '/migrations');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/config/mediable.php',
            'mediable'
        );

        $this->registerSourceAdapterFactory();
        $this->registerUploader();
        $this->registerMover();
        $this->registerUrlGeneratorFactory();
        $this->registerConsoleCommands();
        $this->registerImageManipulator();
    }

    /**
     * Bind an instance of the Source Adapter Factory to the container.
     *
     * Attaches the default adapter types
     * @return void
     */
    public function registerSourceAdapterFactory(): void
    {
        $this->app->singleton('mediable.source.factory', function (Container $app) {
            $factory = new SourceAdapterFactory;

            $classAdapters = $app['config']->get('mediable.source_adapters.class', []);
            foreach ($classAdapters as $source => $adapter) {
                $factory->setAdapterForClass($adapter, $source);
            }

            $patternAdapters = $app['config']->get('mediable.source_adapters.pattern', []);
            foreach ($patternAdapters as $source => $adapter) {
                $factory->setAdapterForPattern($adapter, $source);
            }

            return $factory;
        });
        $this->app->alias('mediable.source.factory', SourceAdapterFactory::class);
    }

    /**
     * Bind the Media Uploader to the container.
     * @return void
     */
    public function registerUploader(): void
    {
        $this->app->bind('mediable.uploader', function (Container $app) {
            return new MediaUploader(
                $app['filesystem'],
                $app['mediable.source.factory'],
                $app['config']->get('mediable')
            );
        });
        $this->app->alias('mediable.uploader', MediaUploader::class);
    }

    /**
     * Bind the Media Uploader to the container.
     * @return void
     */
    public function registerMover(): void
    {
        $this->app->bind('mediable.mover', function (Container $app) {
            return new MediaMover($app['filesystem']);
        });
        $this->app->alias('mediable.mover', MediaMover::class);
    }

    /**
     * Bind the Media Uploader to the container.
     * @return void
     */
    public function registerUrlGeneratorFactory(): void
    {
        $this->app->singleton('mediable.url.factory', function (Container $app) {
            $factory = new UrlGeneratorFactory;

            $config = $app['config']->get('mediable.url_generators', []);
            foreach ($config as $driver => $generator) {
                $factory->setGeneratorForFilesystemDriver($generator, $driver);
            }

            return $factory;
        });
        $this->app->alias('mediable.url.factory', UrlGeneratorFactory::class);
    }

    public function registerImageManipulator(): void
    {
        $this->app->singleton(ImageManipulator::class);
    }

    /**
     * Add package commands to artisan console.
     * @return void
     */
    public function registerConsoleCommands(): void
    {
        $this->commands([
            ImportMediaCommand::class,
            PruneMediaCommand::class,
            SyncMediaCommand::class,
        ]);
    }
}
