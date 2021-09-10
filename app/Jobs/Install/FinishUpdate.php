<?php

namespace App\Jobs\Install;

use App\Abstracts\Job;
use App\Interfaces\Listener\ShouldUpdateAllCompanies;
use App\Models\Module\Module;
use App\Utilities\Console;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;

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

    public function handle(): void
    {
        $companies = $this->getCompanies();

        foreach ($companies as $company) {
            $company_id = is_object($company) ? $company->company_id : $company;

            $command = "update:finish {$this->alias} {$company_id} {$this->new} {$this->old}";

            if (true !== $result = Console::run($command)) {
                $message = !empty($result) ? $result : trans('modules.errors.finish', ['module' => $this->alias]);

                throw new \Exception($message);
            }
        }
    }

    public function getCompanies()
    {
        if ($this->alias == 'core') {
            return [$this->company_id];
        }

        return $this->getCompaniesOfModule();
    }

    public function getCompaniesOfModule()
    {
        $listener = $this->getListenerTypeOfModule();

        if ($listener == 'none') {
            return [];
        }

        if ($listener == 'one') {
            return [$this->company_id];
        }

        // Get company list from modules table
        return Module::alias($this->alias)->allCompanies()->cursor();
    }

    public function getListenerTypeOfModule(): string
    {
        $listener = 'none';

        $module = module($this->alias);
        $filesystem = app(Filesystem::class);

        $updates_folder = $module->getPath() . '/Listeners/Update';

        if (! File::isDirectory($updates_folder)) {
            return $listener;
        }

        foreach ($filesystem->allFiles($updates_folder) as $file) {
            $path = str_replace([$module->getPath(), '.php'], '', $file->getPathname());

            // Thank you PSR-4
            $class = '\Modules\\' . $module->getStudlyName() . str_replace('/', '\\', $path);

            // Skip if listener is same or lower than old version
            if (version_compare($class::VERSION, $this->old, '=<')) {
                continue;
            }

            if (app($class) instanceof ShouldUpdateAllCompanies) {
                // Going to update data
                $listener = 'all';

                break;
            } else {
                // Going to update tables/files
                $listener = 'one';
            }
        }

        return $listener;
    }
}
