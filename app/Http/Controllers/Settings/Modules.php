<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Setting\Setting;
use File;
use Module;

class Modules extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit($alias)
    {
        /*$setting = Setting::all($alias)->pluck('value', 'key');*/
        $setting = Setting::all($alias)->map(function($s) use($alias) {
            $s->key = str_replace($alias . '.', '', $s->key);
            return $s;
        })->pluck('value', 'key');

        $module = Module::findByAlias($alias);

        return view('settings.modules.edit', compact('setting', 'module'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $alias
     *
     * @return Response
     */
    public function update($alias)
    {
        $fields = request()->all();
        
        $skip_keys = ['company_id', '_method', '_token'];
        
        foreach ($fields as $key => $value) {
            // Don't process unwanted keys
            if (in_array($key, $skip_keys)) {
                continue;
            }

            setting()->set($alias . '.' . $key, $value);
        }

        // Save all settings
        setting()->save();

        $message = trans('messages.success.updated', ['type' => trans_choice('general.settings', 2)]);

        flash($message)->success();

        return redirect('settings/apps/' . $alias);
    }
}
