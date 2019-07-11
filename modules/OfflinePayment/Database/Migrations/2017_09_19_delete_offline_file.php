<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class OfflineFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $module = Module::get('Offline');

        if (!empty($module) && version_compare($module->get('version'), '1.0.0') == 0) {
            $offline_payments = json_decode(setting('offline.payment.methods'), true);

            if (!empty($offline_payments)) {
                $offlinepayment = array();

                foreach ($offline_payments as $offline_payment) {
                    $code = explode('.', $offline_payment['code']);

                    $offline_payment['code'] = $code[1];

                    $offlinepayment[] = array(
                        'code' => 'offlinepayment.' . $code[1] . '.' . $code[2],
                        'name' => $offline_payment['name'],
                        'customer' => 0,
                        'order' => $offline_payment['order'],
                        'description' => $offline_payment['description']
                    );
                }

                //$company_id = $this->command->argument('company');

                // Set the active company settings
                setting()->setExtraColumns(['company_id' => 1]);

                setting()->set('offlinepayment.methods', json_encode($offlinepayment));

                setting()->forget('offline.payment.methods');

                setting()->save();
            }

            $module->delete();

            Artisan::call('cache:clear');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}