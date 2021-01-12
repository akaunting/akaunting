<?php

namespace App\Listeners\Document;

use App\Events\Document\DocumentUpdated as Event;
use App\Traits\Documents;

class SettingFieldUpdated
{
    use Documents;

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        $request = $event->request;

        if (!$request->has('setting')) {
            return;
        }

        $type = $request->get('type');
        $fields = $request->get('setting', []);

        foreach ($fields as $key => $value) {
            if ($key == 'company_logo') {
                setting()->set('company.logo', $value);

                continue;
            }

            $real_key = $type . '.' . $key;

            setting()->set($real_key, $value);
        }

        // Save all settings
        setting()->save();
    }
}
