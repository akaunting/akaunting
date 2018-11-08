<?php

namespace App\Jobs\Expense;

use App\Models\Expense\BillHistory;
use App\Models\Expense\BillPayment;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateBillPayment
{
    use Dispatchable;

    protected $request;

    protected $bill;

    /**
     * Create a new job instance.
     *
     * @param  $request
     * @param  $bill
     */
    public function __construct($request, $bill)
    {
        $this->request = $request;
        $this->bill = $bill;
    }

    /**
     * Execute the job.
     *
     * @return BillPayment
     */
    public function handle()
    {
        $bill_payment = BillPayment::create($this->request->input());

        $desc_amount = money((float) $bill_payment->amount, (string) $bill_payment->currency_code, true)->format();

        $history_data = [
            'company_id' => $bill_payment->company_id,
            'bill_id' => $bill_payment->bill_id,
            'status_code' => $this->bill->bill_status_code,
            'notify' => '0',
            'description' => $desc_amount . ' ' . trans_choice('general.payments', 1),
        ];

        BillHistory::create($history_data);

        return $bill_payment;
    }
}
