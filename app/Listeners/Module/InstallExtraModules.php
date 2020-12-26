<?php

namespace App\Listeners\Module;

use App\Events\Module\Installed as Event;
use App\Jobs\Install\DownloadModule;
use App\Jobs\Install\InstallModule;
use App\Traits\Jobs;

class InstallExtraModules
{
    use Jobs;

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        $module = module($event->alias);

        $extra_modules = $module->get('extra-modules');

        if (empty($extra_modules)) {
            return;
        }

        foreach ($extra_modules as $alias => $level) {
            // Don't install if the module is "suggested"
            if ($level != 'required') {
                continue;
            }

            try {
                $this->dispatch(new DownloadModule($alias, $event->company_id));

                $this->dispatch(new InstallModule($alias, $event->company_id, $event->locale));
            } catch (\Exception $e) {
                logger($e->getMessage());

                // Stop the propagation of event if the required module failed to install
                return false;
            }
        }
    }
}
