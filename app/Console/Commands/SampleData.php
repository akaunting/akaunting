<?php

namespace App\Console\Commands;

use Database\Seeds\SampleData as SampleDataSeeder;
use Illuminate\Console\Command;

class SampleData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sample-data:seed {--count=100 : total records for each item}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed for sample data';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $class = $this->laravel->make(SampleDataSeeder::class);

        $seeder = $class->setContainer($this->laravel)->setCommand($this);

        $seeder->__invoke();
    }
}
