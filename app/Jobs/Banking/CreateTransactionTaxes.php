<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\HasSource;
use App\Interfaces\Job\ShouldCreate;
use App\Models\Banking\Transaction;
use App\Models\Banking\TransactionTax;
use App\Models\Setting\Tax;

class CreateTransactionTaxes extends Job implements HasOwner, HasSource, ShouldCreate
{
    protected $transaction;

    protected $request;

    public function __construct(Transaction $transaction, $request)
    {
        $this->transaction = $transaction;
        $this->request = $request;

        parent::__construct($transaction, $request);
    }

    /**
     * Execute the job.
     *
     * @return mixed
     * @todo type hint after upgrading to PHP 8
     */
    public function handle()
    {
        if (empty($this->request['tax_ids'])) {
            return false;
        }

        \DB::transaction(function () {
            $transaction_taxes = $this->getTaxesCalculated();

            foreach ($transaction_taxes as $transaction_tax) {
                TransactionTax::create($transaction_tax);
            }
        });

        return $this->transaction->taxes;
    }

    public function getTaxesCalculated()
    {
        $tax_total = 0;
        $transaction_taxes = [];

        $transaction_amount = (double) $this->request['amount'];

        $trx_params = [
            'company_id'        => $this->transaction->company_id,
            'type'              => $this->transaction->type,
            'transaction_id'    => $this->transaction->id,
            'created_from'      => $this->request['created_from'],
            'created_by'        => $this->request['created_by'],
        ];

        // New variables by tax type & tax sorting
        foreach ((array) $this->request['tax_ids'] as $tax_id) {
            $tax = Tax::find($tax_id);

            // If tax not found, skip
            if (! $tax) {
                continue;
            }

            ${$tax->type . 's'}[] = $tax;
        }

        if (isset($inclusives)) {
            foreach ($inclusives as $inclusive) {
                $tax_amount = $transaction_amount - ($transaction_amount / (1 + $inclusive->rate / 100));

                $transaction_taxes[] = $trx_params + [
                    'tax_id' => $inclusive->id,
                    'name' => $inclusive->name,
                    'amount' => $tax_amount,
                ];

                $tax_total += $tax_amount;
            }
        }

        if (isset($fixeds)) {
            foreach ($fixeds as $tax) {
                $tax_amount = $tax->rate * (double) 1;

                $transaction_taxes[] = $trx_params + [
                    'tax_id' => $tax->id,
                    'name' => $tax->name,
                    'amount' => $tax_amount,
                ];

                $tax_total += $tax_amount;
            }
        }

        if (isset($normals)) {
            foreach ($normals as $tax) {
                $tax_amount = $transaction_amount * ($tax->rate / 100);

                $transaction_taxes[] = $trx_params + [
                    'tax_id' => $tax->id,
                    'name' => $tax->name,
                    'amount' => $tax_amount,
                ];

                $tax_total += $tax_amount;
            }
        }

        if (isset($withholdings)) {
            foreach ($withholdings as $tax) {
                $tax_amount = -($transaction_amount * ($tax->rate / 100));

                $transaction_taxes[] = $trx_params + [
                    'tax_id' => $tax->id,
                    'name' => $tax->name,
                    'amount' => $tax_amount,
                ];

                $tax_total += $tax_amount;
            }
        }

        if (isset($compounds)) {
            foreach ($compounds as $compound) {
                $tax_amount = ($transaction_amount / 100) * $compound->rate;

                $transaction_taxes[] = $trx_params + [
                    'tax_id' => $compound->id,
                    'name' => $compound->name,
                    'amount' => $tax_amount,
                ];

                $tax_total += $tax_amount;
            }
        }

        return $transaction_taxes;
    }
}
