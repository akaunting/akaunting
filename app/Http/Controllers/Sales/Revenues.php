<?php

namespace App\Http\Controllers\Sales;

use App\Abstracts\Http\Controller;
use App\Exports\Sales\Revenues as Export;
use App\Http\Requests\Banking\Transaction as Request;
use App\Http\Requests\Common\Import as ImportRequest;
use App\Imports\Sales\Revenues as Import;
use App\Jobs\Banking\CreateTransaction;
use App\Jobs\Banking\DeleteTransaction;
use App\Jobs\Banking\UpdateTransaction;
use App\Models\Banking\Account;
use App\Models\Banking\Transaction;
use App\Models\Common\Contact;
use App\Models\Setting\Category;
use App\Models\Setting\Currency;
use App\Notifications\Sale\Revenue as Notification;
use App\Traits\Currencies;
use App\Traits\DateTime;
use App\Traits\Transactions;
use App\Utilities\Modules;

class Revenues extends Controller
{
    use Currencies, DateTime, Transactions;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $revenues = Transaction::with('account', 'category', 'contact', 'invoice')->income()->isNotTransfer()->collect(['paid_at'=> 'desc']);

        return $this->response('sales.revenues.index', compact('revenues'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function show(Transaction $revenue)
    {
        return view('sales.revenues.show', compact('revenue'));
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

        $customers = Contact::customer()->enabled()->orderBy('name')->take(setting('default.select_limit'))->pluck('name', 'id');

        $categories = Category::income()->enabled()->orderBy('name')->take(setting('default.select_limit'))->pluck('name', 'id');

        $payment_methods = Modules::getPaymentMethods();

        $file_type_mimes = explode(',', config('filesystems.mimes'));

        $file_types = [];

        foreach ($file_type_mimes as $mime) {
            $file_types[] = '.' . $mime;
        }

        $file_types = implode(',', $file_types);

        return view('sales.revenues.create', compact('accounts', 'currencies', 'account_currency_code', 'currency', 'customers', 'categories', 'payment_methods', 'file_types'));
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
            $response['redirect'] = route('revenues.show', $response['data']->id);

            $message = trans('messages.success.added', ['type' => trans_choice('general.revenues', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('revenues.create');

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Duplicate the specified resource.
     *
     * @param  Transaction  $revenue
     *
     * @return Response
     */
    public function duplicate(Transaction $revenue)
    {
        $clone = $revenue->duplicate();

        $message = trans('messages.success.duplicated', ['type' => trans_choice('general.revenues', 1)]);

        flash($message)->success();

        return redirect()->route('revenues.edit', $clone->id);
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
        $response = $this->importExcel(new Import, $request, trans_choice('general.revenues', 2));

        if ($response['success']) {
            $response['redirect'] = route('revenues.index');

            flash($response['message'])->success();
        } else {
            $response['redirect'] = route('import.create', ['sales', 'revenues']);

            flash($response['message'])->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Transaction  $revenue
     *
     * @return Response
     */
    public function edit(Transaction $revenue)
    {
        $accounts = Account::enabled()->orderBy('name')->pluck('name', 'id');

        $currencies = Currency::enabled()->orderBy('name')->pluck('name', 'code')->toArray();

        $currency = Currency::where('code', $revenue->currency_code)->first();

        $customers = Contact::customer()->enabled()->orderBy('name')->take(setting('default.select_limit'))->pluck('name', 'id');

        if ($revenue->contact && !$customers->has($revenue->contact_id)) {
            $customers->put($revenue->contact->id, $revenue->contact->name);
        }

        $categories = Category::income()->enabled()->orderBy('name')->take(setting('default.select_limit'))->pluck('name', 'id');

        if ($revenue->category && !$categories->has($revenue->category_id)) {
            $categories->put($revenue->category->id, $revenue->category->name);
        }

        $payment_methods = Modules::getPaymentMethods();

        $date_format = $this->getCompanyDateFormat();

        $file_type_mimes = explode(',', config('filesystems.mimes'));

        $file_types = [];

        foreach ($file_type_mimes as $mime) {
            $file_types[] = '.' . $mime;
        }

        $file_types = implode(',', $file_types);

        return view('sales.revenues.edit', compact('revenue', 'accounts', 'currencies', 'currency', 'customers', 'categories', 'payment_methods', 'date_format', 'file_types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Transaction $revenue
     * @param  Request $request
     *
     * @return Response
     */
    public function update(Transaction $revenue, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateTransaction($revenue, $request));

        if ($response['success']) {
            $response['redirect'] = route('revenues.show', $revenue->id);

            $message = trans('messages.success.updated', ['type' => trans_choice('general.revenues', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('revenues.edit', $revenue->id);

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Transaction $revenue
     *
     * @return Response
     */
    public function destroy(Transaction $revenue)
    {
        $response = $this->ajaxDispatch(new DeleteTransaction($revenue));

        $response['redirect'] = route('revenues.index');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => trans_choice('general.revenues', 1)]);

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
        return $this->exportExcel(new Export, trans_choice('general.revenues', 2));
    }

    /**
     * Download the PDF file of revenue.
     *
     * @param  Transaction $revenue
     *
     * @return Response
     */
    public function emailRevenue(Transaction $revenue)
    {
        if (empty($revenue->contact->email)) {
            return redirect()->back();
        }

        // Notify the customer
        $revenue->contact->notify(new Notification($revenue, 'revenue_new_customer', true));

        event(new \App\Events\Banking\TransactionSent($revenue));

        flash(trans('documents.messages.email_sent', ['type' => trans_choice('general.revenues', 1)]))->success();

        return redirect()->back();
    }

    /**
     * Print the revenue.
     *
     * @param  Transaction $revenue
     *
     * @return Response
     */
    public function printRevenue(Transaction $revenue)
    {
        event(new \App\Events\Banking\TransactionPrinting($revenue));

        $view = view($revenue->template_path, compact('revenue'));

        return mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');
    }

    /**
     * Download the PDF file of revenue.
     *
     * @param  Transaction $revenue
     *
     * @return Response
     */
    public function pdfRevenue(Transaction $revenue)
    {
        event(new \App\Events\Banking\TransactionPrinting($revenue));

        $currency_style = true;

        $view = view($revenue->template_path, compact('revenue', 'currency_style'))->render();
        $html = mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');

        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($html);

        //$pdf->setPaper('A4', 'portrait');

        $file_name = $this->getTransactionFileName($revenue);

        return $pdf->download($file_name);
    }
}
