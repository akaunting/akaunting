<?php

namespace Enlightn\Enlightn\Analyzers\Performance;

use Enlightn\Enlightn\Analyzers\Concerns\AnalyzesMiddleware;
use Enlightn\Enlightn\Analyzers\Concerns\ParsesConfigurationFiles;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;

class SessionDriverAnalyzer extends PerformanceAnalyzer
{
    use ParsesConfigurationFiles;
    use AnalyzesMiddleware;

    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = 'A proper session driver is configured.';

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
     * Create a new analyzer instance.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @param  \Illuminate\Contracts\Http\Kernel  $kernel
     * @return void
     */
    public function __construct(Router $router, Kernel $kernel)
    {
        $this->router = $router;
        $this->kernel = $kernel;
    }

    /**
     * Execute the analyzer.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @return void
     */
    public function handle(ConfigRepository $config)
    {
        $driver = ucfirst($config->get('session.driver'));

        if (method_exists($this, "assess{$driver}Driver")) {
            if (! $this->{"assess{$driver}Driver"}($config)) {
                // Record an error if the assessment failed.
                $this->recordError('session', 'driver');
            }
        }
    }

    /**
     * Assess whether a proper session driver is set.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @return bool
     */
    protected function assessNullDriver($config)
    {
        $this->errorMessage = "Your session driver is set to null while you have some routes that "
            ."use the session. This means that all session read operations will result in a miss. "
            ."This setting is only suitable for test environments in specific situations.";

        return false;
    }

    /**
     * Assess whether a proper session driver is set.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @return bool
     */
    protected function assessArrayDriver($config)
    {
        $this->errorMessage = "Your session driver is set to array while you have some routes that "
            ."use the session. This means that session data will not be persisted outside the running "
            ."PHP process in any way. This setting is only suitable for testing.";

        return false;
    }

    /**
     * Assess whether a proper session driver is set.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @return bool
     */
    protected function assessFileDriver($config)
    {
        if ($config->get('app.env') === 'local') {
            // file system session is perfectly fine for local dev
            return true;
        }

        $this->errorMessage = "Your session driver is set to file in a non-local environment "
            ."while you have some routes that use the session. This means that your app uses "
            ."the local filesystem for persisting session data. This setting is only "
            ."suitable if your app is hosted on a single server setup. Even for single "
            ."server setups, a session system such as Redis or Memcached are better suited "
            ."for performance (when using unix sockets) and more efficient eviction of expired "
            ."session items.";

        $this->severity = self::SEVERITY_MINOR;

        return false;
    }

    /**
     * Assess whether a proper session driver is set.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @return bool
     */
    protected function assessCookieDriver($config)
    {
        if ($config->get('app.env') === 'local') {
            // cookie sessions are perfectly fine for local dev
            return true;
        }

        $this->errorMessage = "Your session driver is set to cookie in a non-local environment "
            ."while you have some routes that use the session. This means that your app uses "
            ."client-side cookies for persisting session data. This setting is not advisable as "
            ."cookies have a size limit of 4kB, are stored on the client-side, are temporary in "
            ."nature and may be susceptible to change on the client side if you aren't using "
            ."the EncryptCookies middleware. Consider changing your session driver to more robust "
            ."options such as database, Redis, Memcached or DynamoDB.";

        $this->severity = self::SEVERITY_MINOR;

        return false;
    }

    /**
     * Determine whether to skip the analyzer.
     *
     * @return bool
     * @throws \ReflectionException
     */
    public function skip()
    {
        // Skip this analyzer if the app is stateless and does not use sessions.
        return $this->appIsStateless();
    }
}
