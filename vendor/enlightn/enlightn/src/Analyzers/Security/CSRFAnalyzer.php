<?php

namespace Enlightn\Enlightn\Analyzers\Security;

use Enlightn\Enlightn\Analyzers\Concerns\AnalyzesMiddleware;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Router;
use Illuminate\Support\Str;

class CSRFAnalyzer extends SecurityAnalyzer
{
    use AnalyzesMiddleware;

    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = 'Your application includes middleware to protect against CSRF attacks.';

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
    public $timeToFix = 5;

    /**
     * The routes that are not protected from CSRF.
     *
     * @var \Illuminate\Support\Collection
     */
    public $unprotectedRoutes;

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
        return "Your application is not adequately protected from CSRF attacks. There are {$this->unprotectedRoutes->count()} "
            ."unprotected routes, which include: {$this->formatUnprotectedRoutes()}. This can be very dangerous and you must "
            ."resolve this by adding CSRF middleware to your web routes.";
    }

    /**
     * Execute the analyzer.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function handle()
    {
        if ($this->webMiddlewareGroupIsProtected()
            || $this->appIsGloballyProtected()
            || $this->routesAreIndividuallyProtected()) {
            return;
        }

        $this->markFailed();
    }

    /**
     * Determine whether to skip the analyzer.
     *
     * @return bool
     * @throws \ReflectionException
     */
    public function skip()
    {
        // Skip this analyzer if the app is stateless or does not use cookies (app does not need CSRF protection).
        return $this->appIsStateless() || ! $this->appUsesCookies();
    }

    /**
     * Determine if the "web" middleware group is protected from CSRF.
     *
     * @return bool
     */
    protected function webMiddlewareGroupIsProtected()
    {
        if (isset($this->kernel->getMiddlewareGroups()['web'])) {
            if (collect($this->kernel->getMiddlewareGroups()['web'])->contains(function ($middleware) {
                return is_subclass_of($middleware, VerifyCsrfToken::class);
            })) {
                // Analysis passed as the web middleware group has the VerifyCsrfToken middleware
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the application is globally protected from CSRF.
     *
     * @return bool
     * @throws \ReflectionException
     */
    protected function appIsGloballyProtected()
    {
        if ($this->appUsesGlobalMiddleware(VerifyCsrfToken::class)) {
            // Analysis passed as the VerifyCsrfToken middleware is global
            return true;
        }

        return false;
    }

    /**
     * Determine if all routes are individually protected from CSRF.
     *
     * @return bool
     */
    protected function routesAreIndividuallyProtected()
    {
        $this->unprotectedRoutes = collect($this->router->getRoutes())->filter(function ($route) {
            // Exclude the whitelisted route methods that don't need protection
            return collect($route->methods())->contains(function ($method) {
                return ! in_array($method, ['HEAD', 'GET', 'OPTIONS']);
            });
        })->filter(function ($route) {
            // Get the routes that don't apply the VerifyCsrfToken middleware
            return ! $this->routeUsesMiddleware($route, VerifyCsrfToken::class);
        })->filter(function ($route) {
            // Exclude the routes that are API routes (do not need CSRF protection)
            return ! Str::is('/api/*', $route->uri());
        })->map(function ($route) {
            // Prettify unprotected routes to display in error message
            return '['.implode(',', $route->methods()).'] '.$route->uri();
        });

        return $this->unprotectedRoutes->count() == 0;
    }

    /**
     * @return string
     */
    protected function formatUnprotectedRoutes()
    {
        return $this->unprotectedRoutes->join(', ', ' and ');
    }
}
