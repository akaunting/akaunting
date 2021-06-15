<?php

namespace App\Jobs\Install;

use App\Abstracts\Job;
use App\Utilities\Console;

class InstallModule extends Job
{
    protected $alias;

    protected $company_id;

    protected $locale;

    /**
     * Create a new job instance.
     *
     * @param  $alias
     * @param  $company_id
     * @param  $locale
     */
    public function __construct($alias, $company_id, $locale = null)
    {
        $this->alias = $alias;
        $this->company_id = $company_id;
        $this->locale = $locale ?: company($company_id)->locale;
    }

    /**
     * Execute the job.
     *
     * @return string
     */
    public function handle()
    {
        $command = "module:install {$this->alias} {$this->company_id} {$this->locale}";

        $result = Console::run($command);

        if ($result !== true) {
            $message = !empty($result) ? $result : trans('modules.errors.finish', ['module' => $this->alias]);

            throw new \Exception($message);
        }
    }
}
