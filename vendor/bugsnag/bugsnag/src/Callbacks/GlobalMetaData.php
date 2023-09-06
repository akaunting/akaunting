<?php

namespace Bugsnag\Callbacks;

use Bugsnag\Configuration;
use Bugsnag\Report;

class GlobalMetaData
{
    /**
     * The config instance.
     *
     * @var \Bugsnag\Configuration
     */
    protected $config;

    /**
     * Create a new global meta data callback instance.
     *
     * @param \Bugsnag\Configuration $config the configuration instance
     *
     * @return void
     */
    public function __construct(Configuration $config)
    {
        $this->config = $config;
    }

    /**
     * Execute the global meta data callback.
     *
     * @param \Bugsnag\Report $report the bugsnag report instance
     *
     * @return void
     */
    public function __invoke(Report $report)
    {
        if ($data = $this->config->getMetaData()) {
            $report->setMetaData($data);
        }
    }
}
