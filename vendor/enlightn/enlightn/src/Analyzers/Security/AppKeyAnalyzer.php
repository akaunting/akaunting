<?php

namespace Enlightn\Enlightn\Analyzers\Security;

use Enlightn\Enlightn\Analyzers\Concerns\ParsesConfigurationFiles;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Str;

class AppKeyAnalyzer extends SecurityAnalyzer
{
    use ParsesConfigurationFiles;

    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = 'Application key is set.';

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
    public $timeToFix = 1;

    /**
     * The error message describing the analyzer insights.
     *
     * @var string|null
     */
    public $errorMessage = null;

    /**
     * Determine whether the analyzer should be run in CI mode.
     *
     * @var bool
     */
    public static $runInCI = false;

    /**
     * Get the error message describing the analyzer insights.
     *
     * @return string
     */
    public function errorMessage()
    {
        return $this->errorMessage
                ?? ("Your app key is not set. This can be very dangerous as this key is used "
                ."to encrypt cookies, signed URLs, model data, job data and session data.");
    }

    /**
     * Execute the analyzer.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @return void
     */
    public function handle(ConfigRepository $config)
    {
        if (! $config->get('app.key')) {
            $this->recordError('app', 'key');

            return;
        }

        if (! $config->get('app.cipher')) {
            $this->recordError('app', 'cipher');

            return;
        }

        if (! Encrypter::supported($this->parseKey($config->get('app.key')), $config->get('app.cipher'))) {
            $this->recordError('app', 'key');

            $this->errorMessage = "Your app key and cipher combination is not supported. "
                . "This can be very dangerous as this key is used "
                . "to encrypt passwords, cookies, signed URLs, model data, CSRF tokens and session data.";
        }
    }

    /**
     * Parse the encryption key.
     *
     * @param string $key
     * @return string
     */
    protected function parseKey(string $key)
    {
        if (Str::startsWith($key, $prefix = 'base64:')) {
            $key = base64_decode(Str::after($key, $prefix));
        }

        return $key;
    }
}
