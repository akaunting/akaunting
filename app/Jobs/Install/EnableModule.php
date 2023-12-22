<?php

namespace App\Jobs\Install;

use App\Abstracts\Job;
use App\Traits\Modules;
use App\Utilities\Console;

class EnableModule extends Job
{
    use Modules;

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
        $this->company_id = (int) $company_id;
        $this->locale = $locale ?: company($company_id)->locale ?: config('setting.fallback.default.locale');
    }

    /**
     * Execute the job.
     *
     * @return string
     */
    public function handle()
    {
        $this->authorize();

        $command = "module:enable {$this->alias} {$this->company_id} {$this->locale}";

        $result = Console::run($command);

        if ($result !== true) {
            throw new \Exception($result);
        }
    }

    /**
     * Determine if this action is applicable.
     */
    public function authorize(): void
    {
        if (! $this->moduleExists($this->alias)) {
            throw new \Exception("Module [{$this->alias}] not found.");
        }

        if (! in_array($this->locale, config('language.allowed'))) {
            throw new \Exception("Unknown locale: {$this->locale}");
        }
    }
}
