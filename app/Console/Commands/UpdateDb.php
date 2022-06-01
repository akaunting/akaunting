<?php

namespace App\Console\Commands;

use App\Events\Install\UpdateFinished;
use Illuminate\Console\Command;

class UpdateDb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:db {alias=core} {company=1} {new=3.0.0} {old=2.1.36}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Allows to update Akaunting database manually';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $alias = $this->argument('alias');
        $company_id = $this->argument('company');
        $new = $this->argument('new');
        $old = $this->argument('old');

        company($company_id)->makeCurrent();

        // Disable model cache during update
        config(['laravel-model-caching.enabled' => false]);

        event(new UpdateFinished($alias, $new, $old));
    }
}
