<?php

namespace App\Http\Controllers\Api\Incomes;

use App\Events\InvoiceUpdated;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Income\Invoice as Request;
use App\Jobs\Income\CreateInvoice;
use App\Models\Income\Invoice;
use App\Models\Income\InvoiceHistory;
use App\Models\Income\InvoiceItem;
use App\Models\Income\InvoicePayment;
use App\Models\Income\InvoiceTotal;
use App\Models\Common\Item;
use App\Models\Setting\Tax;
use App\Traits\Incomes;
use App\Transformers\Income\Invoice as Transformer;
use Dingo\Api\Routing\Helpers;

class Invoices extends ApiController
{
    use Helpers, Incomes;

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
     * @param  $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        // Check if we're querying by id or number
        if (is_numeric($id)) {
            $invoice = Invoice::find($id);
        } else {
            $invoice = Invoice::where('invoice_number', $id)->first();
        }

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
        if (empty($request['amount'])) {
            $request['amount'] = 0;
        }

        $invoice = dispatch(new CreateInvoice($request));

        return $this->response->created(url('api/invoices/' . $invoice->id));
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
        $taxes = [];
        $tax_total = 0;
        $sub_total = 0;

        $invoice_item = array();
        $invoice_item['company_id'] = $request['company_id'];
        $invoice_item['invoice_id'] = $invoice->id;

        if ($request['item']) {
            InvoiceItem::where('invoice_id', $invoice->id)->delete();

            foreach ($request['item'] as $item) {
                $item_id = 0;
                $item_sku = '';

                if (!empty($item['item_id'])) {
                    $item_object = Item::find($item['item_id']);

                    $item_id = $item['item_id'];

                    $item['name'] = $item_object->name;
                    $item_sku = $item_object->sku;
                } elseif (!empty($item['sku'])) {
                    $item_sku = $item['sku'];
                }

                $tax = $tax_id = 0;

                if (!empty($item['tax_id'])) {
                    $tax_object = Tax::find($item['tax_id']);

                    $tax_id = $item['tax_id'];

                    $tax = (($item['price'] * $item['quantity']) / 100) * $tax_object->rate;
                } elseif (!empty($item['tax'])) {
                    $tax = $item['tax'];
                }

                $invoice_item['item_id'] = $item_id;
                $invoice_item['name'] = str_limit($item['name'], 180, '');
                $invoice_item['sku'] = $item_sku;
                $invoice_item['quantity'] = $item['quantity'];
                $invoice_item['price'] = $item['price'];
                $invoice_item['tax'] = $tax;
                $invoice_item['tax_id'] = $tax_id;
                $invoice_item['total'] = $item['price'] * $item['quantity'];

                $request['amount'] += $invoice_item['total'];

                InvoiceItem::create($invoice_item);

                if (isset($tax_object)) {
                    if (array_key_exists($tax_object->id, $taxes)) {
                        $taxes[$tax_object->id]['amount'] += $tax;
                    } else {
                        $taxes[$tax_object->id] = [
                            'name' => $tax_object->name,
                            'amount' => $tax
                        ];
                    }
                }

                $tax_total += $tax;
                $sub_total += $invoice_item['total'];

                unset($item_object);
                unset($tax_object);
            }
        }

        if (empty($request['amount'])) {
            $request['amount'] = $sub_total + $tax_total;
        }

        $invoice->update($request->input());

        // Delete previous invoice totals
        InvoiceTotal::where('invoice_id', $invoice->id)->delete();

        $this->addTotals($invoice, $request, $taxes, $sub_total, $tax_total);

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
        $this->deleteRelationships($invoice, ['items', 'histories', 'payments', 'recurring', 'totals']);
        $invoice->delete();

        return $this->response->noContent();
    }

    protected function addTotals($invoice, $request, $taxes, $sub_total, $tax_total) {
        // Add invoice total taxes
        if ($request['totals']) {
            $sort_order = 1;

            foreach ($request['totals'] as $total) {
                if (!empty($total['sort_order'])) {
                    $sort_order = $total['sort_order'];
                }

                $invoice_total = [
                    'company_id' => $request['company_id'],
                    'invoice_id' => $invoice->id,
                    'code' => $total['code'],
                    'name' => $total['name'],
                    'amount' => $total['amount'],
                    'sort_order' => $sort_order,
                ];

                InvoiceTotal::create($invoice_total);

                if (empty($total['sort_order'])) {
                    $sort_order++;
                }
            }
        } else {
            // Added invoice total sub total
            $invoice_sub_total = [
                'company_id' => $request['company_id'],
                'invoice_id' => $invoice->id,
                'code' => 'sub_total',
                'name' => 'invoices.sub_total',
                'amount' => $sub_total,
                'sort_order' => 1,
            ];

            InvoiceTotal::create($invoice_sub_total);

            $sort_order = 2;

            // Added invoice total taxes
            if ($taxes) {
                foreach ($taxes as $tax) {
                    $invoice_tax_total = [
                        'company_id' => $request['company_id'],
                        'invoice_id' => $invoice->id,
                        'code' => 'tax',
                        'name' => $tax['name'],
                        'amount' => $tax['amount'],
                        'sort_order' => $sort_order,
                    ];

                    InvoiceTotal::create($invoice_tax_total);

                    $sort_order++;
                }
            }

            // Added invoice total total
            $invoice_total = [
                'company_id' => $request['company_id'],
                'invoice_id' => $invoice->id,
                'code' => 'total',
                'name' => 'invoices.total',
                'amount' => $sub_total + $tax_total,
                'sort_order' => $sort_order,
            ];

            InvoiceTotal::create($invoice_total);
        }
    }
}
