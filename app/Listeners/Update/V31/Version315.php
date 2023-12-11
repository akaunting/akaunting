<?php

namespace App\Listeners\Update\V31;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use App\Models\Common\Widget;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Version315 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '3.1.5';

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

        Log::channel('stdout')->info('Updating to 3.1.5 version...');

        $this->clearCache();

        $this->updateWidgets();

        Log::channel('stdout')->info('Done!');
    }

    public function clearCache(): void
    {
        Log::channel('stdout')->info('Clearing cache...');

        Artisan::call('view:clear');
        Artisan::call('cache:clear');

        Log::channel('stdout')->info('Cleared cache.');
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

            if (! empty($widget_settings->raw_width)) {
                Log::channel('stdout')->info('Already new classs widget:' . $widget->id);

                continue;
            }

            unset($widget_settings->raw_width);

            if (Str::contains($widget_settings->width, 'lg:w-1/4')) {
                $widget_settings->width = 25;
            } elseif (Str::contains($widget_settings->width, 'lg:w-1/3')) {
                $widget_settings->width = 33;
            } elseif (Str::contains($widget_settings->width, 'lg:w-2/4')) {
                $widget_settings->width = 50;
            } else {
                $widget_settings->width = 100;
            }

            $widget->settings = $widget_settings;

            $widget->save();

            Log::channel('stdout')->info('Widget updated:' . $widget->id);
        }

        Log::channel('stdout')->info('Widgets updated.');
    }
}
