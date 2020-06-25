<?php

namespace Modules\OfflinePayments\Jobs;

use App\Abstracts\Job;
use App\Utilities\Modules;

class DeletePaymentMethod extends Job
{
    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $request
     */
    public function __construct($request)
    {
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return array
     */
    public function handle()
    {
        $methods = json_decode(setting('offline-payments.methods'), true);

        $payment_method = [];

        $code = $this->request->get('code');

        foreach ($methods as $key => $method) {
            if ($method['code'] != $code) {
                continue;
            }

            $payment_method = $methods[$key];

            unset($methods[$key]);
        }

        setting()->set('offline-payments.methods', json_encode($methods));

        setting()->save();

        Modules::clearPaymentMethodsCache();

        return $payment_method;
    }
}
