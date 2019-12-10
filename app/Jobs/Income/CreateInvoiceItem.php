<?php

namespace App\Jobs\Income;

use App\Models\Common\Item;
use App\Models\Income\InvoiceItem;
use App\Models\Income\InvoiceItemTax;
use App\Models\Setting\Tax;
use App\Notifications\Common\Item as ItemNotification;
use App\Notifications\Common\ItemReminder as ItemReminderNotification;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateInvoiceItem
{
    use Dispatchable;

    protected $data;

    protected $invoice;

    protected $discount;

    /**
     * Create a new job instance.
     *
     * @param  $data
     * @param  $invoice
     * @param  $discount
     */
    public function __construct($data, $invoice, $discount = null)
    {
        $this->data = $data;
        $this->invoice = $invoice;
        $this->discount = $discount;
    }

    /**
     * Execute the job.
     *
     * @return InvoiceItem
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

            // Decrease stock (item sold)
            $item_object->quantity -= (double) $this->data['quantity'];
            $item_object->save();

            if (setting('general.send_item_reminder')) {
                $item_stocks = explode(',', setting('general.schedule_item_stocks'));

                foreach ($item_stocks as $item_stock) {
                    if ($item_object->quantity == $item_stock) {
                        foreach ($item_object->company->users as $user) {
                            if (!$user->can('read-notifications')) {
                                continue;
                            }

                            $user->notify(new ItemReminderNotification($item_object));
                        }
                    }
                }
            }

            // Notify users if out of stock
            if ($item_object->quantity == 0) {
                foreach ($item_object->company->users as $user) {
                    if (!$user->can('read-notifications')) {
                        continue;
                    }

                    $user->notify(new ItemNotification($item_object));
                }
            }
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
                            'company_id' => $this->invoice->company_id,
                            'invoice_id' => $this->invoice->id,
                            'tax_id' => $tax_id,
                            'name' => $tax->name,
                            'amount' => $tax_amount,
                        ];

                        $item_tax_total += $tax_amount;
                        break;
                }
            }

            if ($inclusives) {
                $item_amount = $item_discount_amount + $item_tax_total;

                $item_base_rate = $item_amount / (1 + collect($inclusives)->sum('rate')/100);

                foreach ($inclusives as $inclusive) {
                    $item_tax_total += $tax_amount = $item_base_rate * ($inclusive->rate / 100);

                    $item_taxes[] = [
                        'company_id' => $this->invoice->company_id,
                        'invoice_id' => $this->invoice->id,
                        'tax_id' => $inclusive->id,
                        'name' => $inclusive->name,
                        'amount' => $tax_amount,
                    ];
                }

                $item_amount = ($item_amount - $item_tax_total) / (1 - $this->discount / 100);
            }

            if ($compounds) {
                foreach ($compounds as $compound) {
                    $tax_amount = (($item_discount_amount + $item_tax_total) / 100) * $compound->rate;

                    $item_tax_total += $tax_amount;

                    $item_taxes[] = [
                        'company_id' => $this->invoice->company_id,
                        'invoice_id' => $this->invoice->id,
                        'tax_id' => $compound->id,
                        'name' => $compound->name,
                        'amount' => $tax_amount,
                    ];
                }
            }
        }

        $invoice_item = InvoiceItem::create([
            'company_id' => $this->invoice->company_id,
            'invoice_id' => $this->invoice->id,
            'item_id' => $item_id,
            'name' => str_limit($this->data['name'], 180, ''),
            'sku' => $item_sku,
            'quantity' => (double) $this->data['quantity'],
            'price' => (double) $this->data['price'],
            'tax' => $item_tax_total,
            'total' => $item_amount,
        ]);

        $invoice_item->item_taxes = false;
        $invoice_item->inclusives = false;
        $invoice_item->compounds = false;

        // set item_taxes for
        if (!empty($this->data['tax_id'])) {
            $invoice_item->item_taxes = $item_taxes;
            $invoice_item->inclusives = $inclusives;
            $invoice_item->compounds = $compounds;
        }

        if ($item_taxes) {
            foreach ($item_taxes as $item_tax) {
                $item_tax['invoice_item_id'] = $invoice_item->id;

                InvoiceItemTax::create($item_tax);

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

        return $invoice_item;
    }
}
