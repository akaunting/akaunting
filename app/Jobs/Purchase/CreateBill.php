<?php

namespace App\Jobs\Purchase;

use App\Abstracts\Job;
use App\Events\Purchase\BillCreated;
use App\Events\Purchase\BillCreating;
use App\Jobs\Purchase\CreateBillItemsAndTotals;
use App\Models\Purchase\Bill;

class CreateBill extends Job
{
    protected $bill;

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
     * @return Bill
     */
    public function handle()
    {
        if (empty($this->request['amount'])) {
            $this->request['amount'] = 0;
        }

        event(new BillCreating($this->request));

        \DB::transaction(function () {
            $this->bill = Bill::create($this->request->all());

            // Upload attachment
            if ($this->request->file('attachment')) {
                $media = $this->getMedia($this->request->file('attachment'), 'bills');

                $this->bill->attachMedia($media, 'attachment');
            }

            $this->dispatch(new CreateBillItemsAndTotals($this->bill, $this->request));

            $this->bill->update($this->request->all());

            $this->bill->createRecurring();
        });

        event(new BillCreated($this->bill));

        return $this->bill;
    }
}
