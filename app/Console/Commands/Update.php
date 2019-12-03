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
    protected $signature = 'update {alias} {company_id} {new=latest}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Allows to update Akaunting directly through CLI';
    
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

        $this->alias = $this->argument('alias');

        $this->new = $this->getNewVersion();

        $this->old = $this->getOldVersion();

        session(['company_id' => $this->argument('company_id')]);

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
        $new = $this->argument('new');

        if ($new == 'latest') {
            $modules = ($this->alias == 'core') ? [] : [$this->alias];

            $new = Versions::latest($modules)[$this->alias];
        }

        return $new;
    }

    public function getOldVersion()
    {
        return ($this->alias == 'core')
                ? version('short')
                : module($this->alias)->get('version');
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
