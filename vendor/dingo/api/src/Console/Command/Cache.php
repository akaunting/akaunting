<?php

namespace Dingo\Api\Console\Command;

use Dingo\Api\Routing\Router;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Dingo\Api\Contract\Routing\Adapter;
use Illuminate\Contracts\Console\Kernel;

class Cache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    public $signature = 'api:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    public $description = 'Create a route cache file for faster route registration';

    /**
     * Filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Router instance.
     *
     * @var \Dingo\Api\Routing\Router
     */
    private $router;

    /**
     * Adapter instance.
     *
     * @var \Dingo\Api\Contract\Routing\Adapter
     */
    private $adapter;

    /**
     * Create a new cache command instance.
     *
     * @param \Illuminate\Filesystem\Filesystem   $files
     * @param \Dingo\Api\Routing\Router           $router
     * @param \Dingo\Api\Contract\Routing\Adapter $adapter
     *
     * @return void
     */
    public function __construct(Filesystem $files, Router $router, Adapter $adapter)
    {
        $this->files = $files;
        $this->router = $router;
        $this->adapter = $adapter;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->callSilent('route:clear');

        $app = $this->getFreshApplication();

        $this->call('route:cache');

        $routes = $app['api.router']->getAdapterRoutes();

        foreach ($routes as $collection) {
            foreach ($collection as $route) {
                $app['api.router.adapter']->prepareRouteForSerialization($route);
            }
        }

        $stub = "app('api.router')->setAdapterRoutes(unserialize(base64_decode('{{routes}}')));";
        $path = $this->laravel->getCachedRoutesPath();

        if (! $this->files->exists($path)) {
            $stub = "<?php\n\n$stub";
        }

        $this->files->append(
            $path,
            str_replace('{{routes}}', base64_encode(serialize($routes)), $stub)
        );
    }

    /**
     * Get a fresh application instance.
     *
     * @return \Illuminate\Contracts\Container\Container
     */
    protected function getFreshApplication()
    {
        if (method_exists($this->laravel, 'bootstrapPath')) {
            $app = require $this->laravel->bootstrapPath().'/app.php';
        } else {
            $app = require $this->laravel->basePath().'/bootstrap/app.php';
        }

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
