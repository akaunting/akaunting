<?php

namespace App\Jobs\Install;

use App\Abstracts\Job;
use App\Utilities\Console;

class DownloadModule extends Job
{
    protected $alias;

    protected $company_id;

    /**
     * Create a new job instance.
     *
     * @param  $alias
     * @param  $company_id
     */
    public function __construct($alias, $company_id)
    {
        $this->alias = $alias;
        $this->company_id = $company_id;
    }

    /**
     * Execute the job.
     *
     * @return string
     */
    public function handle()
    {
        $command = "module:download {$this->alias} {$this->company_id}";

        $result = Console::run($command);

        if ($result !== true) {
            throw new \Exception($result);
        }
    }
}
