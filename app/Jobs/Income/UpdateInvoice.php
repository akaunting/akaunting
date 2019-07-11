<?php

namespace App\Jobs\Income;

use App\Events\InvoiceUpdated;
use App\Models\Common\Item;
use App\Models\Income\Invoice;
use App\Models\Income\InvoiceTotal;
use App\Traits\Currencies;
use App\Traits\DateTime;
use App\Traits\Incomes;
use App\Traits\Uploads;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateInvoice
{
    use Currencies, DateTime, Dispatchable, Incomes, Uploads;

    protected $invoice;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $request
     */
    public function __construct($invoice, $request)
    {
        $this->invoice = $invoice;
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return Invoice
     */
    public function handle()
    {
        // Upload attachment
        if ($this->request->file('attachment')) {
            $media = $this->getMedia($this->request->file('attachment'), 'invoices');

            $this->invoice->attachMedia($media, 'attachment');
        }

        $taxes = [];

        $tax_total = 0;
        $sub_total = 0;
        $discount_total = 0;
        $discount = $this->request['discount'];

        if ($this->request['item']) {
            $items = $this->invoice->items;

            if ($items) {
                foreach ($items as $item) {
                    if (empty($item->item_id)) {
                        continue;
                    }

                    $item_object = Item::find($item->item_id);

                    // Increase stock
                    $item_object->quantity += (double) $item->quantity;
                    $item_object->save();
                }
            }

            $this->deleteRelationships($this->invoice, 'items');

            foreach ($this->request['item'] as $item) {
                $invoice_item = dispatch(new CreateInvoiceItem($item, $this->invoice, $discount));

                // Calculate totals
                $tax_total += $invoice_item->tax;
                $sub_total += $invoice_item->total;

                // Set taxes
                if ($invoice_item->item_taxes) {
                    foreach ($invoice_item->item_taxes as $item_tax) {
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

        $invoice_paid = $this->invoice->paid;

        unset($this->invoice->reconciled);

        if (($invoice_paid) && $this->request['amount'] > $invoice_paid) {
            $this->request['invoice_status_code'] = 'partial';
        }

        $this->invoice->update($this->request->input());

        // Delete previous invoice totals
        $this->deleteRelationships($this->invoice, 'totals');

        // Add invoice totals
        $this->addTotals($this->invoice, $this->request, $taxes, $sub_total, $discount_total, $tax_total);

        // Recurring
        $this->invoice->updateRecurring();

        // Fire the event to make it extensible
        event(new InvoiceUpdated($this->invoice));

        return $this->invoice;
    }

    protected function addTotals($invoice, $request, $taxes, $sub_total, $discount_total, $tax_total)
    {
        // Check if totals are in request, i.e. api
        if (!empty($request['totals'])) {
            $sort_order = 1;

            foreach ($request['totals'] as $total) {
                $total['invoice_id'] = $invoice->id;

                if (empty($total['sort_order'])) {
                    $total['sort_order'] = $sort_order;
                }

                InvoiceTotal::create($total);

                $sort_order++;
            }

            return;
        }

        $sort_order = 1;

        // Added invoice sub total
        InvoiceTotal::create([
            'company_id' => $request['company_id'],
            'invoice_id' => $invoice->id,
            'code' => 'sub_total',
            'name' => 'invoices.sub_total',
            'amount' => $sub_total,
            'sort_order' => $sort_order,
        ]);

        $sort_order++;

        // Added invoice discount
        if ($discount_total) {
            InvoiceTotal::create([
                'company_id' => $request['company_id'],
                'invoice_id' => $invoice->id,
                'code' => 'discount',
                'name' => 'invoices.discount',
                'amount' => $discount_total,
                'sort_order' => $sort_order,
            ]);

            // This is for total
            $sub_total = $sub_total - $discount_total;

            $sort_order++;
        }

        // Added invoice taxes
        if (isset($taxes)) {
            foreach ($taxes as $tax) {
                InvoiceTotal::create([
                    'company_id' => $request['company_id'],
                    'invoice_id' => $invoice->id,
                    'code' => 'tax',
                    'name' => $tax['name'],
                    'amount' => $tax['amount'],
                    'sort_order' => $sort_order,
                ]);

                $sort_order++;
            }
        }

        // Added invoice total
        InvoiceTotal::create([
            'company_id' => $request['company_id'],
            'invoice_id' => $invoice->id,
            'code' => 'total',
            'name' => 'invoices.total',
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
}
