<?php

namespace Akaunting\Module\Commands;

use Akaunting\Module\Module;
use Akaunting\Module\Support\Config\GenerateConfigReader;
use Akaunting\Module\Support\Stub;
use Akaunting\Module\Traits\ModuleCommandTrait;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ProviderMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The name of argument name.
     *
     * @var string
     */
    protected $argumentName = 'name';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-provider';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service provider class for the specified module.';

    public function getDefaultNamespace() : string
    {
        return $this->laravel['module']->config('paths.generator.provider.path', 'Providers');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The service provider name.'],
            ['alias', InputArgument::OPTIONAL, 'The alias of module will be used.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['master', null, InputOption::VALUE_NONE, 'Indicates the master service provider', null],
        ];
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $stub = $this->option('master') ? 'scaffold/provider' : 'provider';

        /** @var Module $module */
        $module = $this->getModule();

        return (new Stub('/' . $stub . '.stub', [
            'ALIAS'                 => $module->getAlias(),
            'NAMESPACE'             => $this->getClassNamespace($module),
            'CLASS'                 => $this->getClass(),
            'MODULE'                => $this->getModuleName(),
            'NAME'                  => $this->getFileName(),
            'STUDLY_NAME'           => $module->getStudlyName(),
            'MODULE_NAMESPACE'      => $this->laravel['module']->config('namespace'),
            'COMPONENT_NAMESPACE'   => $this->getComponentNamespace(),
            'PATH_VIEWS'            => GenerateConfigReader::read('view')->getPath(),
            'PATH_LANG'             => GenerateConfigReader::read('lang')->getPath(),
            'PATH_CONFIG'           => GenerateConfigReader::read('config')->getPath(),
            'MIGRATIONS_PATH'       => GenerateConfigReader::read('migration')->getPath(),
            'FACTORIES_PATH'        => GenerateConfigReader::read('factory')->getPath(),
            'ROUTES_PATH'           => GenerateConfigReader::read('route')->getPath(),
        ]))->render();
    }
    /**
     * @return string
     */
    private function getFileName()
    {
        return Str::studly($this->argument('name'));
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = module()->getModulePath($this->getModuleAlias());

        $generatorPath = GenerateConfigReader::read('provider');

        return $path . $generatorPath->getPath() . '/' . $this->getFileName() . '.php';
    }

    protected function getComponentNamespace()
    {
        $module = $this->laravel['module'];

        $namespace = $module->config('namespace');

        $namespace .= '\\' . $this->getModuleName() . '\\';

        $namespace .= $module->config('paths.generator.component.namespace') ?: $module->config('paths.generator.component.path');

        $namespace = str_replace('/', '\\', $namespace);

        return trim($namespace, '\\');
    }
}
