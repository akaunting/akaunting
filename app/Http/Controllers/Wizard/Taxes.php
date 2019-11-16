<?php

namespace App\Http\Controllers\Wizard;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Setting\Tax as Request;
use App\Jobs\Setting\CreateTax;
use App\Jobs\Setting\DeleteTax;
use App\Jobs\Setting\UpdateTax;
use App\Models\Setting\Tax;

class Taxes extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function index()
    {
        $taxes = Tax::collect();

        return view('wizard.taxes.index', compact('taxes'));
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
        $response = $this->ajaxDispatch(new CreateTax($request));

        $response['redirect'] = route('wizard.taxes.index');

        if ($response['success']) {
            $message = trans('messages.success.added', ['type' => trans_choice('general.taxes', 1)]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error();
        }

        return response()->json($response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Tax  $tax
     * @param  Request  $request
     *
     * @return Response
     */
    public function update(Tax $tax, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateTax($tax, $request));

        $response['redirect'] = route('wizard.taxes.index');

        if ($response['success']) {
            $message = trans('messages.success.updated', ['type' => $tax->name]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error();
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Tax  $tax
     *
     * @return Response
     */
    public function destroy(Tax $tax)
    {
        $response = $this->ajaxDispatch(new DeleteTax($tax));

        $response['redirect'] = route('wizard.taxes.index');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => $tax->name]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error();
        }

        return response()->json($response);
    }
}
