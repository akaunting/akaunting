<?php

namespace Enlightn\Enlightn\Analyzers\Security;

use Enlightn\Enlightn\NPM;

class FrontendVulnerableDependencyAnalyzer extends SecurityAnalyzer
{
    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = 'Your application does not rely on frontend dependencies with known security issues.';

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
    public $timeToFix = 60;

    /**
     * The NPM instance.
     *
     * @var \Enlightn\Enlightn\NPM
     */
    private $NPM;

    /**
     * The number of frontend vulnerabilities.
     *
     * @var int
     */
    protected $vulnerabilityCount;

    /**
     * Create a new analyzer instance.
     *
     * @param \Enlightn\Enlightn\NPM $NPM
     */
    public function __construct(NPM $NPM)
    {
        $this->NPM = $NPM;
    }

    /**
     * Get the error message describing the analyzer insights.
     *
     * @return string
     */
    public function errorMessage()
    {
        return "Your application has a total of {$this->vulnerabilityCount} known vulnerabilities in the application's "
            ."frontend dependencies. This can be very dangerous and you may investigate this further by running an "
            ."npm audit or a yarn audit command.";
    }

    /**
     * Execute the analyzer.
     *
     * @return void
     */
    public function handle()
    {
        $this->vulnerabilityCount = $this->NPM->countVulnerabilities();

        if ($this->vulnerabilityCount > 0) {
            $this->markFailed();
        }
    }

    /**
     * Determine whether to skip the analyzer.
     *
     * @return bool
     */
    public function skip()
    {
        // Skip the analyzer if package.json or npm/yarn does not exist.
        return empty($this->NPM->findNpmOrYarn());
    }
}
