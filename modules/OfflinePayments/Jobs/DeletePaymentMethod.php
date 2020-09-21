<?php

namespace Modules\OfflinePayments\Jobs;

use App\Abstracts\Job;
use App\Utilities\Modules;
use App\Models\Banking\Transaction;

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
        $this->authorize();

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

    /**
     * Determine if this action is applicable.
     *
     * @return void
     */
    public function authorize()
    {
        if ($relationships = $this->getRelationships()) {
            $message = trans('messages.warning.deleted', ['name' => $this->request->get('code'), 'text' => implode(', ', $relationships)]);

            throw new \Exception($message);
        }
    }

    public function getRelationships()
    {
        $counter = [];

        if (!$c = Transaction::where('payment_method', $this->request->get('code'))->get()->count()) {
            return [];
        }

        $counter[] = $c . ' ' . strtolower(trans_choice('general.transactions', ($c > 1) ? 2 : 1));

        return $counter;
    }
}
