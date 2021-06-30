<?php

namespace App\Http\Controllers\Purchases;

use App\Abstracts\Http\Controller;
use App\Exports\Purchases\Payments as Export;
use App\Http\Requests\Banking\Transaction as Request;
use App\Http\Requests\Common\Import as ImportRequest;
use App\Imports\Purchases\Payments as Import;
use App\Jobs\Banking\CreateTransaction;
use App\Jobs\Banking\DeleteTransaction;
use App\Jobs\Banking\UpdateTransaction;
use App\Models\Banking\Account;
use App\Models\Banking\Transaction;
use App\Models\Common\Contact;
use App\Models\Setting\Category;
use App\Models\Setting\Currency;
use App\Traits\Currencies;
use App\Traits\DateTime;
use App\Traits\Transactions;
use App\Utilities\Modules;

class Payments extends Controller
{
    use Currencies, DateTime, Transactions;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $payments = Transaction::with('account', 'bill', 'category', 'contact')->expense()->isNotTransfer()->collect(['paid_at'=> 'desc']);

        return $this->response('purchases.payments.index', compact('payments'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function show(Transaction $payment)
    {
        return view('purchases.payments.show', compact('payment'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $accounts = Account::enabled()->orderBy('name')->pluck('name', 'id');

        $currencies = Currency::enabled()->orderBy('name')->pluck('name', 'code')->toArray();

        $account_currency_code = Account::where('id', setting('default.account'))->pluck('currency_code')->first();

        $currency = Currency::where('code', $account_currency_code)->first();

        $vendors = Contact::vendor()->enabled()->orderBy('name')->take(setting('default.select_limit'))->pluck('name', 'id');

        $categories = Category::expense()->enabled()->orderBy('name')->take(setting('default.select_limit'))->pluck('name', 'id');

        $payment_methods = Modules::getPaymentMethods();

        $file_type_mimes = explode(',', config('filesystems.mimes'));

        $file_types = [];

        foreach ($file_type_mimes as $mime) {
            $file_types[] = '.' . $mime;
        }

        $file_types = implode(',', $file_types);

        return view('purchases.payments.create', compact('accounts', 'currencies', 'account_currency_code', 'currency', 'vendors', 'categories', 'payment_methods', 'file_types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $response = $this->ajaxDispatch(new CreateTransaction($request));

        if ($response['success']) {
            $response['redirect'] = route('payments.show', $response['data']->id);

            $message = trans('messages.success.added', ['type' => trans_choice('general.payments', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('payments.create');

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Duplicate the specified resource.
     *
     * @param  Transaction $payment
     *
     * @return Response
     */
    public function duplicate(Transaction $payment)
    {
        $clone = $payment->duplicate();

        $message = trans('messages.success.duplicated', ['type' => trans_choice('general.payments', 1)]);

        flash($message)->success();

        return redirect()->route('payments.edit', $clone->id);
    }

    /**
     * Import the specified resource.
     *
     * @param  ImportRequest  $request
     *
     * @return Response
     */
    public function import(ImportRequest $request)
    {
        $response = $this->importExcel(new Import, $request, trans_choice('general.payments', 2));

        if ($response['success']) {
            $response['redirect'] = route('payments.index');

            flash($response['message'])->success();
        } else {
            $response['redirect'] = route('import.create', ['purchases', 'payments']);

            flash($response['message'])->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Transaction $payment
     *
     * @return Response
     */
    public function edit(Transaction $payment)
    {
        $accounts = Account::enabled()->orderBy('name')->pluck('name', 'id');

        $currencies = Currency::enabled()->orderBy('name')->pluck('name', 'code')->toArray();

        $currency = Currency::where('code', $payment->currency_code)->first();

        $vendors = Contact::vendor()->enabled()->orderBy('name')->take(setting('default.select_limit'))->pluck('name', 'id');

        if ($payment->contact && !$vendors->has($payment->contact_id)) {
            $vendors->put($payment->contact->id, $payment->contact->name);
        }

        $categories = Category::expense()->enabled()->orderBy('name')->take(setting('default.select_limit'))->pluck('name', 'id');

        if ($payment->category && !$categories->has($payment->category_id)) {
            $categories->put($payment->category->id, $payment->category->name);
        }

        $payment_methods = Modules::getPaymentMethods();

        $date_format = $this->getCompanyDateFormat();

        $file_type_mimes = explode(',', config('filesystems.mimes'));

        $file_types = [];

        foreach ($file_type_mimes as $mime) {
            $file_types[] = '.' . $mime;
        }

        $file_types = implode(',', $file_types);

        return view('purchases.payments.edit', compact('payment', 'accounts', 'currencies', 'currency', 'vendors', 'categories', 'payment_methods', 'date_format', 'file_types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Transaction $payment
     * @param  Request $request
     *
     * @return Response
     */
    public function update(Transaction $payment, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateTransaction($payment, $request));

        if ($response['success']) {
            $response['redirect'] = route('payments.show', $payment->id);

            $message = trans('messages.success.updated', ['type' => trans_choice('general.payments', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('payments.edit', $payment->id);

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Transaction $payment
     *
     * @return Response
     */
    public function destroy(Transaction $payment)
    {
        $response = $this->ajaxDispatch(new DeleteTransaction($payment));

        $response['redirect'] = route('payments.index');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => trans_choice('general.payments', 1)]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Export the specified resource.
     *
     * @return Response
     */
    public function export()
    {
        return $this->exportExcel(new Export, trans_choice('general.payments', 2));
    }

    /**
     * Download the PDF file of payment.
     *
     * @param  Transaction $payment
     *
     * @return Response
     */
    public function emailPayment(Transaction $payment)
    {
        if (empty($payment->contact->email)) {
            return redirect()->back();
        }

        // Notify the customer
        $payment->contact->notify(new Notification($payment, 'payment_new_customer', true));

        event(new \App\Events\Banking\TransactionSent($payment));

        flash(trans('documents.messages.email_sent', ['type' => trans_choice('general.payments', 1)]))->success();

        return redirect()->back();
    }

    /**
     * Print the payment.
     *
     * @param  Transaction $payment
     *
     * @return Response
     */
    public function printPayment(Transaction $payment)
    {
        event(new \App\Events\Banking\TransactionPrinting($payment));

        $view = view($payment->template_path, compact('payment'));

        return mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');
    }

    /**
     * Download the PDF file of payment.
     *
     * @param  Transaction $payment
     *
     * @return Response
     */
    public function pdfPayment(Transaction $payment)
    {
        event(new \App\Events\Banking\TransactionPrinting($payment));

        $currency_style = true;

        $view = view($payment->template_path, compact('payment', 'currency_style'))->render();
        $html = mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');

        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($html);

        //$pdf->setPaper('A4', 'portrait');

        $file_name = $this->getTransactionFileName($payment);

        return $pdf->download($file_name);
    }
}
