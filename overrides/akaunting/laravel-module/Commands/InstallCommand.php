<?php

namespace Akaunting\Module\Commands;

use App\Abstracts\Commands\Module as Command;
use App\Events\Module\Installed;
use App\Models\Module\Module as Model;

class InstallCommand extends Command
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

        if ($this->getModel()) {
            $this->comment("Module [{$this->alias}] is already installed.");
            return;
        }

        $this->changeRuntime();

        // Create db
        $this->model = Model::create([
            'company_id' => $this->company_id,
            'alias' => $this->alias,
            'enabled' => '1',
        ]);

        $this->createHistory('installed');

        event(new Installed($this->alias, $this->company_id, $this->locale));

        $this->revertRuntime();

        $this->info("Module [{$this->alias}] installed!");
    }
}
