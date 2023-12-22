<?php

namespace App\Jobs\Install;

use App\Abstracts\Job;
use App\Interfaces\Listener\ShouldUpdateAllCompanies;
use App\Models\Module\Module;
use App\Traits\Modules;
use App\Utilities\Console;
use App\Utilities\Versions;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class FinishUpdate extends Job
{
    use Modules;

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
        $this->company_id = (int) $company_id;
    }

    public function handle(): void
    {
        $this->authorize();

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

    /**
     * Determine if this action is applicable.
     */
    public function authorize(): void
    {
        if (($this->alias != 'core') && ! $this->moduleExists($this->alias)) {
            throw new \Exception("Module [{$this->alias}] not found.");
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
            Artisan::call('cache:clear');

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

            if (! Versions::shouldUpdate($class::VERSION, $this->old, $this->new)) {
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
