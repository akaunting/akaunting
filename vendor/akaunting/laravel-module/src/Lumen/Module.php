<?php

namespace Akaunting\Module\Lumen;

use Illuminate\Support\Str;
use Akaunting\Module\Module as BaseModule;

class Module extends BaseModule
{
    /**
     * {@inheritdoc}
     */
    public function getCachedServicesPath()
    {
        return Str::replaceLast('services.php', $this->getSnakeName() . '_module.php', $this->app->basePath('storage/app/') . 'services.php');
    }

    /**
     * {@inheritdoc}
     */
    public function registerProviders()
    {
        foreach ($this->get('providers', []) as $provider) {
            $this->app->register($provider);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function registerAliases()
    {
    }
}
