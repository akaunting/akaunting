<?php

namespace App\Http\Controllers\Settings;

use App\Abstracts\Http\Controller;
use App\Models\Setting\Setting;
use App\Http\Requests\Setting\Module as Request;

class Modules extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit($alias)
    {
        $setting = Setting::all($alias)->map(function ($s) use ($alias) {
            $s->key = str_replace($alias . '.', '', $s->key);
            return $s;
        })->pluck('value', 'key');

        $module = module($alias);

        return view('settings.modules.edit', compact('setting', 'module'));
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

        // Save all settings
        setting()->save();

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
