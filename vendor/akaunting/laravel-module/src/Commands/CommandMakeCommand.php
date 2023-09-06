<?php

namespace Akaunting\Module\Commands;

use Illuminate\Support\Str;
use Akaunting\Module\Support\Config\GenerateConfigReader;
use Akaunting\Module\Support\Stub;
use Akaunting\Module\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CommandMakeCommand extends GeneratorCommand
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
    protected $name = 'module:make-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new Artisan command for the specified module.';

    public function getDefaultNamespace() : string
    {
        return $this->laravel['module']->config('paths.generator.command.path', 'Console');
    }

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
            ['command', null, InputOption::VALUE_OPTIONAL, 'The terminal command that should be assigned.', null],
        ];
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $module = $this->getModule();

        return (new Stub('/command.stub', [
            'ALIAS' => $this->getModuleAlias(),
            'COMMAND_NAME' => $this->getCommandName(),
            'NAMESPACE' => $this->getClassNamespace($module),
            'CLASS' => $this->getClass(),
        ]))->render();
    }

    /**
     * @return string
     */
    private function getCommandName()
    {
        return $this->option('command') ?: 'command:name';
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = module()->getModulePath($this->getModuleAlias());

        $commandPath = GenerateConfigReader::read('command');

        return $path . $commandPath->getPath() . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return Str::studly($this->argument('name'));
    }
}
