<?php

namespace App\Listeners\Module;

use App\Events\Module\Uninstalled as Event;
use App\Exceptions\Common\LastDashboard;
use App\Jobs\Common\DeleteDashboard;
use App\Jobs\Common\DeleteReport;
use App\Jobs\Common\DeleteWidget;
use App\Jobs\Setting\DeleteEmailTemplate;
use App\Models\Common\Dashboard;
use App\Models\Common\Report;
use App\Models\Common\Widget;
use App\Models\Setting\EmailTemplate;
use App\Traits\Jobs;
use Throwable;

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
        $this->deleteWidgets($event->alias);
        $this->deleteEmailTemplates($event->alias);
        $this->deleteReports($event->alias);
        $this->deleteFavorites($event->alias);
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
            } catch (Throwable $e) {
                if ($e instanceof LastDashboard) {
                    return;
                }

                report($e);
            }
        });
    }

    /**
     * Delete any widget created by the module.
     *
     * @param  string $alias
     * @return void
     */
    protected function deleteWidgets($alias)
    {
        Widget::alias($alias)->get()->each(function ($widget) {
            try {
                $this->dispatch(new DeleteWidget($widget));
            } catch (Throwable $e) {
                report($e);
            }
        });
    }

    /**
     * Delete any email template created by the module.
     *
     * @param  string $alias
     * @return void
     */
    protected function deleteEmailTemplates($alias)
    {
        EmailTemplate::moduleAlias($alias)->get()->each(function ($template) {
            try {
                $this->dispatch(new DeleteEmailTemplate($template));
            } catch (Throwable $e) {
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
            } catch (Throwable $e) {
                report($e);
            }
        });
    }

    /**
     * Delete any favorite created by the module.
     *
     * @param  string $alias
     * @return void
     */
    protected function deleteFavorites($alias)
    {
        $favorites = setting('favorites.menu', []);

        if (empty($favorites)) {
            return;
        }

        try {
            foreach ($favorites as $user_id => $user_favorites) {
                $user_favorites = json_decode($user_favorites, true);
    
                foreach ($user_favorites as $key => $favorite) {
                    $route = $favorite['route'];
    
                    if (is_array($route)) {
                        $route = $route[0];
                    }
    
                    if (str_contains($route, $alias)) {
                        unset($user_favorites[$key]);
                    }
                }
    
                setting()->set('favorites.menu.' . $user_id, json_encode($user_favorites));
                setting()->save();
            }
        } catch (Throwable $e) {
            report($e);
        }
    }
}
