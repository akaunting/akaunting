<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class CompanySeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'company:seed {company} {--class= : with Fully Qualified Name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run one or all seeds for a specific company';

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle()
    {
        $class_name = $this->input->getOption('class') ?? 'Database\Seeds\Company';

        $class = $this->laravel->make($class_name);

        $class->setContainer($this->laravel)->setCommand($this)->__invoke();
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['class', null, InputOption::VALUE_OPTIONAL, 'The class name of the root seeder', 'Database\Seeds\Company'],
        ];
    }
}
