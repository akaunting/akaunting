<?php

namespace App\Http\Controllers\Sales;

use App\Abstracts\Http\Controller;
use App\Exports\Sales\Customers as Export;
use App\Http\Requests\Common\Contact as Request;
use App\Http\Requests\Common\Import as ImportRequest;
use App\Imports\Sales\Customers as Import;
use App\Jobs\Common\CreateContact;
use App\Jobs\Common\DeleteContact;
use App\Jobs\Common\DuplicateContact;
use App\Jobs\Common\UpdateContact;
use App\Models\Common\Contact;
use App\Traits\Contacts;

class Customers extends Controller
{
    use Contacts;

    /**
     * @var string
     */
    public $type = Contact::CUSTOMER_TYPE;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $customers = Contact::customer()->with('media', 'invoices.histories', 'invoices.totals', 'invoices.transactions', 'invoices.media')->collect();

        return $this->response('sales.customers.index', compact('customers'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  Contact  $customer
     *
     * @return Response
     */
    public function show(Contact $customer)
    {
        return view('sales.customers.show', compact('customer'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('sales.customers.create');
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
        $response = $this->ajaxDispatch(new CreateContact($request));

        if ($response['success']) {
            $response['redirect'] = route('customers.show', $response['data']->id);

            $message = trans('messages.success.added', ['type' => trans_choice('general.customers', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('customers.create');

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Duplicate the specified resource.
     *
     * @param  Contact  $customer
     *
     * @return Response
     */
    public function duplicate(Contact $customer)
    {
        $clone = $this->dispatch(new DuplicateContact($customer));

        $message = trans('messages.success.duplicated', ['type' => trans_choice('general.customers', 1)]);

        flash($message)->success();

        return redirect()->route('customers.edit', $clone->id);
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
        $response = $this->importExcel(new Import, $request, trans_choice('general.customers', 2));

        if ($response['success']) {
            $response['redirect'] = route('customers.index');

            flash($response['message'])->success();
        } else {
            $response['redirect'] = route('import.create', ['sales', 'customers']);

            flash($response['message'])->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Contact  $customer
     *
     * @return Response
     */
    public function edit(Contact $customer)
    {
        return view('sales.customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Contact $customer
     * @param  Request $request
     *
     * @return Response
     */
    public function update(Contact $customer, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateContact($customer, $request));

        if ($response['success']) {
            $response['redirect'] = route('customers.show', $response['data']->id);

            $message = trans('messages.success.updated', ['type' => $customer->name]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('customers.edit', $customer->id);

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Enable the specified resource.
     *
     * @param  Contact $customer
     *
     * @return Response
     */
    public function enable(Contact $customer)
    {
        $response = $this->ajaxDispatch(new UpdateContact($customer, request()->merge(['enabled' => 1])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.enabled', ['type' => $customer->name]);
        }

        return response()->json($response);
    }

    /**
     * Disable the specified resource.
     *
     * @param  Contact $customer
     *
     * @return Response
     */
    public function disable(Contact $customer)
    {
        $response = $this->ajaxDispatch(new UpdateContact($customer, request()->merge(['enabled' => 0])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.disabled', ['type' => $customer->name]);
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Contact $customer
     *
     * @return Response
     */
    public function destroy(Contact $customer)
    {
        $response = $this->ajaxDispatch(new DeleteContact($customer));

        $response['redirect'] = route('customers.index');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => $customer->name]);

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
        return $this->exportExcel(new Export, trans_choice('general.customers', 2));
    }

    public function createInvoice(Contact $customer)
    {
        $data['contact'] = $customer;

        return redirect()->route('invoices.create')->withInput($data);
    }

    public function createIncome(Contact $customer)
    {
        $data['contact'] = $customer;

        return redirect()->route('transactions.create', ['type' => 'income'])->withInput($data);
    }
}
