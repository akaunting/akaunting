<?php

namespace App\Listeners\Updates;

use App\Events\UpdateFinished;
use App\Models\Setting\Currency;

class Version116 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '1.1.6';

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(UpdateFinished $event)
    {
        // Check if should listen
        if (!$this->check($event)) {
            return;
        }

        $migrations = [
            '\App\Models\Auth\User'             => 'picture',
            '\App\Models\Item\Item'             => 'picture',
            '\App\Models\Expense\Bill'          => 'attachment',
            '\App\Models\Expense\BillPayment'   => 'attachment',
            '\App\Models\Expense\Payment'       => 'attachment',
            '\App\Models\Income\Invoice'        => 'attachment',
            '\App\Models\Income\InvoicePayment' => 'attachment',
            '\App\Models\Income\Revenue'        => 'attachment',
        ];

        foreach ($migrations as $model => $name) {
            if ($model != '\App\Models\Auth\User') {
                $items = $model::where('company_id', '<>', '0')->get();
            } else {
                $items = $model::all();
            }

            foreach ($items as $item) {
                if ($item->$name) {
                    $path = explode('uploads/', $item->$name);

                    $media = MediaUploader::importPath(config('mediable.default_disk'), $path[1]);

                    $item->attachMedia($media, $name);
                }
            }
        }
    }
}
