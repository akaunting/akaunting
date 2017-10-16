<?php

namespace App\Http\Controllers\Api\Incomes;

use App\Http\Requests\Income\InvoicePayment as Request;
use App\Models\Income\Invoice;
use App\Models\Income\InvoiceHistory;
use App\Models\Income\InvoicePayment;
use App\Models\Setting\Currency;
use App\Traits\DateTime;
use App\Transformers\Income\InvoicePayments as Transformer;
use Date;
use Dingo\Api\Routing\Helpers;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class InvoicePayments extends BaseController
{
    use DateTime, Helpers, AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Display a listing of the resource.
     *
     * @param  $invoice_id
     * @return \Dingo\Api\Http\Response
     */
    public function index($invoice_id)
    {
        $invoice_payments = InvoicePayment::where('invoice_id', $invoice_id)->get();

        return $this->response->collection($invoice_payments, new Transformer());
    }

    /**
     * Display the specified resource.
     *
     * @param  $invoice_id
     * @param  $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($invoice_id, $id)
    {
        $invoice_payment = InvoicePayment::find($id);

        return $this->response->item($invoice_payment, new Transformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $invoice_id
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function store($invoice_id, Request $request)
    {
        // Get currency object
        $currency = Currency::where('code', $request['currency_code'])->first();

        $request['currency_code'] = $currency->code;
        $request['currency_rate'] = $currency->rate;

        $request['invoice_id'] = $invoice_id;

        $invoice = Invoice::find($invoice_id);

        if ($request['currency_code'] == $invoice->currency_code) {
            if ($request['amount'] > $invoice->amount) {
                return $this->response->noContent();
            } elseif ($request['amount'] == $invoice->amount) {
                $invoice->invoice_status_code = 'paid';
            } else {
                $invoice->invoice_status_code = 'partial';
            }
        } else {
            $request_invoice = new Invoice();

            $request_invoice->amount = (float) $request['amount'];
            $request_invoice->currency_code = $currency->code;
            $request_invoice->currency_rate = $currency->rate;

            $amount = $request_invoice->getConvertedAmount();

            if ($amount > $invoice->amount) {
                return $this->response->noContent();
            } elseif ($amount == $invoice->amount) {
                $invoice->invoice_status_code = 'paid';
            } else {
                $invoice->invoice_status_code = 'partial';
            }
        }

        $invoice->save();

        $invoice_payment = InvoicePayment::create($request->input());

        $request['status_code'] = $invoice->invoice_status_code;
        $request['notify'] = 0;

        $desc_date = Date::parse($request['paid_at'])->format($this->getCompanyDateFormat());
        $desc_amount = money((float) $request['amount'], $request['currency_code'], true)->format();
        $request['description'] = $desc_date . ' ' . $desc_amount;

        InvoiceHistory::create($request->input());

        return $this->response->created(url('api/invoices/' . $invoice_id . '/payments' . $invoice_payment->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $invoice_id
     * @param  $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($invoice_id, $id)
    {
        $invoice_payment = InvoicePayment::find($id);

        $invoice_payment->delete();

        return $this->response->noContent();
    }
}
