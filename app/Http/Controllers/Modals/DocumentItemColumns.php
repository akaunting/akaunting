<?php

namespace App\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Setting\Setting as Request;
use App\Traits\Documents;

class DocumentItemColumns extends Controller
{
    use Documents;

    public $skip_keys = ['company_id', '_method', '_token', '_template', 'type'];

    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:read-settings-invoice')->only('index', 'edit');
        $this->middleware('permission:update-settings-invoice')->only('update', 'enable', 'disable');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Contact  $customer
     *
     * @return Response
     */
    public function edit()
    {
        $type = request()->get('type', 'invoice');

        $item_names = [
            'hide' => trans('settings.invoice.hide.item_name'),
            'settings.invoice.item' => trans('settings.invoice.item'),
            'settings.invoice.product' => trans('settings.invoice.product'),
            'settings.invoice.service' =>  trans('settings.invoice.service'),
            'custom' => trans('settings.invoice.custom'),
        ];

        $price_names = [
            'hide' => trans('settings.invoice.hide.price'),
            'settings.invoice.price' => trans('settings.invoice.price'),
            'settings.invoice.rate' => trans('settings.invoice.rate'),
            'custom' => trans('settings.invoice.custom'),
        ];

        $quantity_names = [
            'hide' => trans('settings.invoice.hide.quantity'),
            'settings.invoice.quantity' => trans('settings.invoice.quantity'),
            'custom' => trans('settings.invoice.custom'),
        ];

        $item_name             = setting($this->getDocumentSettingKey($type, 'item_name'));
        $item_name_input       = setting($this->getDocumentSettingKey($type, 'item_name_input'));
        $price_name            = setting($this->getDocumentSettingKey($type, 'price_name'));
        $price_name_input      = setting($this->getDocumentSettingKey($type, 'price_name_input'));
        $quantity_name         = setting($this->getDocumentSettingKey($type, 'quantity_name'));
        $quantity_name_input   = setting($this->getDocumentSettingKey($type, 'quantity_name_input'));
        $hide_item_name        = setting($this->getDocumentSettingKey($type, 'hide_item_name'));
        $hide_item_description = setting($this->getDocumentSettingKey($type, 'hide_item_description'));
        $hide_quantity         = setting($this->getDocumentSettingKey($type, 'hide_quantity'));
        $hide_price            = setting($this->getDocumentSettingKey($type, 'hide_price'));
        $hide_amount           = setting($this->getDocumentSettingKey($type, 'hide_amount'));

        $html = view('modals.documents.item_columns', compact(
            'type',
            'item_names',
            'price_names',
            'quantity_names',
            'item_name',
            'item_name_input',
            'price_name',
            'price_name_input',
            'quantity_name',
            'quantity_name_input',
            'hide_item_name',
            'hide_item_description',
            'hide_quantity',
            'hide_price',
            'hide_amount',
        ))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'message' => 'null',
            'html' => $html,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     *
     * @return Response
     */
    public function update(Request $request)
    {
        $fields = $request->all();
        $type = $request->get('type', 'invoice');
        $company_id = $request->get('company_id');

        if (empty($company_id)) {
            $company_id = company_id();
        }

        foreach ($fields as $key => $value) {
            $real_key = $this->getDocumentSettingKey($type, $key);

            // Don't process unwanted keys
            if (in_array($key, $this->skip_keys)) {
                continue;
            }

            setting()->set($real_key, $value);
        }

        // Save all settings
        setting()->save();

        $message = trans('messages.success.updated', ['type' => trans_choice('general.settings', 2)]);

        $response = [
            'status' => null,
            'success' => true,
            'error' => false,
            'message' => $message,
            'data' => null,
            'redirect' => route('settings.invoice.edit'),
        ];

        flash($message)->success();

        return response()->json($response);
    }
}
