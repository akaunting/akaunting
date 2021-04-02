<?php

namespace Modules\Offline\Http\Controllers;

use Artisan;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Offline\Http\Requests\Setting as Request;
use Modules\Offline\Http\Requests\SettingGet as GRequest;
use Modules\Offline\Http\Requests\SettingDelete as DRequest;

class Settings extends Controller
{
    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        $items = json_decode(setting('offline.payment.methods'));

        return view('offline::edit', compact('items'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        $offline = json_decode(setting('offline.payment.methods'), true);

        if (isset($request['method'])) {
            foreach ($offline as $key => $method) {
                if ($method['code'] == $request['method']) {
                    $offline[$key]['code'] = 'offline.' . $request['code'] . '.' . (count($offline) + 1);
                    $offline[$key]['name'] = $request['name'];
                    $offline[$key]['order'] = $request['order'];
                    $offline[$key]['description'] = $request['description'];
                }
            }
        } else {
            $offline[] = array(
                'code' => 'offline.' . $request['code'] . '.' . (count($offline) + 1),
                'name' => $request['name'],
                'order' => $request['order'],
                'description' => $request['description']
            );
        }

        // Set Api Token
        setting()->set('offline.payment.methods', json_encode($offline));

        setting()->save();

        Artisan::call('cache:clear');

        return redirect('modules/offline/settings');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function get(GRequest $request)
    {
        $code = $request['code'];

        $offline = json_decode(setting('offline.payment.methods'), true);

        foreach ($offline as $key => $method) {
            if ($method['code'] == $code) {
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

        $offline = json_decode(setting('offline.payment.methods'), true);

        foreach ($offline as $key => $method) {
            if ($method['code'] == $code) {
                unset($offline[$key]);
            }
        }

        // Set Api Token
        setting()->set('offline.payment.methods', json_encode($offline));

        setting()->save();

        Artisan::call('cache:clear');

        return response()->json([
            'errors' => false,
            'success' => true,
        ]);
    }
}
