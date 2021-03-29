<?php

namespace App\Listeners\Module;

use App\Events\Module\Uninstalled as Event;
use App\Jobs\Common\DeleteDashboard;
use App\Jobs\Common\DeleteReport;
use App\Models\Common\Dashboard;
use App\Models\Common\Report;
use App\Traits\Jobs;

class FinishUninstallation
{
    use Jobs;

    /**
     * Handle the event.
     *
     * @param  Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        $this->deleteDashboards($event->alias);
        $this->deleteReports($event->alias);
    }

    /**
     * Delete any dashboard created by the module.
     *
     * @param  string $alias
     * @return void
     */
    protected function deleteDashboards($alias)
    {
        Dashboard::alias($alias)->get()->each(function ($dashboard) {
            try {
                $this->dispatch(new DeleteDashboard($dashboard));
            } catch (\Exception | \Throwable $e) {
                report($e);
            }
        });
    }

    /**
     * Delete any report created by the module.
     *
     * @param  string $alias
     * @return void
     */
    protected function deleteReports($alias)
    {
        Report::alias($alias)->get()->each(function ($report) {
            try {
                $this->dispatch(new DeleteReport($report));
            } catch (\Exception | \Throwable $e) {
                report($e);
            }
        });
    }
}
