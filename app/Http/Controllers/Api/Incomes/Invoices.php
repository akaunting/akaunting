<?php

namespace App\Http\Controllers\Api\Incomes;

use App\Events\InvoiceCreated;
use App\Events\InvoiceUpdated;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Income\Invoice as Request;
use App\Http\Transformers\Income\Invoice as Transformer;
use App\Models\Income\Invoice;
use App\Models\Income\InvoiceHistory;
use App\Models\Income\InvoiceItem;
use App\Models\Income\InvoicePayment;
use App\Models\Income\InvoiceStatus;
use App\Models\Item\Item;
use App\Models\Setting\Tax;
use Dingo\Api\Routing\Helpers;

class Invoices extends ApiController
{
    use Helpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::with(['customer', 'status', 'items', 'payments', 'histories'])->collect();

        return $this->response->paginator($invoices, new Transformer());
    }

    /**
     * Display the specified resource.
     *
     * @param  Invoice  $invoice
     * @return \Dingo\Api\Http\Response
     */
    public function show(Invoice $invoice)
    {
        return $this->response->item($invoice, new Transformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(Request $request)
    {
        $invoice = Invoice::create($request->all());

        $invoice_item = array();
        $invoice_item['company_id'] = $request['company_id'];
        $invoice_item['invoice_id'] = $invoice->id;

        if ($request['item']) {
            foreach ($request['item'] as $item) {
                $item_sku = '';

                if (!empty($item['item_id'])) {
                    $item_object = Item::find($item['item_id']);

                    $item_sku = $item_object->sku;
                }

                $tax = $tax_id = 0;

                if (!empty($item['tax_id'])) {
                    $tax_object = Tax::find($item['tax_id']);

                    $tax_id = $item['tax_id'];

                    $tax = (($item['price'] * $item['quantity']) / 100) * $tax_object->rate;
                } elseif (!empty($item['tax'])) {
                    $tax = $item['tax'];
                }

                $invoice_item['item_id'] = $item['item_id'];
                $invoice_item['name'] = $item['name'];
                $invoice_item['sku'] = $item_sku;
                $invoice_item['quantity'] = $item['quantity'];
                $invoice_item['price'] = $item['price'];
                $invoice_item['tax'] = $tax;
                $invoice_item['tax_id'] = $tax_id;
                $invoice_item['total'] = ($item['price'] + $invoice_item['tax']) * $item['quantity'];

                $request['amount'] += $invoice_item['total'];

                InvoiceItem::create($invoice_item);
            }
        }

        $invoice->update($request->input());

        $request['invoice_id'] = $invoice->id;
        $request['status_code'] = $request['invoice_status_code'];
        $request['notify'] = 0;
        $request['description'] = trans('messages.success.added', ['type' => $request['invoice_number']]);

        InvoiceHistory::create($request->input());

        // Fire the event to make it extendible
        event(new InvoiceCreated($invoice));

        return $this->response->created(url('api/invoices/'.$invoice->id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $invoice
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function update(Invoice $invoice, Request $request)
    {
        $invoice_item = array();
        $invoice_item['company_id'] = $request['company_id'];
        $invoice_item['invoice_id'] = $invoice->id;

        if ($request['item']) {
            InvoiceItem::where('invoice_id', $invoice->id)->delete();

            foreach ($request['item'] as $item) {
                $item_sku = '';

                if (!empty($item['item_id'])) {
                    $item_object = Item::find($item['item_id']);

                    $item_sku = $item_object->sku;
                }

                $tax = $tax_id = 0;

                if (!empty($item['tax_id'])) {
                    $tax_object = Tax::find($item['tax_id']);

                    $tax_id = $item['tax_id'];

                    $tax = (($item['price'] * $item['quantity']) / 100) * $tax_object->rate;
                } elseif (!empty($item['tax'])) {
                    $tax = $item['tax'];
                }

                $invoice_item['item_id'] = $item['item_id'];
                $invoice_item['name'] = $item['name'];
                $invoice_item['sku'] = $item_sku;
                $invoice_item['quantity'] = $item['quantity'];
                $invoice_item['price'] = $item['price'];
                $invoice_item['tax'] = $tax;
                $invoice_item['tax_id'] = $tax_id;
                $invoice_item['total'] = ($item['price'] + $invoice_item['tax']) * $item['quantity'];

                $request['amount'] += $invoice_item['total'];

                InvoiceItem::create($invoice_item);
            }
        }

        $invoice->update($request->input());

        // Fire the event to make it extendible
        event(new InvoiceUpdated($invoice));

        return $this->response->item($invoice->fresh(), new Transformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Invoice  $invoice
     * @return \Dingo\Api\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        InvoiceItem::where('invoice_id', $invoice->id)->delete();
        InvoicePayment::where('invoice_id', $invoice->id)->delete();
        InvoiceHistory::where('invoice_id', $invoice->id)->delete();

        return $this->response->noContent();
    }
}
