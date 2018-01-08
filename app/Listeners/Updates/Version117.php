<?php

namespace App\Listeners\Updates;

use App\Events\UpdateFinished;
use App\Models\Setting\Currency;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class Version117 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '1.1.7';

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

        Schema::create('media', function (Blueprint $table) {
            $table->increments('id');
            $table->string('disk', 32);
            $table->string('directory');
            $table->string('filename');
            $table->string('extension', 32);
            $table->string('mime_type', 128);
            $table->string('aggregate_type', 32);
            $table->integer('size')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['disk', 'directory']);
            $table->unique(['disk', 'directory', 'filename', 'extension']);
            $table->index('aggregate_type');
        });

        Schema::create('mediables', function (Blueprint $table) {
            $table->integer('media_id')->unsigned();
            $table->string('mediable_type');
            $table->integer('mediable_id')->unsigned();
            $table->string('tag');
            $table->integer('order')->unsigned();

            $table->primary(['media_id', 'mediable_type', 'mediable_id', 'tag']);
            $table->index(['mediable_id', 'mediable_type']);
            $table->index('tag');
            $table->index('order');
            $table->foreign('media_id')->references('id')->on('media')->onDelete('cascade');
        });

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

        // Update database
        Artisan::call('migrate', ['--force' => true]);
    }
}
