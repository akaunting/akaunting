<?php

namespace App\Listeners\Common;

use Illuminate\Console\Events\CommandStarting as Event;
use Illuminate\Console\Scheduling\Schedule;

class SkipScheduleInReadOnlyMode
{
    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        if (! config('read-only.enabled')) {
            return;
        }

        //$event->task->skip(true);

        $schedule = app(Schedule::class);

        foreach ($schedule->events() as $task) {
            $task->skip(true);
        }
    }
}
