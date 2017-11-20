<?php

namespace Modules\PaypalStandard\Http\Controllers;

use App\Events\InvoicePaid;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use GuzzleHttp\Client;

use Illuminate\Http\Request;
use App\Http\Requests\Customer\InvoicePayment as PaymentRequest;

use App\Models\Income\Invoice;

class PaypalStandard extends Controller
{
    /**
     * Show the form for editing the specified resource.
     * @param Invoice
     * @param PaymentRequest
     * @return Response
     */
    public function show(Invoice $invoice, PaymentRequest $request)
    {
        $gateway = setting('paypalstandard');

        $gateway['action'] = 'https://www.paypal.com/cgi-bin/webscr';

        if ($gateway['mode'] == 'sandbox') {
            $gateway['action'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        }

        $customer = explode(" ", $invoice->customer_name);

        $last_name = array_pop($customer);
        $first_name = implode(" ", $customer);

        $invoice->first_name = $first_name;
        $invoice->last_name = $last_name;

        $gateway['language'] = \App::getLocale();

        $html = view('paypalstandard::show', compact('gateway', 'invoice'))->render();

        return response()->json([
            'code' => 'paypalstandard',
            'name' => $gateway['name'],
            'description' => trans('paypalstandard::paypalstandard.description'),
            'redirect' => false,
            'html' => $html,
        ]);
    }

    public function result(Invoice $invoice, Request $request)
    {
        $success = true;

        switch ($request['payment_status']) {
            case 'Completed':
                $message = trans('messages.success.added', ['type' => trans_choice('general.customers', 1)]);
                break;
            case 'Canceled_Reversal':
            case 'Denied':
            case 'Expired':
            case 'Failed':
            case 'Pending':
            case 'Processed':
            case 'Refunded':
            case 'Reversed':
            case 'Voided':
                $message = trans('messages.error.added', ['type' => trans_choice('general.customers', 1)]);
                $success = false;
                break;
        }

        if ($success) {
            flash($message)->success();
        } else {
            flash($message)->warning();
        }

        $redirect = url('customers/invoices/' . $invoice->id);

        return redirect($redirect);
    }

    public function callback(Invoice $invoice, Request $request)
    {
        $gateway = setting('paypalstandard');

        $paypal_log = new Logger('Paypal');

        $paypal_log->pushHandler(new StreamHandler(storage_path('logs/paypal.log')), Logger::INFO);

        if ($invoice) {
            $url = 'https://ipnpb.paypal.com/cgi-bin/webscr';

            if ($gateway['mode'] == 'sandbox') {
                $url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
            }

            $client = new Client(['verify' => false]);

            $paypal_request['cmd'] = '_notify-validate';

            foreach ($request->toArray() as $key => $value) {
                $paypal_request[$key] = urlencode(html_entity_decode($value, ENT_QUOTES, 'UTF-8'));
            }

            $result = $client->post($url, $paypal_request);

            if ($result->getStatusCode() != 200) {
                $paypal_log->info('PAYPAL_STANDARD :: CURL failed ', $result->getBody()->getContents());
            } else {
                $result = $result->getBody()->getContents();
            }

            if ($gateway['debug']) {
                $paypal_log->info('PAYPAL_STANDARD :: IPN REQUEST: ', $request->toArray());
                //$paypal_log->info('PAYPAL_STANDARD :: IPN RESULT: ', $result);
            }

            if ((strcmp($result, 'VERIFIED') == 0 || strcmp($result, 'UNVERIFIED') == 0) || true) {
                switch ($request['payment_status']) {
                    case 'Completed':
                        $receiver_match = (strtolower($request['receiver_email']) == strtolower($gateway['email']));

                        $total_paid_match = ((float)$request['mc_gross'] == $invoice->amount);

                        if ($receiver_match && $total_paid_match) {
                            event(new InvoicePaid($invoice, $request->toArray()));
                        }

                        if (!$receiver_match) {
                            $paypal_log->info('PAYPAL_STANDARD :: RECEIVER EMAIL MISMATCH! ' . strtolower($request['receiver_email']));
                        }

                        if (!$total_paid_match) {
                            $paypal_log->info('PAYPAL_STANDARD :: TOTAL PAID MISMATCH! ' . $request['mc_gross']);
                        }
                        break;
                    case 'Canceled_Reversal':
                    case 'Denied':
                    case 'Expired':
                    case 'Failed':
                    case 'Pending':
                    case 'Processed':
                    case 'Refunded':
                    case 'Reversed':
                    case 'Voided':
                        $paypal_log->info('PAYPAL_STANDARD :: NOT COMPLETED ' . $request->toArray());
                        break;
                }
            } else {
                $paypal_log->info('PAYPAL_STANDARD :: VERIFIED != 0 || UNVERIFIED != 0 ' . $request->toArray());
            }
        }
    }
}
