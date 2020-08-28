<?php

namespace Modules\OfflinePayments\Jobs;

use App\Abstracts\Job;
use App\Utilities\Modules;

class CreatePaymentMethod extends Job
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

        $payment_method = [
            'code' => 'offline-payments.' . $this->request->get('code') . '.' . (count($methods) + 1),
            'name' => $this->request->get('name'),
            'customer' => $this->request->get('customer'),
            'order' => $this->request->get('order'),
            'description' => $this->request->get('description'),
        ];

        $methods[] = $payment_method;

        setting()->set('offline-payments.methods', json_encode($methods));

        setting()->save();

        Modules::clearPaymentMethodsCache();

        return $payment_method;
    }
}
