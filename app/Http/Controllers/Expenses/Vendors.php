<?php

namespace App\Http\Controllers\Expenses;

use App\Http\Controllers\Controller;
use App\Http\Requests\Expense\Vendor as Request;
use App\Models\Expense\Vendor;
use App\Models\Setting\Currency;

class Vendors extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $vendors = Vendor::collect();

        return view('expenses.vendors.index', compact('vendors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $currencies = Currency::enabled()->pluck('name', 'code');

        return view('expenses.vendors.create', compact('currencies'));
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
        Vendor::create($request->all());

        $message = trans('messages.success.added', ['type' => trans_choice('general.vendors', 1)]);

        flash($message)->success();

        return redirect('expenses/vendors');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Vendor  $vendor
     *
     * @return Response
     */
    public function edit(Vendor $vendor)
    {
        $currencies = Currency::enabled()->pluck('name', 'code');

        return view('expenses.vendors.edit', compact('vendor', 'currencies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Vendor  $vendor
     * @param  Request  $request
     *
     * @return Response
     */
    public function update(Vendor $vendor, Request $request)
    {
        $vendor->update($request->all());

        $message = trans('messages.success.updated', ['type' => trans_choice('general.vendors', 1)]);

        flash($message)->success();

        return redirect('expenses/vendors');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Vendor  $vendor
     *
     * @return Response
     */
    public function destroy(Vendor $vendor)
    {
        $canDelete = $vendor->canDelete();

        if ($canDelete === true) {
            $vendor->delete();

            $message = trans('messages.success.deleted', ['type' => trans_choice('general.vendors', 1)]);

            flash($message)->success();
        } else {
            $text = array();

            if (isset($canDelete['bills'])) {
                $text[] = '<b>' . $canDelete['bills'] . '</b> ' . trans_choice('general.bills', ($canDelete['bills'] > 1) ? 2 : 1);
            }

            if (isset($canDelete['payments'])) {
                $text[] = '<b>' . $canDelete['payments'] . '</b> ' . trans_choice('general.payments', ($canDelete['payments'] > 1) ? 2 : 1);
            }

            $message = trans('messages.warning.deleted', ['type' => trans_choice('general.vendors', 1), 'text' => implode(', ', $text)]);

            flash($message)->warning();
        }

        return redirect('expenses/vendors');
    }

    public function currency()
    {
        $vendor_id = request('vendor_id');

        $vendor = Vendor::find($vendor_id);

        return response()->json($vendor);
    }
}
