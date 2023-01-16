<?php

namespace App\Http\Controllers\Api\Settings;

use App\Abstracts\Http\ApiController;
use App\Http\Requests\Setting\Setting as Request;
use App\Http\Resources\Setting\Setting as Resource;
use App\Models\Setting\Setting;
use Laratrust\Middleware\LaratrustMiddleware;


class Settings extends ApiController
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        // We've added base 3 permission then get all setting thsi permissions
        $this->middleware('permission:create-settings-company|create-settings-defaults|create-settings-localisation')->only('create', 'store', 'duplicate', 'import');
        $this->middleware('permission:read-settings-company|read-settings-defaults|read-settings-localisation')->only('index', 'show', 'edit', 'export');
        $this->middleware('permission:update-settings-company|update-settings-defaults|update-settings-localisation')->only('update', 'enable', 'disable', 'destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $settings = Setting::all();

        return Resource::collection($settings);
    }

    /**
     * Display the specified resource.
     *
     * @param  int|string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Check if we're querying by id or key
        if (is_numeric($id)) {
            $setting = Setting::find($id);
        } else {
            $setting = Setting::where('key', $id)->first();
        }

        if (! $setting instanceof Setting) {
            return $this->errorInternal('No query results for model [' . Setting::class . '] ' . $id);
        }

        return new Resource($setting);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $setting = Setting::create($request->all());

        return $this->created(route('api.settings.show', $setting->id), new Resource($setting));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $setting
     * @param  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Setting $setting, Request $request)
    {
        $setting->update($request->all());

        return new Resource($setting->fresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        $setting->delete();

        return $this->noContent();
    }
}
