<?php

namespace Enlightn\SecurityChecker;

class SecurityChecker
{
    /**
     * @var string
     */
    private $tempDir;

    public function __construct($tempDir = null)
    {
        $this->tempDir = $tempDir;
    }

    /**
     * @param string $composerLockPath
     * @param false $excludeDev
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function check($composerLockPath, $excludeDev = false)
    {
        $parser = new AdvisoryParser((new AdvisoryFetcher($this->tempDir))->fetchAdvisories());

        $dependencies = (new Composer)->getDependencies($composerLockPath, $excludeDev);

        return (new AdvisoryAnalyzer($parser->getAdvisories()))->analyzeDependencies($dependencies);
    }
}
