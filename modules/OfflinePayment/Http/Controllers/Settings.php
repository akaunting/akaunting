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
     * @return Response
     */
    public function edit()
    {
        $items = json_decode(setting('offlinepayment.methods'));

        return view('offlinepayment::edit', compact('items'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        $offlinepayment = json_decode(setting('offlinepayment.methods'), true);

        if (isset($request['method'])) {
            foreach ($offlinepayment as $key => $method) {
                if ($method['code'] == $request['method']) {
                    $method = explode('.', $request['method']);

                    $offlinepayment[$key]['code'] = 'offlinepayment.' . $request['code'] . '.' . $method[2];
                    $offlinepayment[$key]['name'] = $request['name'];
                    $offlinepayment[$key]['order'] = $request['order'];
                    $offlinepayment[$key]['description'] = $request['description'];
                }
            }
        } else {
            $offlinepayment[] = array(
                'code' => 'offlinepayment.' . $request['code'] . '.' . (count($offlinepayment) + 1),
                'name' => $request['name'],
                'order' => $request['order'],
                'description' => $request['description']
            );
        }

        // Set Api Token
        setting()->set('offlinepayment.methods', json_encode($offlinepayment));

        setting()->save();

        Artisan::call('cache:clear');

        return redirect('apps/offlinepayment/settings');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function get(GRequest $request)
    {
        $code = $request['code'];

        $offlinepayment = json_decode(setting('offlinepayment.methods'), true);

        foreach ($offlinepayment as $key => $method) {
            if ($method['code'] == $code) {
                $method['title'] = trans('offlinepayment::offlinepayment.edit', ['method' => $method['name']]);
                $method['method'] = $code;

                $code = explode('.', $method['code']);

                $method['code'] = $code[1];

                $data = $method;
            }
        }

        return response()->json([
            'errors' => false,
            'success' => true,
            'data'    => $data
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function delete(DRequest $request)
    {
        $code = $request['code'];

        $offlinepayment = json_decode(setting('offlinepayment.methods'), true);

        foreach ($offlinepayment as $key => $method) {
            if ($method['code'] == $code) {
                unset($offlinepayment[$key]);
            }
        }

        // Set Api Token
        setting()->set('offlinepayment.methods', json_encode($offlinepayment));

        setting()->save();

        Artisan::call('cache:clear');

        return response()->json([
            'errors' => false,
            'success' => true,
        ]);
    }
}
