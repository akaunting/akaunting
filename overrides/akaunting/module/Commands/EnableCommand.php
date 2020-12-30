<?php

namespace Akaunting\Module\Commands;

use App\Abstracts\Commands\Module as Command;
use App\Events\Module\Enabled;

class EnableCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:enable {alias} {company} {locale=en-GB}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enable the specified module.';

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

        if ($this->model->enabled) {
            $this->comment("Module [{$this->alias}] is already enabled.");
            return;
        }

        $this->changeRuntime();

        // Update db
        $this->model->enabled = true;
        $this->model->save();

        $this->createHistory('enabled');

        event(new Enabled($this->alias, $this->company_id));

        $this->revertRuntime();

        $this->info("Module [{$this->alias}] enabled.");
    }
}
