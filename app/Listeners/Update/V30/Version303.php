<?php

namespace App\Listeners\Update\V30;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use App\Models\Common\Widget;
use App\Models\Common\Company;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Version303 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '3.0.3';

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        if ($this->skipThisUpdate($event)) {
            return;
        }

        Log::channel('stdout')->info('Starting the Akaunting 3.0.3 update...');

        $this->updateCompanies();

        Log::channel('stdout')->info('Akaunting 3.0.3 update finished.');
    }

    public function updateCompanies()
    {
        Log::channel('stdout')->info('Updating companies...');

        $company_id = company_id();

        $companies = Company::cursor();

        foreach ($companies as $company) {
            Log::channel('stdout')->info('Updating company:' . $company->id);

            $company->makeCurrent();

            $this->updateWidgets();

            Log::channel('stdout')->info('Company updated:' . $company->id);
        }

        company($company_id)->makeCurrent();

        Log::channel('stdout')->info('Companies updated.');
    }

    public function updateWidgets()
    {
        Log::channel('stdout')->info('Updating widgets...');

        $widgets = Widget::cursor();

        foreach ($widgets as $widget) {
            Log::channel('stdout')->info('Updating widget:' . $widget->id);

            $widget_settings = $widget->settings;

            if (empty($widget_settings->width)) {
                Log::channel('stdout')->info('Skip widget:' . $widget->id);

                continue;
            }

            if (Str::contains($widget_settings->width, 'w-full')) {
                Log::channel('stdout')->info('Already new classs widget:' . $widget->id);

                continue;
            }

            switch ($widget_settings->width) {
                case 'col-md-3':
                    $widget_settings->width = 'w-full lg:w-1/4 px-6';
                    break;
                case 'col-md-4':
                    $widget_settings->width = 'w-full lg:w-1/3 px-6';
                    break;
                case 'col-md-6':
                    $widget_settings->width = 'w-full lg:w-2/4 px-6';
                    break;
                case 'col-md-12':
                    $widget_settings->width = 'w-full px-6';
                    break;
            }

            $widget->settings = $widget_settings;

            $widget->save();

            Log::channel('stdout')->info('Widget updated:' . $widget->id);
        }

        Log::channel('stdout')->info('Widgets updated.');
    }
}
