<?php

namespace App\Listeners\Update\V30;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use App\Models\Banking\Transaction;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Version305 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '3.0.5';

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

        Log::channel('stdout')->info('Updating to 3.0.5 version...');

        $this->updateDatabase();

        $this->updateSettings();

        $this->updateTransfers();

        Log::channel('stdout')->info('Done!');
    }

    public function updateDatabase(): void
    {
        Log::channel('stdout')->info('Updating database...');

        Artisan::call('migrate', ['--force' => true]);

        Log::channel('stdout')->info('Database updated.');
    }

    public function updateSettings(): void
    {
        Log::channel('stdout')->info('Updating settings...');

        DB::table('settings')->where('key', 'transaction.type.income')->cursor()->each(function ($setting) {
            DB::table('settings')->where('id', $setting->id)->update([
                'value' => $setting->value . ',' . Transaction::INCOME_TRANSFER_TYPE,
            ]);
        });

        DB::table('settings')->where('key', 'transaction.type.expense')->cursor()->each(function ($setting) {
            DB::table('settings')->where('id', $setting->id)->update([
                'value' => $setting->value . ',' . Transaction::EXPENSE_TRANSFER_TYPE,
            ]);
        });

        Log::channel('stdout')->info('Settings updated.');
    }

    public function updateTransfers(): void
    {
        Log::channel('stdout')->info('Updating transfers...');

        DB::table('transfers')->cursor()->each(function ($transfer) {
            Log::channel('stdout')->info('Updating transfer: ' . $transfer->id);

            try {
                DB::table('transactions')->where('id', $transfer->income_transaction_id)->update([
                    'type' => Transaction::INCOME_TRANSFER_TYPE,
                ]);
            } catch (\Exception $e) {
                Log::channel('stdout')->error('Error updating transaction: ' . $transfer->income_transaction_id);
            }

            try {
                DB::table('transactions')->where('id', $transfer->expense_transaction_id)->update([
                    'type' => Transaction::EXPENSE_TRANSFER_TYPE,
                ]);
            } catch (\Exception $e) {
                Log::channel('stdout')->error('Error updating transaction: ' . $transfer->expense_transaction_id);
            }
        });

        Log::channel('stdout')->info('Transfers updated.');
    }
}
