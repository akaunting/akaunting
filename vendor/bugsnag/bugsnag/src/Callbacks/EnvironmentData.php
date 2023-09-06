<?php

namespace Bugsnag\Callbacks;

use Bugsnag\Report;

class EnvironmentData
{
    /**
     * Execute the environment data callback.
     *
     * @param \Bugsnag\Report $report the bugsnag report instance
     *
     * @return void
     */
    public function __invoke(Report $report)
    {
        if (!empty($_ENV)) {
            $report->setMetaData(['Environment' => $_ENV]);
        }
    }
}
