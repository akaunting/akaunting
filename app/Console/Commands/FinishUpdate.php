<?php

namespace App\Console\Commands;

use App\Events\Install\UpdateFinished;
use Illuminate\Console\Command;

class FinishUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:finish {alias} {company} {new} {old}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Finish the update process through CLI';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(3600); // 1 hour

        $this->info('Finishing update...');

        $this->call('cache:clear');

        $alias = $this->argument('alias');
        $company_id = $this->argument('company');
        $new = $this->argument('new');
        $old = $this->argument('old');

        // Check if file mirror was successful
        $version = ($alias == 'core') ? version('short') : module($alias)->get('version');
        if ($version != $new) {
            logger($alias . ' update failed:: file version > ' . $version . ' -vs- ' . 'request version > ' . $new);

            throw new \Exception(trans('modules.errors.finish', ['module' => $alias]));
        }

        $company = company($company_id);

        if (empty($company)) {
            return;
        }

        $company->makeCurrent();

        // Set locale for modules
        if (($alias != 'core') && !empty($company->locale)) {
            app()->setLocale($company->locale);
        }

        // Disable model cache during update
        config(['laravel-model-caching.enabled' => false]);

        event(new UpdateFinished($alias, $new, $old));
    }
}
