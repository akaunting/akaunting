<?php

namespace App\Jobs\Sale;

use App\Abstracts\Job;
use App\Events\Sale\InvoiceCreated;
use App\Events\Sale\InvoiceCreating;
use App\Models\Sale\Invoice;
use App\Models\Sale\InvoiceTotal;
use App\Traits\Currencies;
use App\Traits\DateTime;

class CreateInvoice extends Job
{
    use Currencies, DateTime;

    protected $request;

    protected $invoice;

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
     * @return Invoice
     */
    public function handle()
    {
        if (empty($this->request['amount'])) {
            $this->request['amount'] = 0;
        }

        event(new InvoiceCreating($this->request));

        $this->invoice = Invoice::create($this->request->all());

        // Upload attachment
        if ($this->request->file('attachment')) {
            $media = $this->getMedia($this->request->file('attachment'), 'invoices');

            $this->invoice->attachMedia($media, 'attachment');
        }

        $this->createItemsAndTotals();

        $this->invoice->update($this->request->all());

        $this->invoice->createRecurring();

        event(new InvoiceCreated($this->invoice));

        return $this->invoice;
    }

    protected function createItemsAndTotals()
    {
        // Create items
        list($sub_total, $discount_amount_total, $taxes) = $this->createItems();

        $sort_order = 1;

        // Add sub total
        InvoiceTotal::create([
            'company_id' => $this->invoice->company_id,
            'invoice_id' => $this->invoice->id,
            'code' => 'sub_total',
            'name' => 'invoices.sub_total',
            'amount' => $sub_total,
            'sort_order' => $sort_order,
        ]);

        $this->request['amount'] += $sub_total;

        $sort_order++;

        // Add discount
        if ($discount_amount_total > 0) {
            InvoiceTotal::create([
                'company_id' => $this->invoice->company_id,
                'invoice_id' => $this->invoice->id,
                'code' => 'item_discount',
                'name' => 'invoices.item_discount',
                'amount' => $discount_amount_total,
                'sort_order' => $sort_order,
            ]);

            $this->request['amount'] -= $discount_amount_total;

            $sort_order++;
        }

        if (!empty($this->request['discount'])) {
            $discount_total = ($sub_total - $discount_amount_total) * ($this->request['discount'] / 100);

            InvoiceTotal::create([
                'company_id' => $this->invoice->company_id,
                'invoice_id' => $this->invoice->id,
                'code' => 'discount',
                'name' => 'invoices.discount',
                'amount' => $discount_total,
                'sort_order' => $sort_order,
            ]);

            $this->request['amount'] -= $discount_total;

            $sort_order++;
        }

        // Add taxes
        if (!empty($taxes)) {
            foreach ($taxes as $tax) {
                InvoiceTotal::create([
                    'company_id' => $this->invoice->company_id,
                    'invoice_id' => $this->invoice->id,
                    'code' => 'tax',
                    'name' => $tax['name'],
                    'amount' => $tax['amount'],
                    'sort_order' => $sort_order,
                ]);

                $this->request['amount'] += $tax['amount'];

                $sort_order++;
            }
        }

        // Add extra totals, i.e. shipping fee
        if (!empty($this->request['totals'])) {
            foreach ($this->request['totals'] as $total) {
                $total['company_id'] = $this->invoice->company_id;
                $total['invoice_id'] = $this->invoice->id;
                $total['sort_order'] = $sort_order;

                if (empty($total['code'])) {
                    $total['code'] = 'extra';
                }

                InvoiceTotal::create($total);

                if (empty($total['operator']) || ($total['operator'] == 'addition')) {
                    $this->request['amount'] += $total['amount'];
                } else {
                    // subtraction
                    $this->request['amount'] -= $total['amount'];
                }

                $sort_order++;
            }
        }

        // Add total
        InvoiceTotal::create([
            'company_id' => $this->invoice->company_id,
            'invoice_id' => $this->invoice->id,
            'code' => 'total',
            'name' => 'invoices.total',
            'amount' => $this->request['amount'],
            'sort_order' => $sort_order,
        ]);
    }

    protected function createItems()
    {
        $sub_total = $discount_amount = $discount_amount_total = 0;

        $taxes = [];

        if (empty($this->request['items'])) {
            return [$sub_total, $discount_amount_total, $taxes];
        }

        foreach ((array) $this->request['items'] as $item) {
            $item['global_discount'] = 0;

            if (!empty($this->request['discount'])) {
                $item['global_discount'] = $this->request['discount'];
            }

            $invoice_item = $this->dispatch(new CreateInvoiceItem($item, $this->invoice));

            $item_amount = (double) $item['price'] * (double) $item['quantity'];

            $discount_amount = 0;

            if (!empty($item['discount'])) {
                $discount_amount = ($item_amount * ($item['discount'] / 100));
            }

            // Calculate totals
            $sub_total += $invoice_item->total + $discount_amount;

            $discount_amount_total += $discount_amount;

            if (!$invoice_item->item_taxes) {
                continue;
            }

            // Set taxes
            foreach ((array) $invoice_item->item_taxes as $item_tax) {
                if (array_key_exists($item_tax['tax_id'], $taxes)) {
                    $taxes[$item_tax['tax_id']]['amount'] += $item_tax['amount'];
                } else {
                    $taxes[$item_tax['tax_id']] = [
                        'name' => $item_tax['name'],
                        'amount' => $item_tax['amount']
                    ];
                }
            }
        }

        return [$sub_total, $discount_amount_total, $taxes];
    }
}
