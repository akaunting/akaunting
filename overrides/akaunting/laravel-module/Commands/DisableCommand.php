<?php

namespace Akaunting\Module\Commands;

use App\Abstracts\Commands\Module as Command;
use App\Events\Module\Disabled;

class DisableCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:disable {alias} {company} {locale=en-GB}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disable the specified module.';

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

        if (!$this->model->enabled) {
            $this->comment("Module [{$this->alias}] is already disabled.");
            return;
        }

        $this->changeRuntime();

        // Update db
        $this->model->enabled = false;
        $this->model->save();

        $this->createHistory('disabled');

        event(new Disabled($this->alias, $this->company_id));

        $this->revertRuntime();

        $this->info("Module [{$this->alias}] disabled.");
    }
}
