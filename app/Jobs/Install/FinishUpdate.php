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

    /**
     * Create a new job instance.
     *
     * @param  $alias
     * @param  $new
     * @param  $old
     */
    public function __construct($alias, $new, $old)
    {
        $this->alias = $alias;
        $this->new = $new;
        $this->old = $old;
    }

    /**
     * Execute the job.
     *
     * @return string
     */
    public function handle()
    {
        if ($this->alias == 'core') {
            $companies = [session('company_id')];
        } else {
            $companies = Module::alias($this->alias)->allCompanies()->cursor();
        }

        foreach ($companies as $company) {
            $company_id = is_object($company) ? $company->id : $company;

            $command = "update:finish {$this->alias} {$company_id} {$this->new} {$this->old}";

            if (true !== $result = Console::run($command)) {
                $message = !empty($result) ? $result : trans('modules.errors.finish', ['module' => $this->alias]);

                throw new \Exception($message);
            }
        }
    }
}
