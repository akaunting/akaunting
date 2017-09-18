<?php

namespace Modules\OfflinePayment\Providers;

use Artisan;
use Module;

use Illuminate\Support\ServiceProvider;

class OfflineServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $module = Module::get('OfflinePayment');

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
                        'order' => $offline_payment['order'],
                        'description' => $offline_payment['description']
                    );
                }

                setting()->set('offlinepayment.methods', json_encode($offlinepayment));

                setting()->forget('offline.payment.methods');

                setting()->save();
            }

            $module->delete();

            Artisan::call('cache:clear');
        }
    }
}
