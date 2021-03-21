<?php

namespace Enlightn\Enlightn\Analyzers\Security;

use Enlightn\Enlightn\Analyzers\Concerns\AnalyzesMiddleware;
use Enlightn\Enlightn\Analyzers\Concerns\InspectsCode;
use Enlightn\Enlightn\Inspection\Inspector;
use Enlightn\Enlightn\Inspection\QueryBuilder;
use Illuminate\Cache\RateLimiter;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Routing\Router;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\RateLimiter as RateLimiterFacade;
use Illuminate\Support\Str;

class LoginThrottlingAnalyzer extends SecurityAnalyzer
{
    use AnalyzesMiddleware;
    use InspectsCode;

    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = 'Your application includes login throttling for protection against brute force attacks.';

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
     * The routes that are not protected from CSRF.
     *
     * @var \Illuminate\Support\Collection
     */
    public $unprotectedRoutes;

    /**
     * Get the error message describing the analyzer insights.
     *
     * @return string
     */
    public function errorMessage()
    {
        return "Your application is not adequately protected from brute force attacks. This can be very dangerous and "
            ."you may resolve this by adding appropriate login throttling middleware to your login routes. We make an "
            ."educated guess that the following routes could be unprotected login routes: {$this->formatUnprotectedRoutes()}. "
            ."You may ignore this in case the throttling is already setup at the web server (Nginx, Apache) level instead "
            ."of the Laravel application level or in case you have devised your own custom throttling mechanism and are not "
            ."using the throttling middleware or RateLimiter class that ships with the Laravel Framework.";
    }

    /**
     * Execute the analyzer.
     *
     * @param \Enlightn\Enlightn\Inspection\Inspector $inspector
     * @return void
     * @throws \ReflectionException
     */
    public function handle(Inspector $inspector)
    {
        if ($this->appUsesMiddleware(ThrottleRequests::class)
            || $this->appUsesRateLimiterFacade($inspector)
            || $this->appUsesRateLimiterInstance($inspector)) {
            // We simply assume that if either a throttling middleware or the application uses the RateLimiter class,
            // then login is throttled properly. This is smarter than guessing the name of the login route.
            return;
        }

        if ($this->canFindLoginRoute()) {
            // If we can't guess the login route, we're not marking it as failed. There could be use cases where
            // some apps don't have login routes at all but are still stateful (e.g. the login could be proxied
            // from another app in the same domain that share cookies like a dashboard app).
            $this->markFailed();
        }
    }

    /**
     * Determine whether to skip the analyzer.
     *
     * @return bool
     * @throws \ReflectionException
     */
    public function skip()
    {
        // Skip this analyzer if the app is stateless (there is no login for stateless apps) or if the app
        // uses the laravel/ui package (that handles login throttling without middleware).
        return $this->appIsStateless() || trait_exists(\Illuminate\Foundation\Auth\AuthenticatesUsers::class)
            // Fortify also uses it's own authentication pipeline that has throttling. We skip this analyzer if we find
            // that the app uses Fortify's in-built login throttling.
            || (class_exists(\Laravel\Fortify\LoginRateLimiter::class) && is_null(config('fortify.limiters.login')));
    }

    /**
     * Determine whether we can find the login route.
     *
     * @return bool
     */
    protected function canFindLoginRoute()
    {
        $this->unprotectedRoutes = collect($this->router->getRoutes())->filter(function ($route) {
            // Exclude all non-POST route methods that don't need protection
            return collect($route->methods())->contains(function ($method) {
                return $method === 'POST';
            });
        })->filter(function ($route) {
            // Get all the routes that are "stateful" (exclude stateless API routes that don't need login)
            return $this->appUsesGlobalMiddleware(StartSession::class)
                || $this->routeUsesMiddleware($route, StartSession::class);
        })->filter(function ($route) {
            // Here we're just filtering out all the routes by guessing the login route name
            return Str::contains(strtolower($route->uri()), ['login', 'signin', 'auth']);
        })->map(function ($route) {
            // Prettify unprotected routes to display in error message
            return $route->uri();
        });

        return $this->unprotectedRoutes->count() > 0;
    }

    /**
     * Determine whether the app uses the RateLimiter facade
     *
     * @param \Enlightn\Enlightn\Inspection\Inspector $inspector
     * @return bool
     */
    protected function appUsesRateLimiterFacade(Inspector $inspector)
    {
        $builder = (new QueryBuilder())->hasStaticCall(RateLimiterFacade::class, 'hit');

        return $this->passesCodeInspection($inspector, $builder);
    }

    /**
     * Determine whether the app uses the RateLimiter facade
     *
     * @param \Enlightn\Enlightn\Inspection\Inspector $inspector
     * @return bool
     */
    protected function appUsesRateLimiterInstance(Inspector $inspector)
    {
        $builder = (new QueryBuilder())->instantiates(RateLimiter::class);

        return $this->passesCodeInspection($inspector, $builder);
    }

    /**
     * @return string
     */
    protected function formatUnprotectedRoutes()
    {
        return $this->unprotectedRoutes->join(', ', ' and ');
    }
}
