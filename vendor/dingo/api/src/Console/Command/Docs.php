<?php

namespace Dingo\Api\Console\Command;

use ReflectionClass;
use Dingo\Blueprint\Writer;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Dingo\Api\Routing\Router;
use Dingo\Blueprint\Blueprint;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class Docs extends Command
{
    /**
     * Router instance.
     *
     * @var \Dingo\Api\Routing\Router
     */
    protected $router;

    /**
     * The blueprint instance.
     *
     * @var \Dingo\Blueprint\Blueprint
     */
    protected $blueprint;

    /**
     * Blueprint instance.
     *
     * @var \Dingo\Blueprint\Blueprint
     */
    protected $docs;

    /**
     * Writer instance.
     *
     * @var \Dingo\Blueprint\Writer
     */
    protected $writer;

    /**
     * Default documentation name.
     *
     * @var string
     */
    protected $name;

    /**
     * Default documentation version.
     *
     * @var string
     */
    protected $version;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:docs {--name= : Name of the generated documentation}
                                     {--use-version= : Version of the documentation to be generated}
                                     {--output-file= : Output the generated documentation to a file}
                                     {--include-path= : Path where included documentation files are located}
                                     {--use-controller= : Specify a controller where to generate documentation for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate API documentation from annotated controllers';

    /**
     * Create a new docs command instance.
     *
     * @param \Dingo\Api\Routing\Router  $router
     * @param \Dingo\Blueprint\Blueprint $blueprint
     * @param \Dingo\Blueprint\Writer    $writer
     * @param string                     $name
     * @param string                     $version
     *
     * @return void
     */
    public function __construct(Router $router, Blueprint $blueprint, Writer $writer, $name, $version)
    {
        parent::__construct();

        $this->router = $router;
        $this->blueprint = $blueprint;
        $this->writer = $writer;
        $this->name = $name;
        $this->version = $version;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $contents = $this->blueprint->generate($this->getControllers(), $this->getDocName(), $this->getVersion(), $this->getIncludePath());

        if ($file = $this->option('output-file')) {
            $this->writer->write($contents, $file);

            return $this->info('Documentation was generated successfully.');
        }

        return $this->line($contents);
    }

    /**
     * Get the documentation name.
     *
     * @return string
     */
    protected function getDocName()
    {
        $name = $this->option('name') ?: $this->name;

        if (! $name) {
            $this->comment('A name for the documentation was not supplied. Use the --name option or set a default in the configuration.');

            exit;
        }

        return $name;
    }

    /**
     * Get the include path for documentation files.
     *
     * @return string
     */
    protected function getIncludePath()
    {
        return base_path($this->option('include-path'));
    }

    /**
     * Get the documentation version.
     *
     * @return string
     */
    protected function getVersion()
    {
        $version = $this->option('use-version') ?: $this->version;

        if (! $version) {
            $this->comment('A version for the documentation was not supplied. Use the --use-version option or set a default in the configuration.');

            exit;
        }

        return $version;
    }

    /**
     * Get all the controller instances.
     *
     * @return array
     */
    protected function getControllers()
    {
        $controllers = new Collection;

        if ($controller = $this->option('use-controller')) {
            $this->addControllerIfNotExists($controllers, app($controller));

            return $controllers;
        }

        foreach ($this->router->getRoutes() as $collections) {
            foreach ($collections as $route) {
                if ($controller = $route->getControllerInstance()) {
                    $this->addControllerIfNotExists($controllers, $controller);
                }
            }
        }

        return $controllers;
    }

    /**
     * Add a controller to the collection if it does not exist. If the
     * controller implements an interface suffixed with "Docs" it
     * will be used instead of the controller.
     *
     * @param \Illuminate\Support\Collection $controllers
     * @param object                         $controller
     *
     * @return void
     */
    protected function addControllerIfNotExists(Collection $controllers, $controller)
    {
        $class = get_class($controller);

        if ($controllers->has($class)) {
            return;
        }

        $reflection = new ReflectionClass($controller);

        $interface = Arr::first($reflection->getInterfaces(), function ($key, $value) {
            return Str::endsWith($key, 'Docs');
        });

        if ($interface) {
            $controller = $interface;
        }

        $controllers->put($class, $controller);
    }
}
