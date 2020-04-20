<?php

namespace App\Jobs\Sale;

use App\Abstracts\Job;
use App\Models\Sale\InvoiceItem;
use App\Models\Sale\InvoiceItemTax;
use App\Models\Setting\Tax;
use Illuminate\Support\Str;

class CreateInvoiceItem extends Job
{
    protected $request;

    protected $invoice;

    /**
     * Create a new job instance.
     *
     * @param  $request
     * @param  $invoice
     */
    public function __construct($request, $invoice)
    {
        $this->request = $request;
        $this->invoice = $invoice;
    }

    /**
     * Execute the job.
     *
     * @return InvoiceItem
     */
    public function handle()
    {
        $item_id = !empty($this->request['item_id']) ? $this->request['item_id'] : 0;
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
                            'company_id' => $this->invoice->company_id,
                            'invoice_id' => $this->invoice->id,
                            'tax_id' => $tax_id,
                            'name' => $tax->name,
                            'amount' => $tax_amount,
                        ];

                        $item_tax_total += $tax_amount;

                        break;
                    default:
                        $tax_amount = ($item_discounted_amount / 100) * $tax->rate;

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
                $item_amount = $item_discounted_amount + $item_tax_total;

                $item_base_rate = $item_amount / (1 + collect($inclusives)->sum('rate') / 100);

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

                $item_amount = ($item_amount - $item_tax_total) / (1 - $discount / 100);
            }

            if ($compounds) {
                foreach ($compounds as $compound) {
                    $tax_amount = (($item_discounted_amount + $item_tax_total) / 100) * $compound->rate;

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
            'name' => Str::limit($this->request['name'], 180, ''),
            'quantity' => (double) $this->request['quantity'],
            'price' => (double) $this->request['price'],
            'tax' => $item_tax_total,
            'discount_rate' => !empty($this->request['discount']) ? $this->request['discount'] : 0,
            'total' => $item_amount,
        ]);

        $invoice_item->item_taxes = false;
        $invoice_item->inclusives = false;
        $invoice_item->compounds = false;

        if (!empty($item_taxes)) {
            $invoice_item->item_taxes = $item_taxes;
            $invoice_item->inclusives = $inclusives;
            $invoice_item->compounds = $compounds;

            foreach ($item_taxes as $item_tax) {
                $item_tax['invoice_item_id'] = $invoice_item->id;

                InvoiceItemTax::create($item_tax);
            }
        }

        return $invoice_item;
    }
}
