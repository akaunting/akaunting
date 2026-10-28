<?php

namespace App\Listeners\Update\V33;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Version331 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '3.3.1';

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

        Log::channel('stdout')->info('Updating to 3.3.1 version...');

        $this->updateMenuFavorites();

        $this->clearCache();

        Log::channel('stdout')->info('Done!');
    }

    /**
     * Until 3.3.1 the menu stored the title and url of a favorite escaped twice, once by Blade for the
     * page's favorite slot attribute and again by the Livewire tag, so "Profit & Loss" showed as
     * "Profit &amp; Loss". Undo both escapes in the stored JSON of every company; a decoded quote
     * stays escaped for JSON.
     */
    public function updateMenuFavorites(): void
    {
        Log::channel('stdout')->info('Updating menu favorites...');

        $entities = [
            '&amp;quot;' => '\"',
            '&amp;#039;' => "'",
            '&amp;lt;' => '<',
            '&amp;gt;' => '>',
            '&amp;amp;' => '&',
        ];

        $value = DB::getQueryGrammar()->wrap('value');

        foreach ($entities as $search => $replace) {
            $value = 'REPLACE(' . $value . ', ' . DB::escape($search) . ', ' . DB::escape($replace) . ')';
        }

        $count = DB::table('settings')
            ->where('key', 'like', 'favorites.menu.%')
            ->update(['value' => DB::raw($value)]);

        Log::channel('stdout')->info("Menu favorites updated. Updated rows: {$count}.");
    }

    public function clearCache(): void
    {
        Log::channel('stdout')->info('Clearing cache...');

        Artisan::call('cache:clear');

        Log::channel('stdout')->info('Cleared cache.');
    }
}
