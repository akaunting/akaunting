<?php

namespace Modules\OfflinePayments\Http\Controllers;

use App\Abstracts\Http\Controller;
use Illuminate\Http\Response;
use Modules\OfflinePayments\Http\Requests\Setting as Request;
use Modules\OfflinePayments\Http\Requests\SettingGet as GRequest;
use Modules\OfflinePayments\Http\Requests\SettingDelete as DRequest;
use Modules\OfflinePayments\Jobs\CreatePaymentMethod;
use Modules\OfflinePayments\Jobs\DeletePaymentMethod;
use Modules\OfflinePayments\Jobs\UpdatePaymentMethod;

class Settings extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit()
    {
        $methods = json_decode(setting('offline-payments.methods'));

        return view('offline-payments::edit', compact('methods'));
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
        if (!empty($request->get('update_code'))) {
            $payment_method = $this->dispatch(new UpdatePaymentMethod($request));

            $message = trans('messages.success.updated', ['type' => $payment_method['name']]);
        } else {
            $payment_method = $this->dispatch(new CreatePaymentMethod($request));

            $message = trans('messages.success.added', ['type' => $payment_method['name']]);
        }

        flash($message)->success();

        return response()->json([
            'success' => true,
            'error' => false,
            'message' => $message,
            'redirect' => route('offline-payments.settings.edit'),
        ]);
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

        $code = $request->get('code');

        $methods = json_decode(setting('offline-payments.methods'), true);

        foreach ($methods as $key => $method) {
            if ($method['code'] != $code) {
                continue;
            }

            $method['title'] = trans('offline-payments::offline-payments.edit', ['method' => $method['name']]);
            $method['update_code'] = $code;

            $code = explode('.', $method['code']);

            $method['code'] = $code[1];

            $data = $method;

            break;
        }

        return response()->json([
            'errors' => false,
            'success' => true,
            'data'    => $data,
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
        $response = $this->ajaxDispatch(new DeletePaymentMethod($request));

        if ($response['success']) {
            //$response['redirect'] = route('offline-payments.settings.edit');

            $response['message'] = trans('messages.success.deleted', ['type' => $response['data']['name']]);

            //flash($message)->success();
        } else {
            //$response['redirect'] = route('offline-payments.settings.edit');

            $message = $response['message'];

            //flash($message)->error();
        }

        return response()->json($response);
    }
}
