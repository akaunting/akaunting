<?php

namespace Nwidart\Modules\Process;

use Nwidart\Modules\Module;

class Updater extends Runner
{
    protected $composerPath;

    /**
     * Update the dependencies for the specified module by given the module name.
     *
     * @param string $module
     */
    public function update($module)
    {
        $module = $this->module->findOrFail($module);

        chdir(base_path());
        $this->composerPath = config('app.composer_path');

        $this->installRequires($module);
        $this->installDevRequires($module);
        $this->copyScriptsToMainComposerJson($module);
    }

    /**
     * @param Module $module
     */
    private function installRequires(Module $module)
    {
        $packages = $module->getComposerAttr('require', []);

        $concatenatedPackages = '';
        foreach ($packages as $name => $version) {
            $concatenatedPackages .= "\"{$name}:{$version}\" ";
        }

        if(!empty($concatenatedPackages)) {
            if(config('app.composer_internal'))
                $this->run("php {$this->composerPath} require {$concatenatedPackages}");
            else
                $this->run("composer require {$concatenatedPackages}");
        }
    }

    /**
     * @param Module $module
     */
    private function installDevRequires(Module $module)
    {
        $devPackages = $module->getComposerAttr('require-dev', []);

        $concatenatedPackages = '';
        foreach ($devPackages as $name => $version) {
            $concatenatedPackages .= "\"{$name}:{$version}\" ";
        }

        if(!empty($concatenatedPackages)) {
            if(config('app.composer_internal'))
                $this->run("php {$this->composerPath} require --dev {$concatenatedPackages}");
            else
                $this->run("composer require --dev {$concatenatedPackages}");
        }
    }

    /**
     * @param Module $module
     */
    private function copyScriptsToMainComposerJson(Module $module)
    {
        $scripts = $module->getComposerAttr('scripts', []);

        $composer = json_decode(file_get_contents(base_path('composer.json')), true);

        foreach ($scripts as $key => $script) {
            if (array_key_exists($key, $composer['scripts'])) {
                $composer['scripts'][$key] = array_unique(array_merge($composer['scripts'][$key], $script));
                continue;
            }
            $composer['scripts'] = array_merge($composer['scripts'], [$key => $script]);
        }

        file_put_contents(base_path('composer.json'), json_encode($composer, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }
}
