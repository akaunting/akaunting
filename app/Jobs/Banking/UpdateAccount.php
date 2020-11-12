<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Models\Banking\Account;

class UpdateAccount extends Job
{
    protected $account;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $account
     * @param  $request
     */
    public function __construct($account, $request)
    {
        $this->account = $account;
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return Account
     */
    public function handle()
    {
        $this->authorize();

        \DB::transaction(function () {
            $this->account->update($this->request->all());

            // Set default account
            if ($this->request['default_account']) {
                setting()->set('default.account', $this->account->id);
                setting()->save();
            }
        });

        return $this->account;
    }

    /**
     * Determine if this action is applicable.
     *
     * @return void
     */
    public function authorize()
    {
        $relationships = $this->getRelationships();

        if (!$this->request->get('enabled') && ($this->account->id == setting('default.account'))) {
            $relationships[] = strtolower(trans_choice('general.companies', 1));

            $message = trans('messages.warning.disabled', ['name' => $this->account->name, 'text' => implode(', ', $relationships)]);

            throw new \Exception($message);
        }

        if (!$relationships) {
            return;
        }

        if ($this->account->currency_code != $this->request->get('currency_code')) {
            $message = trans('messages.warning.disable_code', ['name' => $this->account->name, 'text' => implode(', ', $relationships)]);

            throw new \Exception($message);
        }
    }

    public function getRelationships()
    {
        $rels = [
            'transactions' => 'transactions',
        ];

        return $this->countRelationships($this->account, $rels);
    }
}
