<?php

namespace Enlightn\Enlightn\Analyzers\Performance;

use Enlightn\Enlightn\Analyzers\Concerns\ParsesConfigurationFiles;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

class CacheDriverAnalyzer extends PerformanceAnalyzer
{
    use ParsesConfigurationFiles;

    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = 'A proper cache driver is configured.';

    /**
     * The severity of the analyzer.
     *
     * @var string|null
     */
    public $severity = self::SEVERITY_MAJOR;

    /**
     * The time to fix in minutes.
     *
     * @var int|null
     */
    public $timeToFix = 60;

    /**
     * Execute the analyzer.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @return void
     */
    public function handle(ConfigRepository $config)
    {
        $defaultStore = $config->get('cache.default');
        $driver = ucfirst($config->get("cache.stores.{$defaultStore}.driver", "null"));

        if (method_exists($this, "assess{$driver}Driver")) {
            if (! $this->{"assess{$driver}Driver"}($config)) {
                $this->recordError('cache', 'default');
            }
        }
    }

    /**
     * Assess whether a proper cache driver is set.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @return bool
     */
    protected function assessNullDriver($config)
    {
        $this->errorMessage = "Your cache driver is set to null. This means that your app is not "
            ."using caching and all cache read operations will result in a miss. This setting is "
            ."only suitable for test environments in specific situations.";

        return false;
    }

    /**
     * Assess whether a proper cache driver is set.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @return bool
     */
    protected function assessArrayDriver($config)
    {
        $this->errorMessage = "Your cache driver is set to array. This means that your app is not "
            ."using caching and caches will not be persisted outside the running PHP process in any way. "
            ."This setting is only suitable for testing.";

        return false;
    }

    /**
     * Assess whether a proper cache driver is set.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @return bool
     */
    protected function assessFileDriver($config)
    {
        if ($config->get('app.env') === 'local') {
            // file system cache is perfectly fine for local dev
            return true;
        }

        $this->errorMessage = "Your cache driver is set to file in a non-local environment. "
            ."This means that your app uses the local filesystem for caching. This setting is "
            ."only suitable if your app is hosted on a single server setup. Even for single "
            ."server setups, a cache system such as Redis or Memcached are better suited "
            ."for performance (when using unix sockets) and more efficient eviction of expired "
            ."cache items.";

        $this->severity = self::SEVERITY_MINOR;

        return false;
    }

    /**
     * Assess whether a proper cache driver is set.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @return bool
     */
    protected function assessDatabaseDriver($config)
    {
        if ($config->get('app.env') === 'local') {
            // database cache is perfectly fine for local dev
            return true;
        }

        $this->errorMessage = "Your cache driver is set to database in a non-local environment. "
            ."This setting is not suitable for production environments. Cache drivers such as "
            ."Redis or Memcached are much more robust and better suited for production.";

        $this->severity = self::SEVERITY_MINOR;

        return false;
    }
}
