<?php

namespace App\Jobs\Install;

use App\Abstracts\Job;
use App\Models\Module\Module;
use App\Utilities\Console;

class FinishUpdate extends Job
{
    protected $alias;

    protected $new;

    protected $old;

    protected $company_id;

    /**
     * Create a new job instance.
     *
     * @param  $alias
     * @param  $new
     * @param  $old
     * @param  $company_id
     */
    public function __construct($alias, $new, $old, $company_id)
    {
        $this->alias = $alias;
        $this->new = $new;
        $this->old = $old;
        $this->company_id = $company_id;
    }

    /**
     * Execute the job.
     *
     * @return string
     */
    public function handle()
    {
        if ($this->alias == 'core') {
            $companies = [$this->company_id];
        } else {
            // Get company list from modules table
            $companies = Module::alias($this->alias)->allCompanies()->cursor();
        }

        foreach ($companies as $company) {
            $company_id = is_object($company) ? $company->company_id : $company;

            $command = "update:finish {$this->alias} {$company_id} {$this->new} {$this->old}";

            if (true !== $result = Console::run($command)) {
                $message = !empty($result) ? $result : trans('modules.errors.finish', ['module' => $this->alias]);

                throw new \Exception($message);
            }
        }
    }
}
