<?php

namespace Modules\OfflinePayments\Http\Controllers;

use Artisan;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\OfflinePayments\Http\Requests\Setting as Request;
use Modules\OfflinePayments\Http\Requests\SettingGet as GRequest;
use Modules\OfflinePayments\Http\Requests\SettingDelete as DRequest;

class Settings extends Controller
{

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit()
    {
        $items = json_decode(setting('offline-payments.methods'));

        return view('offline-payments::edit', compact('items'));
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
        $methods = json_decode(setting('offline-payments.methods'), true);

        if (isset($request['method'])) {
            foreach ($methods as $key => $method) {
                if ($method['code'] != $request['method']) {
                    continue;
                }

                $method = explode('.', $request['method']);

                $methods[$key]['code'] = 'offline-payments.' . $request['code'] . '.' . $method[2];
                $methods[$key]['name'] = $request['name'];
                $methods[$key]['customer'] = $request['customer'];
                $methods[$key]['order'] = $request['order'];
                $methods[$key]['description'] = $request['description'];
            }

            $message = trans('messages.success.updated', ['type' => $request['name']]);
        } else {
            $methods[] = array(
                'code' => 'offline-payments.' . $request['code'] . '.' . (count($methods) + 1),
                'name' => $request['name'],
                'customer' => $request['customer'],
                'order' => $request['order'],
                'description' => $request['description']
            );

            $message = trans('messages.success.added', ['type' => $request['name']]);
        }

        // Set Api Token
        setting()->set('offline-payments.methods', json_encode($methods));

        setting()->save();

        Artisan::call('cache:clear');

        $response = [
            'status' => null,
            'success' => true,
            'error' => false,
            'message' => $message,
            'data' => null,
            'redirect' => route('offline-payments.edit'),
        ];

        flash($message)->success();

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  GRequest  $request
     *
     * @return Response
     */
    public function get(GRequest $request)
    {
        $data = [];

        $code = $request['code'];

        $methods = json_decode(setting('offline-payments.methods'), true);

        foreach ($methods as $key => $method) {
            if ($method['code'] != $code) {
                continue;
            }

            $method['title'] = trans('offline-payments::offline-payments.edit', ['method' => $method['name']]);
            $method['update'] = $code;

            $code = explode('.', $method['code']);

            $method['code'] = $code[1];

            $data = $method;

            break;
        }

        return response()->json([
            'errors' => false,
            'success' => true,
            'data'    => $data
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DRequest  $request
     *
     * @return Response
     */
    public function destroy(DRequest $request)
    {
        $code = $request['code'];

        $methods = json_decode(setting('offline-payments.methods'), true);

        $remove = false;

        foreach ($methods as $key => $method) {
            if ($method['code'] != $code) {
                continue;
            }

            $remove = $methods[$key];
            unset($methods[$key]);
        }

        // Set Api Token
        setting()->set('offline-payments.methods', json_encode($methods));

        setting()->save();

        Artisan::call('cache:clear');

        $message = trans('messages.success.deleted', ['type' => $remove['name']]);

        flash($message)->success();

        return response()->json([
            'errors' => false,
            'success' => true,
            'message' => $message,
            'redirect' => route('offline-payments.edit'),
        ]);
    }
}
