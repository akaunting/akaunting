<?php

namespace App\Console\Commands;

use App\Abstracts\Commands\Module as Command;
use App\Events\Module\Uninstalled;

class UninstallModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:uninstall {alias} {company} {locale=en-GB}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Uninstall the specified module.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->prepare();

        if (!$this->getModel()) {
            $this->info("Module [{$this->alias}] not found.");
            return;
        }

        $this->changeRuntime();

        // Delete db
        $this->model->delete();

        $this->createHistory('uninstalled');

        event(new Uninstalled($this->alias, $this->company_id));

        // Delete files
        $this->module->delete();

        $this->revertRuntime();

        $this->info("Module [{$this->alias}] uninstalled.");
    }
}
