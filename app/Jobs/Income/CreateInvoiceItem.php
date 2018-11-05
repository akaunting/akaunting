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

        if (!empty($item_id)) {
            $item_object = Item::find($item_id);

            $this->data['name'] = $item_object->name;
            $item_sku = $item_object->sku;

            // Decrease stock (item sold)
            $item_object->quantity -= $this->data['quantity'];
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

        $item_tax = 0;
        $item_taxes = [];
        $invoice_item_taxes = [];

        if (!empty($this->data['tax_id'])) {
            foreach ((array) $this->data['tax_id'] as $tax_id) {
                $tax_object = Tax::find($tax_id);

                $item_taxes[] = $tax_id;

                $tax = (((double) $this->data['price'] * (double) $this->data['quantity']) / 100) * $tax_object->rate;

                // Apply discount to tax
                if ($this->discount) {
                    $tax = $tax - ($tax * ($this->discount / 100));
                }

                $invoice_item_taxes[] = [
                    'company_id' => $this->invoice->company_id,
                    'invoice_id' => $this->invoice->id,
                    'tax_id' => $tax_id,
                    'name' => $tax_object->name,
                    'amount' => $tax,
                ];

                $item_tax += $tax;
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
            'tax' => $item_tax,
            'tax_id' => 0, // (int) $item_taxes;
            'total' => (double) $this->data['price'] * (double) $this->data['quantity'],
        ]);

        if ($invoice_item_taxes) {
            foreach ($invoice_item_taxes as $invoice_item_tax) {
                $invoice_item_tax['invoice_item_id'] = $invoice_item->id;

                InvoiceItemTax::create($invoice_item_tax);

                // Set taxes
                if (isset($taxes) && array_key_exists($invoice_item_tax['tax_id'], $taxes)) {
                    $taxes[$invoice_item_tax['tax_id']]['amount'] += $invoice_item_tax['amount'];
                } else {
                    $taxes[$invoice_item_tax['tax_id']] = [
                        'name' => $invoice_item_tax['name'],
                        'amount' => $invoice_item_tax['amount']
                    ];
                }
            }
        }

        return $invoice_item;
    }
}