<?php

namespace Modules\OfflinePayment\Http\Controllers;

use Artisan;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\OfflinePayment\Http\Requests\Setting as Request;
use Modules\OfflinePayment\Http\Requests\SettingGet as GRequest;
use Modules\OfflinePayment\Http\Requests\SettingDelete as DRequest;

class Settings extends Controller
{

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit()
    {
        $items = json_decode(setting('offlinepayment.methods'));

        return view('offlinepayment::edit', compact('items'));
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
        $methods = json_decode(setting('offlinepayment.methods'), true);

        if (isset($request['method'])) {
            foreach ($methods as $key => $method) {
                if ($method['code'] != $request['method']) {
                    continue;
                }

                $method = explode('.', $request['method']);

                $methods[$key]['code'] = 'offlinepayment.' . $request['code'] . '.' . $method[2];
                $methods[$key]['name'] = $request['name'];
                $methods[$key]['customer'] = $request['customer'];
                $methods[$key]['order'] = $request['order'];
                $methods[$key]['description'] = $request['description'];
            }
        } else {
            $methods[] = array(
                'code' => 'offlinepayment.' . $request['code'] . '.' . (count($methods) + 1),
                'name' => $request['name'],
                'customer' => $request['customer'],
                'order' => $request['order'],
                'description' => $request['description']
            );
        }

        // Set Api Token
        setting()->set('offlinepayment.methods', json_encode($methods));

        setting()->save();

        Artisan::call('cache:clear');

        return redirect()->route('offlinepayment.edit');
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

        $methods = json_decode(setting('offlinepayment.methods'), true);

        foreach ($methods as $key => $method) {
            if ($method['code'] != $code) {
                continue;
            }

            $method['title'] = trans('offlinepayment::offlinepayment.edit', ['method' => $method['name']]);
            $method['method'] = $code;

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
    public function delete(DRequest $request)
    {
        $code = $request['code'];

        $methods = json_decode(setting('offlinepayment.methods'), true);

        foreach ($methods as $key => $method) {
            if ($method['code'] != $code) {
                continue;
            }

            unset($methods[$key]);
        }

        // Set Api Token
        setting()->set('offlinepayment.methods', json_encode($methods));

        setting()->save();

        Artisan::call('cache:clear');

        return response()->json([
            'errors' => false,
            'success' => true,
        ]);
    }
}
