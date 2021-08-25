<?php

namespace App\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Setting\Setting as Request;

class TransferTemplates extends Controller
{
    public $skip_keys = ['company_id', '_method', '_token', '_prefix', '_template'];

    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-settings-settings')->only('create', 'store');
        $this->middleware('permission:read-settings-settings')->only('index', 'edit');
        $this->middleware('permission:update-settings-settings')->only('update', 'enable', 'disable');
        $this->middleware('permission:delete-settings-settings')->only('destroy');
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
        $prefix = $request->get('_prefix', 'transfer');
        $company_id = $request->get('company_id');

        if (empty($company_id)) {
            $company_id = company_id();
        }

        foreach ($fields as $key => $value) {
            $real_key = $prefix . '.' . $key;

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
            'redirect' => url()->previous(),
        ];

        flash($message)->success();

        return response()->json($response);
    }
}
