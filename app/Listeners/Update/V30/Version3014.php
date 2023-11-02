<?php

namespace App\Listeners\Update\V30;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use App\Traits\Categories;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Version3014 extends Listener
{
    use Categories;

    const ALIAS = 'core';

    const VERSION = '3.0.14';

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

        Log::channel('stdout')->info('Updating to 3.0.14 version...');

        DB::transaction(function () {
            $types = $this->getTypesByAllowedTranslations();

            foreach ($types as $type => $translations) {
                DB::table('categories')->whereIn('type', $translations)->update(['type' => $type]);
            }
        });

        Log::channel('stdout')->info('Done!');
    }

    protected function getTypesByAllowedTranslations(): array
    {
        $types = $this->getCategoryTypes(false);
        $lang_codes = array_keys(language()->allowed());

        foreach ($types as $type => $trans_name) {
            $translations_for_type = [];

            foreach ($lang_codes as $lang_code) {
                $translation = trans_choice($trans_name, 1, locale: $lang_code);

                if ($translation === $trans_name) {
                    continue;
                }

                $translations_for_type[] = $translation;
            }

            $types[$type] = $translations_for_type;
        }

        /**
         * Example:     en-GB      es-ES
         * 'income' => ['Income', 'Ingresos']
         */
        return $types;
    }
}
