<?php

namespace App\Jobs\Purchase;

use App\Abstracts\Job;
use App\Models\Purchase\BillItem;
use App\Models\Purchase\BillItemTax;
use App\Models\Setting\Tax;
use Illuminate\Support\Str;

class CreateBillItem extends Job
{
    protected $bill;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $bill
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
     * @return BillItem
     */
    public function handle()
    {
        $item_id = !empty($this->request['item_id']) ? $this->request['item_id'] : 0;
        $precision = config('money.' . $this->bill->currency_code . '.precision');

        $item_amount = (double) $this->request['price'] * (double) $this->request['quantity'];

        $discount = 0;
        $item_discounted_amount = $item_amount;

        // Apply line discount to amount
        if (!empty($this->request['discount'])) {
            $discount += $this->request['discount'];

            $item_discounted_amount = $item_amount -= ($item_amount * ($this->request['discount'] / 100));
        }

        // Apply global discount to amount
        if (!empty($this->request['global_discount'])) {
            $discount += $this->request['global_discount'];

            $item_discounted_amount = $item_amount - ($item_amount * ($this->request['global_discount'] / 100));
        }

        $tax_amount = 0;
        $item_taxes = [];
        $item_tax_total = 0;

        if (!empty($this->request['tax_id'])) {
            $inclusives = $compounds = [];

            foreach ((array) $this->request['tax_id'] as $tax_id) {
                $tax = Tax::find($tax_id);

                switch ($tax->type) {
                    case 'inclusive':
                        $inclusives[] = $tax;

                        break;
                    case 'compound':
                        $compounds[] = $tax;

                        break;
                    case 'fixed':
                        $tax_amount = $tax->rate * (double) $this->request['quantity'];

                        $item_taxes[] = [
                            'company_id' => $this->bill->company_id,
                            'bill_id' => $this->bill->id,
                            'tax_id' => $tax_id,
                            'name' => $tax->name,
                            'amount' => $tax_amount,
                        ];

                        $item_tax_total += $tax_amount;

                        break;
                    case 'withholding':
                        $tax_amount = 0 - $item_discounted_amount * ($tax->rate / 100);

                        $item_taxes[] = [
                            'company_id' => $this->bill->company_id,
                            'bill_id' => $this->bill->id,
                            'tax_id' => $tax_id,
                            'name' => $tax->name,
                            'amount' => $tax_amount,
                        ];

                        $item_tax_total += $tax_amount;

                        break;
                    default:
                        $tax_amount = $item_discounted_amount * ($tax->rate / 100);

                        $item_taxes[] = [
                            'company_id' => $this->bill->company_id,
                            'bill_id' => $this->bill->id,
                            'tax_id' => $tax_id,
                            'name' => $tax->name,
                            'amount' => $tax_amount,
                        ];

                        $item_tax_total += $tax_amount;

                        break;
                }
            }

            if ($inclusives) {
                $item_amount = $item_discounted_amount + $item_tax_total;

                $item_base_rate = $item_amount / (1 + collect($inclusives)->sum('rate') / 100);

                foreach ($inclusives as $inclusive) {
                    $tax_amount = $item_base_rate * ($inclusive->rate / 100);

                    $item_taxes[] = [
                        'company_id' => $this->bill->company_id,
                        'bill_id' => $this->bill->id,
                        'tax_id' => $inclusive->id,
                        'name' => $inclusive->name,
                        'amount' => $tax_amount,
                    ];

                    $item_tax_total += $tax_amount;
                }

                $item_amount = ($item_amount - $item_tax_total) / (1 - $discount / 100);
            }

            if ($compounds) {
                foreach ($compounds as $compound) {
                    $tax_amount = (($item_discounted_amount + $item_tax_total) / 100) * $compound->rate;

                    $item_taxes[] = [
                        'company_id' => $this->bill->company_id,
                        'bill_id' => $this->bill->id,
                        'tax_id' => $compound->id,
                        'name' => $compound->name,
                        'amount' => $tax_amount,
                    ];

                    $item_tax_total += $tax_amount;
                }
            }
        }

        $bill_item = BillItem::create([
            'company_id' => $this->bill->company_id,
            'bill_id' => $this->bill->id,
            'item_id' => $item_id,
            'name' => Str::limit($this->request['name'], 180, ''),
            'quantity' => (double) $this->request['quantity'],
            'price' => round($this->request['price'], $precision),
            'tax' => round($item_tax_total, $precision),
            'discount_rate' => !empty($this->request['discount']) ? $this->request['discount'] : 0,
            'total' => round($item_amount, $precision),
        ]);

        $bill_item->item_taxes = false;
        $bill_item->inclusives = false;
        $bill_item->compounds = false;

        if (!empty($item_taxes)) {
            $bill_item->item_taxes = $item_taxes;
            $bill_item->inclusives = $inclusives;
            $bill_item->compounds = $compounds;

            foreach ($item_taxes as $item_tax) {
                $item_tax['bill_item_id'] = $bill_item->id;
                $item_tax['amount'] = round(abs($item_tax['amount']), $precision);

                BillItemTax::create($item_tax);
            }
        }

        return $bill_item;
    }
}
