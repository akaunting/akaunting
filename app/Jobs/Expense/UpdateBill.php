<?php

namespace App\Jobs\Expense;

use App\Events\BillUpdated;
use App\Models\Expense\Bill;
use App\Models\Expense\BillTotal;
use App\Traits\Currencies;
use App\Traits\DateTime;
use App\Traits\Uploads;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateBill
{
    use Currencies, DateTime, Dispatchable, Uploads;

    protected $bill;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $request
     */
    public function __construct($bill, $request)
    {
        $this->bill = $bill;
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return Bill
     */
    public function handle()
    {
        // Upload attachment
        if ($this->request->file('attachment')) {
            $media = $this->getMedia($this->request->file('attachment'), 'bills');

            $this->bill->attachMedia($media, 'attachment');
        }

        $taxes = [];

        $tax_total = 0;
        $sub_total = 0;
        $discount_total = 0;
        $discount = $this->request['discount'];

        if ($this->request['item']) {
            $this->deleteRelationships($this->bill, 'items');

            foreach ($this->request['item'] as $item) {
                $bill_item = dispatch(new CreateBillItem($item, $this->bill, $discount));

                // Calculate totals
                $tax_total += $bill_item->tax;
                $sub_total += $bill_item->total;

                // Set taxes
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

        $s_total = $sub_total;

        // Apply discount to total
        if ($discount) {
            $s_discount = $s_total * ($discount / 100);
            $discount_total += $s_discount;
            $s_total = $s_total - $s_discount;
        }

        $amount = $s_total + $tax_total;

        $this->request['amount'] = money($amount, $this->request['currency_code'])->getAmount();

        $this->bill->update($this->request->input());

        // Delete previous bill totals
        $this->deleteRelationships($this->bill, 'totals');

        // Add bill totals
        $this->addTotals($this->bill, $this->request, $taxes, $sub_total, $discount_total, $tax_total);

        // Recurring
        $this->bill->updateRecurring();

        // Fire the event to make it extensible
        event(new BillUpdated($this->bill));

        return $this->bill;
    }

    protected function addTotals($bill, $request, $taxes, $sub_total, $discount_total, $tax_total)
    {
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

    /**
     * Mass delete relationships with events being fired.
     *
     * @param  $model
     * @param  $relationships
     *
     * @return void
     */
    public function deleteRelationships($model, $relationships)
    {
        foreach ((array) $relationships as $relationship) {
            if (empty($model->$relationship)) {
                continue;
            }

            $items = $model->$relationship->all();

            if ($items instanceof Collection) {
                $items = $items->all();
            }

            foreach ((array) $items as $item) {
                $item->delete();
            }
        }
    }
    
    function test() {

        $taxes = [];

        $tax_total = 0;
        $sub_total = 0;
        $discount_total = 0;
        $discount = $request['discount'];

        $bill_item = [];
        $bill_item['company_id'] = $request['company_id'];
        $bill_item['bill_id'] = $bill->id;

        if ($request['item']) {
            $this->deleteRelationships($bill, 'items');

            foreach ($request['item'] as $item) {
                unset($tax_object);
                $item_sku = '';

                if (!empty($item['item_id'])) {
                    $item_object = Item::find($item['item_id']);

                    $item['name'] = $item_object->name;
                    $item_sku = $item_object->sku;
                }

                $item_tax = 0;
                $item_taxes = [];
                $bill_item_taxes = [];

                if (!empty($item['tax_id'])) {
                    foreach ($item['tax_id'] as $tax_id) {
                        $tax_object = Tax::find($tax_id);

                        $item_taxes[] = $tax_id;

                        $tax = (((double) $item['price'] * (double) $item['quantity']) / 100) * $tax_object->rate;

                        // Apply discount to tax
                        if ($discount) {
                            $tax = $tax - ($tax * ($discount / 100));
                        }

                        $bill_item_taxes[] = [
                            'company_id' => $request['company_id'],
                            'bill_id' => $bill->id,
                            'tax_id' => $tax_id,
                            'name' => $tax_object->name,
                            'amount' => $tax,
                        ];

                        $item_tax += $tax;
                    }
                }

                $bill_item['item_id'] = $item['item_id'];
                $bill_item['name'] = str_limit($item['name'], 180, '');
                $bill_item['sku'] = $item_sku;
                $bill_item['quantity'] = (double) $item['quantity'];
                $bill_item['price'] = (double) $item['price'];
                $bill_item['tax'] = $item_tax;
                $bill_item['tax_id'] = 0;//$tax_id;
                $bill_item['total'] = (double) $item['price'] * (double) $item['quantity'];

                $tax_total += $item_tax;
                $sub_total += $bill_item['total'];

                $bill_item_created = BillItem::create($bill_item);

                if ($bill_item_taxes) {
                    foreach ($bill_item_taxes as $bill_item_tax) {
                        $bill_item_tax['bill_item_id'] = $bill_item_created->id;

                        BillItemTax::create($bill_item_tax);

                        // Set taxes
                        if (isset($taxes) && array_key_exists($bill_item_tax['tax_id'], $taxes)) {
                            $taxes[$bill_item_tax['tax_id']]['amount'] += $bill_item_tax['amount'];
                        } else {
                            $taxes[$bill_item_tax['tax_id']] = [
                                'name' => $bill_item_tax['name'],
                                'amount' => $bill_item_tax['amount']
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

        $request['amount'] = money($amount, $request['currency_code'])->getAmount();

        $bill->update($request->input());

        // Upload attachment
        if ($request->file('attachment')) {
            $media = $this->getMedia($request->file('attachment'), 'bills');

            $bill->attachMedia($media, 'attachment');
        }

        // Delete previous bill totals
        $this->deleteRelationships($bill, 'totals');

        // Add bill totals
        $this->addTotals($bill, $request, $taxes, $sub_total, $discount_total, $tax_total);

        // Recurring
        $bill->updateRecurring();

        // Fire the event to make it extendible
        event(new BillUpdated($bill));
    }
}
