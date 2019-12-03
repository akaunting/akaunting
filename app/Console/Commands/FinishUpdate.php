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
    protected $signature = 'update:finish {alias} {company_id} {new} {old}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Finish the update process through CLI';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(900); // 15 minutes

        $this->info('Finishing update...');

        session(['company_id' => $this->argument('company_id')]);

        $this->call('cache:clear');

        event(new UpdateFinished($this->argument('alias'), $this->argument('new'), $this->argument('old')));
    }
}
