<?php

namespace App\Console\Commands;

use App\Events\Install\UpdateCopied;
use App\Events\Install\UpdateDownloaded;
use App\Events\Install\UpdateFailed;
use App\Events\Install\UpdateUnzipped;
use App\Jobs\Install\CopyFiles;
use App\Jobs\Install\DownloadFile;
use App\Jobs\Install\FinishUpdate;
use App\Jobs\Install\UnzipFile;
use App\Traits\Jobs;
use App\Utilities\Versions;
use Illuminate\Console\Command;

class Update extends Command
{
    use Jobs;

    const CMD_SUCCESS = 0;

    const CMD_ERROR = 1;

    public $alias;

    public $company;

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
        $this->company = $this->argument('company');

        if (false === $this->new = $this->getNewVersion()) {
            $message = 'Not able to get the latest version of ' . $this->alias . '!';

            $this->error($message);

            event(new UpdateFailed($this->alias, $this->new, $this->old, 'Version', $message));

            return self::CMD_ERROR;
        }

        $this->old = $this->getOldVersion();

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
            $path = $this->dispatch(new DownloadFile($this->alias, $this->new));

            event(new UpdateDownloaded($this->alias, $this->new, $this->old));
        } catch (\Exception $e) {
            $message = $e->getMessage();

            $this->error($message);

            event(new UpdateFailed($this->alias, $this->new, $this->old, 'Download', $message));

            return false;
        }

        return $path;
    }

    public function unzip($path)
    {
        $this->info('Unzipping update...');

        try {
            $this->dispatch(new UnzipFile($this->alias, $path));

            event(new UpdateUnzipped($this->alias, $this->new, $this->old));
        } catch (\Exception $e) {
            $message = $e->getMessage();

            $this->error($message);

            event(new UpdateFailed($this->alias, $this->new, $this->old, 'Unzip', $message));

            return false;
        }

        return true;
    }

    public function copyFiles($path)
    {
        $this->info('Copying update files...');

        try {
            $this->dispatch(new CopyFiles($this->alias, $path));

            event(new UpdateCopied($this->alias, $this->new, $this->old));
        } catch (\Exception $e) {
            $message = $e->getMessage();

            $this->error($message);

            event(new UpdateFailed($this->alias, $this->new, $this->old, 'Copy Files', $message));

            return false;
        }

        return true;
    }

    public function finish()
    {
        $this->info('Finishing update...');

        try {
            $this->dispatch(new FinishUpdate($this->alias, $this->new, $this->old, $this->company));
        } catch (\Exception $e) {
            $message = $e->getMessage();

            $this->error($message);

            event(new UpdateFailed($this->alias, $this->new, $this->old, 'Finish', $message));

            return false;
        }

        return true;
    }
}
