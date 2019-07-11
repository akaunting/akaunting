<?php

namespace App\Jobs\Expense;

use App\Events\BillCreated;
use App\Models\Expense\Bill;
use App\Models\Expense\BillHistory;
use App\Models\Expense\BillTotal;
use App\Traits\Currencies;
use App\Traits\DateTime;
use App\Traits\Uploads;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateBill
{
    use Currencies, DateTime, Dispatchable, Uploads;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $request
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return Invoice
     */
    public function handle()
    {
        $bill = Bill::create($this->request->input());

        // Upload attachment
        if ($this->request->file('attachment')) {
            $media = $this->getMedia($this->request->file('attachment'), 'bills');

            $bill->attachMedia($media, 'attachment');
        }

        $taxes = [];

        $tax_total = 0;
        $sub_total = 0;
        $discount_total = 0;
        $discount = $this->request['discount'];

        if ($this->request['item']) {
            foreach ($this->request['item'] as $item) {
                $bill_item = dispatch(new CreateBillItem($item, $bill, $discount));

                // Calculate totals
                $tax_total += $bill_item->tax;
                $sub_total += $bill_item->total;

                // Set taxes
                if ($bill_item->item_taxes) {
                    foreach ($bill_item->item_taxes as $item_tax) {
                        if (isset($taxes) && array_key_exists($item_tax['tax_id'], $taxes)) {
                            $taxes[$item_tax['tax_id']]['amount'] += $item_tax['amount'];
                        } else {
                            $taxes[$item_tax['tax_id']] = [
                                'name' => $item_tax['name'],
                                'amount' => $item_tax['amount']
                            ];
                        }
                    }
                }
            }
        }

        $s_total = $sub_total;

        // Apply discount to total
        if ($discount) {
            $s_discount = $s_total * ($discount / 100);
            $discount_total += $s_discount;
            $s_total = $s_total - $s_discount;
        }

        $amount = $s_total + $tax_total;

        $this->request['amount'] = money($amount, $this->request['currency_code'])->getAmount();

        $bill->update($this->request->input());

        // Add bill totals
        $this->addTotals($bill, $this->request, $taxes, $sub_total, $discount_total, $tax_total);

        // Add bill history
        BillHistory::create([
            'company_id' => session('company_id'),
            'bill_id' => $bill->id,
            'status_code' => 'draft',
            'notify' => 0,
            'description' => trans('messages.success.added', ['type' => $bill->bill_number]),
        ]);

        // Recurring
        $bill->createRecurring();

        // Fire the event to make it extendible
        event(new BillCreated($bill));

        return $bill;
    }

    protected function addTotals($bill, $request, $taxes, $sub_total, $discount_total, $tax_total)
    {
        // Check if totals are in request, i.e. api
        if (!empty($request['totals'])) {
            $sort_order = 1;

            foreach ($request['totals'] as $total) {
                $total['bill_id'] = $bill->id;

                if (empty($total['sort_order'])) {
                    $total['sort_order'] = $sort_order;
                }

                BillTotal::create($total);

                $sort_order++;
            }

            return;
        }

        $sort_order = 1;

        // Added bill sub total
        BillTotal::create([
            'company_id' => $request['company_id'],
            'bill_id' => $bill->id,
            'code' => 'sub_total',
            'name' => 'bills.sub_total',
            'amount' => $sub_total,
            'sort_order' => $sort_order,
        ]);

        $sort_order++;

        // Added bill discount
        if ($discount_total) {
            BillTotal::create([
                'company_id' => $request['company_id'],
                'bill_id' => $bill->id,
                'code' => 'discount',
                'name' => 'bills.discount',
                'amount' => $discount_total,
                'sort_order' => $sort_order,
            ]);

            // This is for total
            $sub_total = $sub_total - $discount_total;

            $sort_order++;
        }

        // Added bill taxes
        if (isset($taxes)) {
            foreach ($taxes as $tax) {
                BillTotal::create([
                    'company_id' => $request['company_id'],
                    'bill_id' => $bill->id,
                    'code' => 'tax',
                    'name' => $tax['name'],
                    'amount' => $tax['amount'],
                    'sort_order' => $sort_order,
                ]);

                $sort_order++;
            }
        }

        // Added bill total
        BillTotal::create([
            'company_id' => $request['company_id'],
            'bill_id' => $bill->id,
            'code' => 'total',
            'name' => 'bills.total',
            'amount' => $sub_total + $tax_total,
            'sort_order' => $sort_order,
        ]);
    }
}
