<?php

namespace Akaunting\Module\Commands;

use Akaunting\Module\Generators\FileGenerator;
use Akaunting\Module\Support\Config\GenerateConfigReader;
use Akaunting\Module\Support\Stub;
use Akaunting\Module\Traits\ModuleCommandTrait;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class ComponentMakeCommand extends GeneratorCommand
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
    protected $name = 'module:make-component';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new component for the specified module.';

    public function handle()
    {
        if (parent::handle() === E_ERROR) {
            return E_ERROR;
        }

        $this->createViewTemplate();

        return 0;
    }

    public function getDefaultNamespace() : string
    {
        $module = $this->laravel['module'];

        return $module->config('paths.generator.component.namespace') ?: $module->config('paths.generator.component.path');
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
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $module = $this->getModule();

        return (new Stub('/component.stub', [
            'ALIAS'             => $this->getModuleAlias(),
            'NAMESPACE'         => $this->getClassNamespace($module),
            'CLASS'             => $this->getClass(),
            'VIEW_NAME'         => 'components.' . $this->getViewName()
        ]))->render();
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = module()->getModulePath($this->getModuleAlias());

        $config = GenerateConfigReader::read('component');

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
     * Create the view template of the component.
     *
     * @return void
     */
    protected function createViewTemplate()
    {
        $overwrite_file = $this->hasOption('force') ? $this->option('force') : false;

        $path = $this->getViewTemplatePath();

        $contents = $this->getViewTemplateContents();

        (new FileGenerator($path, $contents))->withFileOverwrite($overwrite_file)->generate();
    }

    protected function getViewTemplatePath()
    {
        $module_path = $this->laravel['module']->getModulePath($this->getModuleAlias());

        $folder = $module_path . GenerateConfigReader::read('view')->getPath() . '/components/';

        if (!File::isDirectory($folder)) {
            File::makeDirectory($folder);
        }

        return $folder . $this->getViewName() . '.blade.php';
    }

    protected function getViewTemplateContents()
    {
        $quote = Inspiring::quote();

        return <<<HTML
            <div>
                {$quote}
            </div>
        HTML;
    }

    protected function getViewName()
    {
        return Str::kebab($this->argument('name'));
    }
}
