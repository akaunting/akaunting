<?php

namespace Enlightn\Enlightn\Analyzers\Security;

use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Throwable;

class EnvAccessAnalyzer extends SecurityAnalyzer
{
    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = 'Your .env is not publicly accessible.';

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
     * Determine whether the analyzer should be run in CI mode.
     *
     * @var bool
     */
    public static $runInCI = false;

    /**
     * The Guzzle client instance.
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Create a new analyzer instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Get the error message describing the analyzer insights.
     *
     * @return string
     */
    public function errorMessage()
    {
        return "Your .env file seems to be publicly accessible.";
    }

    /**
     * Execute the analyzer.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $response = $this->client->get(url('.env'));

            if (Str::contains((string) $response->getBody(), ['APP_NAME=', 'APP_ENV=', 'APP_KEY='])) {
                $this->markFailed();
            }
        } catch (Throwable $e) {
            return;
        }
    }

    /**
     * Set the Guzzle client.
     *
     * @param \GuzzleHttp\Client $client
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
    }
}
