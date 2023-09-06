<?php

namespace Laratrust\Console;

use Illuminate\Support\Facades\Config;
use Illuminate\Console\GeneratorCommand;

class MakePermissionCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'laratrust:permission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Permission model if it does not exist';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Permission model';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/../../stubs/permission.stub';
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return Config::get('laratrust.models.permission', 'Permission');
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }
}
