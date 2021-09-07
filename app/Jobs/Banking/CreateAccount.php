<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\HasSource;
use App\Interfaces\Job\ShouldCreate;
use App\Models\Banking\Account;

class CreateAccount extends Job implements HasOwner, HasSource, ShouldCreate
{
    public function handle(): Account
    {
        \DB::transaction(function () {
            $this->model = Account::create($this->request->all());

            // Set default account
            if ($this->request['default_account']) {
                setting()->set('default.account', $this->model->id);
                setting()->save();
            }
        });

        return $this->model;
    }
}
