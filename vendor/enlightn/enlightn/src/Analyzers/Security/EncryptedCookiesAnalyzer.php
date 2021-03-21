<?php

namespace Enlightn\Enlightn\Analyzers\Security;

use Enlightn\Enlightn\Analyzers\Concerns\AnalyzesMiddleware;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Router;

class EncryptedCookiesAnalyzer extends SecurityAnalyzer
{
    use AnalyzesMiddleware;

    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = 'Your application encrypts its cookies.';

    /**
     * The severity of the analyzer.
     *
     * @var string|null
     */
    public $severity = self::SEVERITY_CRITICAL;

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
     * Get the error message describing the analyzer insights.
     *
     * @return string
     */
    public function errorMessage()
    {
        return "Your application's cookies are not encrypted. This exposes your application to a wide variety "
            ."of security risks and potential attacks. An easy fix would be to add the EncryptCookies middleware "
            ."shipped with Laravel.";
    }

    /**
     * Execute the analyzer.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function handle()
    {
        if ($this->appUsesMiddleware(EncryptCookies::class)) {
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
        // Skip this analyzer if the app is stateless (e.g. API only apps) or doesn't use cookies.
        return $this->appIsStateless() || ! $this->appUsesCookies();
    }
}
