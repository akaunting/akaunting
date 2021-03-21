<?php

namespace Enlightn\Enlightn\Analyzers\Security;

use Enlightn\Enlightn\Analyzers\Concerns\AnalyzesHeaders;
use Enlightn\Enlightn\Analyzers\Concerns\AnalyzesMiddleware;
use GuzzleHttp\Client;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Support\Str;

class XSSAnalyzer extends SecurityAnalyzer
{
    use AnalyzesMiddleware;
    use AnalyzesHeaders;

    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = 'Your application sets appropriate HTTP headers to protect against XSS attacks.';

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
     * Determine whether the analyzer should be run in CI mode.
     *
     * @var bool
     */
    public static $runInCI = false;

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
        $this->client = new Client();
    }

    /**
     * Get the error message describing the analyzer insights.
     *
     * @return string
     */
    public function errorMessage()
    {
        return 'Your application is not adequately protected from XSS attacks. The Content-Security-Policy '
            .'is either not set or not set adequately for XSS. It is recommended to set a "script-src" or '
            .'"default-src" policy directive without "unsafe-eval" or "unsafe-inline".';
    }

    /**
     * Execute the analyzer.
     *
     * @return void
     */
    public function handle()
    {
        $headers = $this->getHeadersOnUrl($url = $this->findLoginRoute(), 'Content-Security-Policy');

        if (! isset($headers)) {
            $policy = get_meta_tags($url)['Content-Security-Policy'] ?? '';
            if (empty($policy)) {
                $this->markFailed();
            } elseif (! Str::contains($policy, ['default-src', 'script-src']) || Str::contains($policy, 'unsafe')) {
                $this->markFailed();
            }
        } elseif (! collect($headers)->contains(function ($header) {
            return Str::contains($header, ['default-src', 'script-src']) && ! Str::contains($header, 'unsafe');
        })) {
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
        // Skip this analyzer if the app is stateless (e.g. API only apps).
        return $this->isLocalAndShouldSkip() || $this->appIsStateless();
    }
}
