<?php

namespace Akaunting\Module\Commands;

use Illuminate\Support\Str;
use Akaunting\Module\Module;
use Akaunting\Module\Support\Config\GenerateConfigReader;
use Akaunting\Module\Support\Stub;
use Akaunting\Module\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ListenerMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    protected $argumentName = 'name';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-listener';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new event listener class for the specified module';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the command.'],
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
            ['event', 'e', InputOption::VALUE_OPTIONAL, 'The event class being listened for.'],
            ['queued', null, InputOption::VALUE_NONE, 'Indicates the event listener should be queued.'],
        ];
    }

    protected function getTemplateContents()
    {
        $module = $this->getModule();

        return (new Stub($this->getStubName(), [
            'ALIAS'             => $module->getAlias(),
            'NAMESPACE'         => $this->getClassNamespace($module),
            'EVENTNAME'         => $this->getEventName($module),
            'SHORTEVENTNAME'    => $this->getShortEventName(),
            'CLASS'             => $this->getClass(),
        ]))->render();
    }

    public function getDefaultNamespace() : string
    {
        return $this->laravel['module']->config('paths.generator.listener.path', 'Listeners');
    }

    protected function getEventName(Module $module)
    {
        $config = GenerateConfigReader::read('event');

        $name = $this->laravel['module']->config('namespace') . "\\" . $module->getStudlyName() . "\\" . $config->getPath() . "\\" . $this->option('event');

        return str_replace('/', '\\', $name);
    }

    protected function getShortEventName()
    {
        return class_basename($this->option('event'));
    }

    protected function getDestinationFilePath()
    {
        $path = module()->getModulePath($this->getModuleAlias());

        $config = GenerateConfigReader::read('listener');

        return $path . $config->getPath() . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    protected function getFileName()
    {
        return Str::studly($this->argument('name'));
    }

    /**
     * @return string
     */
    protected function getStubName(): string
    {
        if ($this->option('queued')) {
            if ($this->option('event')) {
                return '/listener-queued.stub';
            }

            return '/listener-queued-duck.stub';
        }

        if ($this->option('event')) {
            return '/listener.stub';
        }

        return '/listener-duck.stub';
    }
}
