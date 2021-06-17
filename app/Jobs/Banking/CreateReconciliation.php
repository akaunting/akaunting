<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Models\Banking\Reconciliation;
use App\Models\Banking\Transaction;

class CreateReconciliation extends Job
{
    protected $reconciliation;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $request
     */
    public function __construct($request)
    {
        $this->request = $this->getRequestInstance($request);
        $this->request->merge(['created_by' => user_id()]);
    }

    /**
     * Execute the job.
     *
     * @return Reconciliation
     */
    public function handle()
    {
        \DB::transaction(function () {
            $reconcile = (int) $this->request->get('reconcile');
            $transactions = $this->request->get('transactions');

            $this->request->merge(['reconciled' => $reconcile]);

            $this->reconciliation = Reconciliation::create($this->request->all());

            if ($reconcile && $transactions) {
                foreach ($transactions as $key => $value) {
                    if (empty($value)) {
                        continue;
                    }

                    $t = explode('_', $key);

                    $transaction = Transaction::find($t[1]);
                    $transaction->reconciled = 1;
                    $transaction->save();
                }
            }
        });

        return $this->reconciliation;
    }
}
