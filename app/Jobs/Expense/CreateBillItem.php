<?php

namespace App\Jobs\Expense;

use App\Models\Common\Item;
use App\Models\Expense\BillItem;
use App\Models\Expense\BillItemTax;
use App\Models\Setting\Tax;
use App\Notifications\Common\Item as ItemNotification;
use App\Notifications\Common\ItemReminder as ItemReminderNotification;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateBillItem
{
    use Dispatchable;

    protected $data;

    protected $bill;

    protected $discount;

    /**
     * Create a new job instance.
     *
     * @param  $data
     * @param  $bill
     * @param  $discount
     */
    public function __construct($data, $bill, $discount = null)
    {
        $this->data = $data;
        $this->bill = $bill;
        $this->discount = $discount;
    }

    /**
     * Execute the job.
     *
     * @return BillItem
     */
    public function handle()
    {
        $item_sku = '';

        $item_id = !empty($this->data['item_id']) ? $this->data['item_id'] : 0;
        $item_amount = (double) $this->data['price'] * (double) $this->data['quantity'];

        $item_discount_amount = $item_amount;

        // Apply discount to tax
        if ($this->discount) {
            $item_discount_amount = $item_amount - ($item_amount * ($this->discount / 100));
        }

        if (!empty($item_id)) {
            $item_object = Item::find($item_id);

            $this->data['name'] = $item_object->name;
            $item_sku = $item_object->sku;

            // Increase stock (item bought)
            $item_object->quantity += (double) $this->data['quantity'];
            $item_object->save();
        } elseif (!empty($this->data['sku'])) {
            $item_sku = $this->data['sku'];
        }

        $tax_amount = 0;
        $item_taxes = [];
        $item_tax_total = 0;

        if (!empty($this->data['tax_id'])) {
            $inclusives = $compounds = $taxes = [];

            foreach ((array) $this->data['tax_id'] as $tax_id) {
                $tax = Tax::find($tax_id);

                switch ($tax->type) {
                    case 'inclusive':
                        $inclusives[] = $tax;
                        break;
                    case 'compound':
                        $compounds[] = $tax;
                        break;
                    case 'normal':
                    default:
                        $taxes[] = $tax;

                        $tax_amount = ($item_discount_amount / 100) * $tax->rate;

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
                if ($this->discount) {
                    $item_tax_total = 0;

                    if ($taxes) {
                        foreach ($taxes as $tax) {
                            $item_tax_amount = ($item_amount / 100) * $tax->rate;

                            $item_tax_total += $item_tax_amount;
                        }
                    }

                    foreach ($inclusives as $inclusive) {
                        $item_sub_and_tax_total = $item_amount + $item_tax_total;

                        $item_tax_total = $item_sub_and_tax_total - ($item_sub_and_tax_total / (1 + ($inclusive->rate / 100)));

                        $item_sub_total = $item_sub_and_tax_total - $item_tax_total;

                        $item_taxes[] = [
                            'company_id' => $this->bill->company_id,
                            'bill_id' => $this->bill->id,
                            'tax_id'     => $inclusive->id,
                            'name'       => $inclusive->name,
                            'amount'     => $tax_amount,
                        ];

                        $item_discount_amount = $item_sub_total - ($item_sub_total * ($this->discount / 100));
                    }
                } else {
                    foreach ($inclusives as $inclusive) {
                        $item_sub_and_tax_total = $item_discount_amount + $item_tax_total;

                        $item_tax_total = $tax_amount = $item_sub_and_tax_total - ($item_sub_and_tax_total / (1 + ($inclusive->rate / 100)));

                        $item_taxes[] = [
                            'company_id' => $this->bill->company_id,
                            'bill_id' => $this->bill->id,
                            'tax_id'     => $inclusive->id,
                            'name'       => $inclusive->name,
                            'amount'     => $tax_amount,
                        ];

                        $item_amount = $item_sub_and_tax_total - $item_tax_total;
                    }
                }
            }

            if ($compounds) {
                foreach ($compounds as $compound) {
                    $tax_amount = (($item_discount_amount + $item_tax_total) / 100) * $compound->rate;

                    $item_tax_total += $tax_amount;

                    $item_taxes[] = [
                        'company_id' => $this->bill->company_id,
                        'bill_id' => $this->bill->id,
                        'tax_id' => $compound->id,
                        'name' => $compound->name,
                        'amount' => $tax_amount,
                    ];
                }
            }
        }

        $bill_item = BillItem::create([
            'company_id' => $this->bill->company_id,
            'bill_id' => $this->bill->id,
            'item_id' => $item_id,
            'name' => str_limit($this->data['name'], 180, ''),
            'sku' => $item_sku,
            'quantity' => (double) $this->data['quantity'],
            'price' => (double) $this->data['price'],
            'tax' => $item_tax_total,
            'tax_id' => 0,
            'total' => $item_amount,
        ]);

        $bill_item->item_taxes = false;
        $bill_item->inclusives = false;
        $bill_item->compounds = false;

        // set item_taxes for
        if (!empty($this->data['tax_id'])) {
            $bill_item->item_taxes = $item_taxes;
            $bill_item->inclusives = $inclusives;
            $bill_item->compounds = $compounds;
        }

        if ($item_taxes) {
            foreach ($item_taxes as $item_tax) {
                $item_tax['bill_item_id'] = $bill_item->id;

                BillItemTax::create($item_tax);

                // Set taxes
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

        return $bill_item;
    }
}
