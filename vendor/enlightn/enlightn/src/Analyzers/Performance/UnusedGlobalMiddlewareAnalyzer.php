<?php

namespace Enlightn\Enlightn\Analyzers\Performance;

use Enlightn\Enlightn\Analyzers\Concerns\AnalyzesMiddleware;
use Enlightn\Enlightn\Inspection\Reflector;
use Fideloper\Proxy\TrustProxies;
use Fruitcake\Cors\HandleCors;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Middleware\TrustHosts;
use Illuminate\Routing\Router;

class UnusedGlobalMiddlewareAnalyzer extends PerformanceAnalyzer
{
    use AnalyzesMiddleware;

    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = 'Your application does not contain unused global HTTP middleware.';

    /**
     * The severity of the analyzer.
     *
     * @var string|null
     */
    public $severity = self::SEVERITY_MINOR;

    /**
     * The time to fix in minutes.
     *
     * @var int|null
     */
    public $timeToFix = 5;

    /**
     * A collection of unused middleware
     *
     * @var \Illuminate\Support\Collection
     */
    protected $unusedMiddleware;

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
     * Get the error message describing the analyzer insights.
     *
     * @return string
     */
    public function errorMessage()
    {
        return 'Your application contains global middleware that is not currently being used. It is '
            .'recommended to remove these middleware from your Kernel class to enhance performance '
            .'slightly. Your unused middleware include: '.$this->formatUnusedMiddleware();
    }

    /**
     * Execute the analyzer.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @param \Illuminate\Contracts\Config\Repository $config
     * @return void
     * @throws \ReflectionException
     */
    public function handle(Application $app, ConfigRepository $config)
    {
        $this->unusedMiddleware = collect();

        if (class_exists(TrustProxies::class) && $this->appUsesGlobalMiddleware(TrustProxies::class)) {
            $middlewareClass = collect($this->getGlobalMiddleware())->filter(function ($middleware) {
                return $middleware === TrustProxies::class || is_subclass_of($middleware, TrustProxies::class);
            })->first();

            $middleware = $app->make($middlewareClass);
            $proxies = Reflector::get($middleware, 'proxies');

            if (empty($proxies) && is_null($config->get('trustedproxy.proxies'))) {
                $this->unusedMiddleware->push(TrustProxies::class);

                if ($this->appUsesGlobalMiddleware(TrustHosts::class)) {
                    // Trusted hosts without trusted proxies is useless.
                    $this->unusedMiddleware->push(TrustHosts::class);
                }
            }
        } elseif ($this->appUsesGlobalMiddleware(TrustHosts::class)) {
            // Trusted hosts without trusted proxies is useless.
            $this->unusedMiddleware->push(TrustHosts::class);
        }

        if (empty($config->get('cors.paths', [])) && $this->appUsesGlobalMiddleware(HandleCors::class)) {
            $this->unusedMiddleware->push(HandleCors::class);
        }

        if ($this->unusedMiddleware->count() > 0) {
            $this->markFailed();
        }
    }

    /**
     * @return string
     */
    protected function formatUnusedMiddleware()
    {
        return $this->unusedMiddleware->map(function ($middlewareClass) {
            return '['.class_basename($middlewareClass).']';
        })->join(', ', ' and ');
    }
}
