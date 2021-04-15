<?php

namespace App\Http\Controllers\Settings;

use App\Abstracts\Http\Controller;
use App\Models\Banking\Account;
use App\Models\Setting\Category;
use App\Models\Setting\Setting;
use App\Utilities\Modules as Utility;
use App\Http\Requests\Setting\Module as Request;

class Modules extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        $alias = request()->segment(2);

        // Add CRUD permission check
        $this->middleware('permission:create-' . $alias . '-settings')->only('create', 'store', 'duplicate', 'import');
        $this->middleware('permission:read-' . $alias . '-settings')->only('index', 'show', 'edit', 'export');
        $this->middleware('permission:update-' . $alias . '-settings')->only('update', 'enable', 'disable');
        $this->middleware('permission:delete-' . $alias . '-settings')->only('destroy');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit($alias)
    {
        $accounts = Account::enabled()->orderBy('name')->pluck('name', 'id');
        $categories = Category::income()->enabled()->orderBy('name')->pluck('name', 'id');

        $setting = Setting::prefix($alias)->get()->transform(function ($s) use ($alias) {
            $s->key = str_replace($alias . '.', '', $s->key);
            return $s;
        })->pluck('value', 'key');

        $module = module($alias);

        return view('settings.modules.edit', compact('setting', 'module', 'accounts', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $alias
     *
     * @return Response
     */
    public function update($alias, Request $request)
    {
        $fields = $request->except(['company_id', '_method', '_token', 'module_alias']);

        foreach ($fields as $key => $value) {
            setting()->set($alias . '.' . $key, $value);
        }

        setting()->save();

        Utility::clearPaymentMethodsCache();

        $message = trans('messages.success.updated', ['type' => trans_choice('general.settings', 2)]);

        $response = [
            'status' => null,
            'success' => true,
            'error' => false,
            'message' => $message,
            'data' => null,
            'redirect' => route('settings.index')//('settings/apps/' . $alias),
        ];

        flash($message)->success();

        return response()->json($response);
    }
}
