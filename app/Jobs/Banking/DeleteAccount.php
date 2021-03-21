<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;

class DeleteAccount extends Job
{
    protected $account;

    /**
     * Create a new job instance.
     *
     * @param  $account
     */
    public function __construct($account)
    {
        $this->account = $account;
    }

    /**
     * Execute the job.
     *
     * @return boolean|Exception
     */
    public function handle()
    {
        $this->authorize();

        \DB::transaction(function () {
            $this->account->delete();
        });

        return true;
    }

    /**
     * Determine if this action is applicable.
     *
     * @return void
     */
    public function authorize()
    {
        if ($relationships = $this->getRelationships()) {
            $message = trans('messages.warning.deleted', ['name' => $this->account->name, 'text' => implode(', ', $relationships)]);

            throw new \Exception($message);
        }
    }

    public function getRelationships()
    {
        $rels = [
            'transactions' => 'transactions',
        ];

        $relationships = $this->countRelationships($this->account, $rels);

        if ($this->account->id == setting('default.account')) {
            $relationships[] = strtolower(trans_choice('general.companies', 1));
        }

        return $relationships;
    }
}
