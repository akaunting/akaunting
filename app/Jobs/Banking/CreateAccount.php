<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Events\Banking\AccountCreating;
use App\Events\Banking\AccountCreated;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\HasSource;
use App\Interfaces\Job\ShouldCreate;
use App\Models\Banking\Account;

class CreateAccount extends Job implements HasOwner, HasSource, ShouldCreate
{
    public function handle(): Account
    {
        event(new AccountCreating($this->request));

        \DB::transaction(function () {
            $this->model = Account::create($this->request->all());

            // Set default account
            if ($this->request['default_account']) {
                setting()->set('default.account', $this->model->id);
                setting()->save();
            }
        });

        event(new AccountCreated($this->model, $this->request));

        return $this->model;
    }
}
