<?php

namespace App\Http\Controllers\Purchases;

use App\Abstracts\Http\Controller;
use App\Exports\Purchases\Vendors as Export;
use App\Http\Requests\Common\Contact as Request;
use App\Http\Requests\Common\Import as ImportRequest;
use App\Imports\Purchases\Vendors as Import;
use App\Jobs\Common\CreateContact;
use App\Jobs\Common\DeleteContact;
use App\Jobs\Common\DuplicateContact;
use App\Jobs\Common\UpdateContact;
use App\Models\Common\Contact;
use App\Traits\Contacts;

class Vendors extends Controller
{
    use Contacts;

    /**
     * @var string
     */
    public $type = Contact::VENDOR_TYPE;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $vendors = Contact::with('media', 'bills.histories', 'bills.totals', 'bills.transactions', 'bills.media')->vendor()->collect();

        return $this->response('purchases.vendors.index', compact('vendors'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  Contact  $vendor
     *
     * @return Response
     */
    public function show(Contact $vendor)
    {
        return view('purchases.vendors.show', compact('vendor'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('purchases.vendors.create');
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
            $response['redirect'] = route('vendors.show', $response['data']->id);

            $message = trans('messages.success.created', ['type' => trans_choice('general.vendors', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('vendors.create');

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Duplicate the specified resource.
     *
     * @param  Contact  $vendor
     *
     * @return Response
     */
    public function duplicate(Contact $vendor)
    {
        $clone = $this->dispatch(new DuplicateContact($vendor));

        $message = trans('messages.success.duplicated', ['type' => trans_choice('general.vendors', 1)]);

        flash($message)->success();

        return redirect()->route('vendors.edit', $clone->id);
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
        $response = $this->importExcel(new Import, $request, trans_choice('general.vendors', 2));

        if ($response['success']) {
            $response['redirect'] = route('vendors.index');

            flash($response['message'])->success();
        } else {
            $response['redirect'] = route('import.create', ['purchases', 'vendors']);

            flash($response['message'])->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Contact  $vendor
     *
     * @return Response
     */
    public function edit(Contact $vendor)
    {
        return view('purchases.vendors.edit', compact('vendor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Contact $vendor
     * @param  Request $request
     *
     * @return Response
     */
    public function update(Contact $vendor, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateContact($vendor, $request));

        if ($response['success']) {
            $response['redirect'] = route('vendors.show', $response['data']->id);

            $message = trans('messages.success.updated', ['type' => $vendor->name]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('vendors.edit', $vendor->id);

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Enable the specified resource.
     *
     * @param  Contact $vendor
     *
     * @return Response
     */
    public function enable(Contact $vendor)
    {
        $response = $this->ajaxDispatch(new UpdateContact($vendor, request()->merge(['enabled' => 1])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.enabled', ['type' => $vendor->name]);
        }

        return response()->json($response);
    }

    /**
     * Disable the specified resource.
     *
     * @param  Contact $vendor
     *
     * @return Response
     */
    public function disable(Contact $vendor)
    {
        $response = $this->ajaxDispatch(new UpdateContact($vendor, request()->merge(['enabled' => 0])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.disabled', ['type' => $vendor->name]);
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Contact $vendor
     *
     * @return Response
     */
    public function destroy(Contact $vendor)
    {
        $response = $this->ajaxDispatch(new DeleteContact($vendor));

        $response['redirect'] = route('vendors.index');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => $vendor->name]);

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
        return $this->exportExcel(new Export, trans_choice('general.vendors', 2));
    }

    public function createBill(Contact $vendor)
    {
        $data['contact'] = $vendor;

        return redirect()->route('bills.create')->withInput($data);
    }

    public function createExpense(Contact $vendor)
    {
        $data['contact'] = $vendor;

        return redirect()->route('transactions.create', ['type' => 'expense'])->withInput($data);
    }
}
