<?php

namespace Akaunting\Module\Commands;

use App\Abstracts\Commands\Module;
use App\Models\Module\Module as Model;

class InstallCommand extends Module
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:install {alias} {company} {locale=en-GB}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the specified module.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->prepare();

        $this->changeRuntime();

        // Create db
        $this->model = Model::create([
            'company_id' => $this->company_id,
            'alias' => $this->alias,
            'enabled' => '1',
        ]);

        $this->createHistory('installed');

        event(new \App\Events\Module\Installed($this->alias, $this->company_id));

        $this->revertRuntime();

        $this->info('Module installed!');
    }
}
