<?php

namespace Barryvdh\DomPDF;

use Dompdf\Dompdf;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @throws \Exception
     * @return void
     */
    public function register(): void
    {
        $configPath = __DIR__ . '/../config/dompdf.php';
        $this->mergeConfigFrom($configPath, 'dompdf');

        $this->app->bind('dompdf.options', function ($app) {
            $defines = $app['config']->get('dompdf.defines');

            if ($defines) {
                $options = [];
                /**
                 * @var string $key
                 * @var mixed $value
                 */
                foreach ($defines as $key => $value) {
                    $key = strtolower(str_replace('DOMPDF_', '', $key));
                    $options[$key] = $value;
                }
            } else {
                $options = $app['config']->get('dompdf.options');
            }

            return $options;
        });

        $this->app->bind('dompdf', function ($app) {

            $options = $app->make('dompdf.options');
            $dompdf = new Dompdf($options);
            $path = realpath($app['config']->get('dompdf.public_path') ?: base_path('public'));
            if ($path === false) {
                throw new \RuntimeException('Cannot resolve public path');
            }
            $dompdf->setBasePath($path);

            return $dompdf;
        });
        $this->app->alias('dompdf', Dompdf::class);

        $this->app->bind('dompdf.wrapper', function ($app) {
            return new PDF($app['dompdf'], $app['config'], $app['files'], $app['view']);
        });
    }

    /**
     * Check if package is running under Lumen app
     */
    protected function isLumen(): bool
    {
        return Str::contains($this->app->version(), 'Lumen') === true;
    }

    public function boot(): void
    {
        if (! $this->isLumen()) {
            $configPath = __DIR__ . '/../config/dompdf.php';
            $this->publishes([$configPath => config_path('dompdf.php')], 'config');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<string>
     */
    public function provides(): array
    {
        return ['dompdf', 'dompdf.options', 'dompdf.wrapper'];
    }
}
