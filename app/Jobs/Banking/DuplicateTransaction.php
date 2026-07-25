<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Events\Banking\TransactionCreated;
use App\Events\Banking\TransactionDuplicating;
use App\Models\Banking\Transaction;

class DuplicateTransaction extends Job
{
    protected $clone;

    public function __construct(Transaction $model)
    {
        $this->model = $model;

        parent::__construct($model);
    }

    public function handle(): Transaction
    {
        event(new TransactionDuplicating($this->model));

        \DB::transaction(function () {
            $this->clone = $this->model->duplicate();
        });

        event(new TransactionCreated($this->clone, request()));

        return $this->clone;
    }
}
