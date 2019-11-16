<?php

namespace App\Listeners\Updates\V13;

use App\Events\UpdateFinished;
use App\Listeners\Updates\Listener;
use App\Models\Common\Company;
use App\Utilities\Installer;
use Artisan;

class Version1313 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '1.3.13';

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(UpdateFinished $event)
    {
        // Check if should listen
        if (!$this->check($event)) {
            return;
        }

        $schedule_time = '09:00';

        // Get default locale if only 1 company
        if (Company::all()->count() == 1) {
            $schedule_time = setting('general.schedule_time', '09:00');
        }

        // Set default locale
        Installer::updateEnv(['APP_SCHEDULE_TIME' => '"' . $schedule_time . '"']);

        // Update database
        Artisan::call('migrate', ['--force' => true]);
    }
}
