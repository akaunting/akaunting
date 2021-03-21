<?php

namespace Akaunting\Module\Traits;

trait ModuleCommandTrait
{
    /**
     * Get the module instance.
     *
     * @return \Akaunting\Module\Module
     */
    public function getModule()
    {
        $alias = $this->argument('alias') ?: app('module')->getUsedNow();

        $module = module()->findOrFail($alias);

        return $module;
    }

    /**
     * Get the module name.
     *
     * @return string
     */
    public function getModuleName()
    {
        return $this->getModule()->getStudlyName();
    }

    /**
     * Get the module name.
     *
     * @return string
     */
    public function getModuleAlias()
    {
        return $this->getModule()->getAlias();
    }
}
