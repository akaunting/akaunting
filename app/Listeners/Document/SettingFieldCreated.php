<?php

namespace App\Listeners\Document;

use App\Events\Document\DocumentCreated as Event;
use App\Models\Common\Company;
use App\Traits\Documents;
use App\Traits\Uploads;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class SettingFieldCreated
{
    use Documents, Uploads;

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        $request = $event->request;
        $document = $event->document;

        if (!$request->has('setting')) {
            return;
        }

        $type = $request->get('type');
        $fields = $request->get('setting', []);

        foreach ($fields as $key => $value) {
            if ($key == 'company_logo') {
                if (Arr::has($value, 'dropzone')) {
                    continue;
                }

                setting()->set('company.logo', $value);

                continue;
            }

            $real_key = $type . '.' . $key;

            setting()->set($real_key, $value);
        }

        $files = $request->file('setting', []);

        if ($files) {
            $company = Company::find($document->company_id);

            foreach ($files as $key => $value) {
                // Upload attachment    
                $media = $this->getMedia($value, 'settings');

                $company->attachMedia($media, Str::snake($real_key));

                $value = $media->id;

                if ($key == 'company_logo') {
                    setting()->set('company.logo', $value);

                    continue;
                }

                $real_key = $type . '.' . $key;

                setting()->set($real_key, $value);
            }
        }

        // Save all settings
        setting()->save();
    }
}
