<?php

namespace Akaunting\Module\Commands;

use Akaunting\Module\Module;
use Akaunting\Module\Support\Config\GenerateConfigReader;
use Akaunting\Module\Support\Stub;
use Akaunting\Module\Traits\ModuleCommandTrait;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ObserverMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    protected $argumentName = 'name';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-observer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new observer class for the specified module';

    public function getTemplateContents()
    {
        $module = $this->getModule();

        return (new Stub($this->getStubName(), [
            'NAMESPACE' => $this->getClassNamespace($module),
            'CLASS' => $this->getClass(),
            'MODELNAME' => $this->getModelName($module),
            'SHORTMODELNAME' => $this->getShortModelName(),
            'MODELVARIABLE' => $this->getModelVariable(),
        ]))->render();
    }

    public function getDestinationFilePath()
    {
        $path = $this->laravel['module']->getModulePath($this->getModuleAlias());

        $observerPath = GenerateConfigReader::read('observer');

        return $path . $observerPath->getPath() . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    protected function getFileName()
    {
        return Str::studly($this->argument('name'));
    }

    public function getDefaultNamespace(): string
    {
        return $this->laravel['module']->config('paths.generator.observer.path', 'Observers');
    }

    protected function getModelName(Module $module)
    {
        $config = GenerateConfigReader::read('model');

        $name = $this->laravel['module']->config('namespace') . "\\" . $module->getStudlyName() . "\\" . $config->getPath() . "\\" . $this->option('model');

        return str_replace('/', '\\', $name);
    }

    protected function getShortModelName()
    {
        return class_basename($this->option('model'));
    }

    protected function getModelVariable()
    {
        return strtolower(class_basename($this->option('model')));
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the observer.'],
            ['alias', InputArgument::OPTIONAL, 'The alias of module will be used.'],
        ];
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'The model that the observer applies to.'],
        ];
    }

    /**
     * Get the stub file name based on the options
     * @return string
     */
    protected function getStubName()
    {
        $stub = '/observer-plain.stub';

        if ($this->option('model')) {
            $stub = '/observer.stub';
        }

        return $stub;
    }
}
