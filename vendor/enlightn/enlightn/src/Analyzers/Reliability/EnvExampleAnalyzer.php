<?php

namespace Enlightn\Enlightn\Analyzers\Reliability;

use Dotenv\Dotenv;

class EnvExampleAnalyzer extends ReliabilityAnalyzer
{
    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = "All env variables used in your .env file are defined in your .env.example file.";

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
    public $timeToFix = 10;

    /**
     * The missing .env variables.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $missingEnvVariables;

    /**
     * Get the error message describing the analyzer insights.
     *
     * @return string
     */
    public function errorMessage()
    {
        return "Your application has some missing environment variables that are defined in your .env file "
            ."but missing in your .env.example file: ".$this->formatMissingEnvVariables();
    }

    /**
     * Execute the analyzer.
     *
     * @return void
     */
    public function handle()
    {
        if (method_exists(Dotenv::class, 'createImmutable')) {
            $this->handleDotEnvV4();

            return;
        }

        $examples = Dotenv::create(base_path(), '.env.example');
        $actual = Dotenv::create(base_path(), '.env');

        $examples->safeLoad();
        $actual->safeLoad();

        $this->missingEnvVariables = collect($actual->getEnvironmentVariableNames())
            ->diff($examples->getEnvironmentVariableNames());

        if (! $this->missingEnvVariables->isEmpty()) {
            $this->markFailed();
        }
    }

    /**
     * Execute the analyzer for DotEnv v4.
     */
    protected function handleDotEnvV4()
    {
        $examples = Dotenv::createMutable(base_path(), '.env.example');
        $actual = Dotenv::createMutable(base_path(), '.env');

        $this->missingEnvVariables = collect($actual->safeLoad())
            ->diffKeys($examples->safeLoad())
            ->keys();

        if (! $this->missingEnvVariables->isEmpty()) {
            $this->markFailed();
        }
    }

    /**
     * @return string
     */
    protected function formatMissingEnvVariables()
    {
        return $this->missingEnvVariables->join(', ', ' and ');
    }
}
