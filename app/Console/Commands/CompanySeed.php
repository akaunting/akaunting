<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CompanySeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'company:seed {company}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed for specific company';
    
    /**
     * Create a new command instance.
     *
     * @return void
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
        $class = $this->laravel->make('CompanySeeder');
        
        $seeder = $class->setContainer($this->laravel)->setCommand($this);
        
        $seeder->__invoke();
    }

}
