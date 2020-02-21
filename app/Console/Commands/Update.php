<?php

namespace App\Console\Commands;

use App\Utilities\Updater;
use App\Utilities\Versions;
use Illuminate\Console\Command;

class Update extends Command
{
    const CMD_SUCCESS = 0;

    const CMD_ERROR = 1;

    public $alias;

    public $new;

    public $old;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update {alias} {company} {new=latest}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Allows to update Akaunting and modules directly through CLI';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(3600); // 1 hour

        $this->alias = $this->argument('alias');

        if (false === $this->new = $this->getNewVersion()) {
            $this->error('Not able to get the latest version of ' . $this->alias . '!');

            return self::CMD_ERROR;
        }

        $this->old = $this->getOldVersion();

        session(['company_id' => $this->argument('company')]);
        setting()->setExtraColumns(['company_id' => $this->argument('company')]);

        if (!$path = $this->download()) {
            return self::CMD_ERROR;
        }

        if (!$this->unzip($path)) {
            return self::CMD_ERROR;
        }

        if (!$this->copyFiles($path)) {
            return self::CMD_ERROR;
        }

        if (!$this->finish()) {
            return self::CMD_ERROR;
        }

        return self::CMD_SUCCESS;
    }

    public function getNewVersion()
    {
        return ($this->argument('new') == 'latest') ? Versions::latest($this->alias) : $this->argument('new');
    }

    public function getOldVersion()
    {
        if ($this->alias == 'core') {
            return version('short');
        }

        if ($module = module($this->alias)) {
            return $module->get('version');
        }

        return '1.0.0';
    }

    public function download()
    {
        $this->info('Downloading update...');

        try {
            $path = Updater::download($this->alias, $this->new, $this->old);
        } catch (\Exception $e) {
            $this->error($e->getMessage());

            return false;
        }

        return $path;
    }

    public function unzip($path)
    {
        $this->info('Unzipping update...');

        try {
            Updater::unzip($path, $this->alias, $this->new, $this->old);
        } catch (\Exception $e) {
            $this->error($e->getMessage());

            return false;
        }

        return true;
    }

    public function copyFiles($path)
    {
        $this->info('Copying update files...');

        try {
            Updater::copyFiles($path, $this->alias, $this->new, $this->old);
        } catch (\Exception $e) {
            $this->error($e->getMessage());

            return false;
        }

        return true;
    }

    public function finish()
    {
        $this->info('Finishing update...');

        try {
            Updater::finish($this->alias, $this->new, $this->old);
        } catch (\Exception $e) {
            $this->error($e->getMessage());

            return false;
        }

        return true;
    }
}
