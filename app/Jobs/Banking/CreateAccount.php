<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Models\Banking\Account;

class CreateAccount extends Job
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
     * @return Account
     */
    public function handle()
    {
        $account = Account::create($this->request->all());

        // Set default account
        if ($this->request['default_account']) {
            setting()->set('default.account', $account->id);
            setting()->save();
        }

        return $account;
    }
}
