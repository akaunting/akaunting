<?php

namespace App\Console\Commands;

use App\Jobs\Install\CopyFiles;
use App\Jobs\Install\DownloadFile;
use App\Jobs\Install\UnzipFile;
use App\Traits\Jobs;
use App\Utilities\Versions;
use Illuminate\Console\Command;

class DownloadModule extends Command
{
    use Jobs;

    const CMD_SUCCESS = 0;

    const CMD_ERROR = 1;

    public $alias;

    public $company;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:download {alias} {company}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download the specified module.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(3600); // 1 hour

        $this->alias = $this->argument('alias');
        $this->company = $this->argument('company');

        company($this->company)->makeCurrent();

        if (!$path = $this->download()) {
            return self::CMD_ERROR;
        }

        if (!$this->unzip($path)) {
            return self::CMD_ERROR;
        }

        if (!$this->copyFiles($path)) {
            return self::CMD_ERROR;
        }

        $this->info("Module [{$this->alias}] downloaded!");

        return self::CMD_SUCCESS;
    }

    public function download()
    {
        $this->info('Downloading module...');

        try {
            $path = $this->dispatch(new DownloadFile($this->alias, $this->getVersion()));
        } catch (\Exception $e) {
            $this->error($e->getMessage());

            return false;
        }

        return $path;
    }

    public function unzip($path)
    {
        $this->info('Unzipping module...');

        try {
            $this->dispatch(new UnzipFile($this->alias, $path));
        } catch (\Exception $e) {
            $this->error($e->getMessage());

            return false;
        }

        return true;
    }

    public function copyFiles($path)
    {
        $this->info('Copying module files...');

        try {
            $this->dispatch(new CopyFiles($this->alias, $path));

            event(new \App\Events\Module\Copied($this->alias, $this->company));
        } catch (\Exception $e) {
            $this->error($e->getMessage());

            return false;
        }

        return true;
    }

    protected function getVersion()
    {
        $version = Versions::latest($this->alias);

        if (empty($version)) {
            $current = '1.0.0';

            $url = 'apps/' . $this->alias . '/version/' . $current . '/' . version('short');

            $version = Versions::getLatestVersion($url, $current);
        }

        return $version;
    }
}
