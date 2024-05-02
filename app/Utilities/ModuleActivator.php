<?php

namespace App\Utilities;

use Akaunting\Module\Contracts\ActivatorInterface;
use Akaunting\Module\Module;
use App\Models\Module\Module as Model;
use App\Traits\Companies;
use App\Traits\Modules;
use Illuminate\Cache\CacheManager as Cache;
use Illuminate\Config\Repository as Config;
use Illuminate\Container\Container;

class ModuleActivator implements ActivatorInterface
{
    use Companies, Modules;

    public Cache $cache;

    public Config $config;

    public array $statuses;

    public int $company_id;

    public function __construct(Container $app)
    {
        $this->cache = $app['cache'];
        $this->config = $app['config'];

        $this->load();
    }

    public function is(Module $module, bool $active): bool
    {
        if (app()->runningInConsole()) {
            return true;
        }

        if (running_in_install() && in_array($module->getAlias(), ['offline-payments', 'paypal-standard'])) {
            return true;
        }

        if (! isset($this->statuses[$module->getAlias()])) {
            return false;
        }

        return $this->statuses[$module->getAlias()] === $active;
    }

    public function enable(Module $module): void
    {
        $this->setActive($module, true);
    }

    public function disable(Module $module): void
    {
        $this->setActive($module, false);
    }

    public function setActive(Module $module, bool $active): void
    {
        $this->statuses[$module->getAlias()] = $active;

        $this->flushCache();

        if (empty($this->company_id)) {
            $company_id = $this->getCompanyId();

            if (empty($company_id)) {
                return;
            }

            $this->company_id = $company_id;
        }

        $model = Model::companyId($this->company_id)->alias($module->getAlias())->first();

        if (! empty($model)) {
            $model->enabled = $active;
            $model->save();

            return;
        }

        Model::create([
            'company_id'    => $this->company_id,
            'alias'         => $module->getAlias(),
            'enabled'       => $active,
            'created_from'  => 'core::activator',
        ]);
    }

    public function delete(Module $module): void
    {
        if (! isset($this->statuses[$module->getAlias()])) {
            return;
        }

        unset($this->statuses[$module->getAlias()]);

        Model::companyId($this->company_id)->alias($module->getAlias())->delete();

        $this->flushCache();
    }

    public function reset(): void
    {
        $this->statuses = [];

        $this->flushCache();
    }

    public function load(): void
    {
        $this->statuses = $this->getStatusesByCompany();
    }

    public function getStatusesByCompany(): array
    {
        if (! $this->config->get('module.cache.enabled')) {
            return $this->readDatabase();
        }

        $key = $this->config->get('module.cache.key') . '.statuses';
        $lifetime = $this->config->get('module.cache.lifetime');

        return $this->cache->remember($key, $lifetime, function () {
            return $this->readDatabase();
        });
    }

    public function readDatabase(): array
    {
        if (app()->runningInConsole()) {
            return [];
        }

        $company_id = $this->getCompanyId();

        if (empty($company_id)) {
            return [];
        }

        $this->company_id = $company_id;

        $modules = Model::companyId($this->company_id)->pluck('enabled', 'alias')->toArray();

        foreach ($modules as $alias => $enabled) {
            if (in_array($alias, ['offline-payments', 'paypal-standard'])) {
                continue;
            }

            $subscription = $this->getSubscription($alias);

            if (! is_object($subscription)) {
                continue;
            }

            $modules[$alias] = $subscription->status;
        }

        return $modules;
    }

    public function flushCache(): void
    {
        $key = $this->config->get('module.cache.key') . '.statuses';

        $this->cache->forget($key);
    }

    public function register(): void
    {
        $this->load();

        app()->register(\Akaunting\Module\Providers\Bootstrap::class, true);
    }
}
