<?php

namespace App\Http\Controllers\Settings;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Setting\Tax as Request;
use App\Jobs\Setting\CreateTax;
use App\Jobs\Setting\DeleteTax;
use App\Jobs\Setting\UpdateTax;
use App\Models\Setting\Tax;

class Taxes extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $taxes = Tax::collect();

        $types = [
            'fixed' => trans('taxes.fixed'),
            'normal' => trans('taxes.normal'),
            'inclusive' => trans('taxes.inclusive'),
            'withholding' => trans('taxes.withholding'),
            'compound' => trans('taxes.compound'),
        ];

        return $this->response('settings.taxes.index', compact('taxes', 'types'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function show()
    {
        return redirect()->route('taxes.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $types = [
            'fixed' => trans('taxes.fixed'),
            'normal' => trans('taxes.normal'),
            'inclusive' => trans('taxes.inclusive'),
            'withholding' => trans('taxes.withholding'),
            'compound' => trans('taxes.compound'),
        ];

        $disable_options = [];

        if ($compound = Tax::compound()->first()) {
            $disable_options = ['compound'];
        }

        return view('settings.taxes.create', compact('types', 'disable_options'));
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

        if ($response['success']) {
            $response['redirect'] = route('taxes.index');

            $message = trans('messages.success.added', ['type' => trans_choice('general.taxes', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('taxes.create');

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Tax  $tax
     *
     * @return Response
     */
    public function edit(Tax $tax)
    {
        $types = [
            'fixed' => trans('taxes.fixed'),
            'normal' => trans('taxes.normal'),
            'inclusive' => trans('taxes.inclusive'),
            'withholding' => trans('taxes.withholding'),
            'compound' => trans('taxes.compound'),
        ];

        $disable_options = [];

        if ($tax->type != 'compound' && $compound = Tax::compound()->first()) {
            $disable_options = ['compound'];
        }

        return view('settings.taxes.edit', compact('tax', 'types', 'disable_options'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Tax $tax
     * @param  Request $request
     *
     * @return Response
     */
    public function update(Tax $tax, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateTax($tax, $request));

        if ($response['success']) {
            $response['redirect'] = route('taxes.index');

            $message = trans('messages.success.updated', ['type' => $tax->name]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('taxes.edit', $tax->id);

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Enable the specified resource.
     *
     * @param  Tax $tax
     *
     * @return Response
     */
    public function enable(Tax $tax)
    {
        $response = $this->ajaxDispatch(new UpdateTax($tax, request()->merge(['enabled' => 1])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.enabled', ['type' => $tax->name]);
        }

        return response()->json($response);
    }

    /**
     * Disable the specified resource.
     *
     * @param  Tax $tax
     *
     * @return Response
     */
    public function disable(Tax $tax)
    {
        $response = $this->ajaxDispatch(new UpdateTax($tax, request()->merge(['enabled' => 0])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.disabled', ['type' => $tax->name]);
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Tax $tax
     *
     * @return Response
     */
    public function destroy(Tax $tax)
    {
        $response = $this->ajaxDispatch(new DeleteTax($tax));

        $response['redirect'] = route('taxes.index');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => $tax->name]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }
}
