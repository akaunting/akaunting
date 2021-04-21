<?php

namespace App\Abstracts\Http;

use App\Events\Document\PaymentReceived;
use App\Http\Requests\Portal\InvoicePayment as PaymentRequest;
use App\Models\Document\Document;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\URL;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

abstract class PaymentController extends BaseController
{
    public $alias = '';

    public $type = ''; // hosted, redirect

    public $setting = [];

    public $logger = null;

    public $user = null;

    public $module = null;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->setting = setting($this->alias);
            $this->setting['code'] = $this->alias;
            $this->setting['language'] = app()->getLocale();

            $this->logger = $this->getLogger();

            $this->user = user();

            $this->module = module($this->alias);

            return $next($request);
        });
    }

    public function show(Document $invoice, PaymentRequest $request)
    {
        $this->setContactFirstLastName($invoice);

        $confirm_url = $this->getConfirmUrl($invoice);

        $html = view('partials.portal.payment_method.' . $this->type, [
            'setting' => $this->setting,
            'invoice' => $invoice,
            'confirm_url' => $confirm_url,
        ])->render();

        return response()->json([
            'code' => $this->setting['code'],
            'name' => $this->setting['name'],
            'description' => trans($this->alias . '::general.description'),
            'redirect' => false,
            'html' => $html,
        ]);
    }

    public function signed(Document $invoice, PaymentRequest $request)
    {
        return $this->show($invoice, $request);
    }

    public function cancel(Document $invoice, $force_redirect = false)
    {
        $message = trans('messages.warning.payment_cancel', ['method' => setting($this->alias . '.name')]);

        $this->logger->info($this->module->getName() . ':: Invoice: ' . $invoice->id . ' - Cancel Message: ' . $message);

        flash($message)->warning()->important();

        $invoice_url = $this->getInvoiceUrl($invoice);

        if ($force_redirect || ($this->type == 'redirect')) {
            return redirect($invoice_url);
        }

        return response()->json([
            'error' => $message,
            'redirect' => $invoice_url,
            'success' => false,
            'data' => false,
        ]);
    }

    public function finish($invoice, $request, $force_redirect = false)
    {
        $this->dispatchPaidEvent($invoice, $request);

        $this->forgetReference($invoice);

        $message = trans('messages.success.added', ['type' => trans_choice('general.payments', 1)]);

        $this->logger->info($this->module->getName() . ':: Invoice: ' . $invoice->id . ' - Success Message: ' . $message);

        flash($message)->success();

        $invoice_url = $this->getInvoiceUrl($invoice);

        if ($force_redirect || ($this->type == 'redirect')) {
            return redirect($invoice_url);
        }

        return response()->json([
            'error' => $message,
            'redirect' => $invoice_url,
            'success' => true,
            'data' => false,
        ]);
    }

    public function getInvoiceUrl($invoice)
    {
        return request()->isPortal($invoice->company_id)
                ? route('portal.invoices.show', $invoice->id)
                : URL::signedRoute('signed.invoices.show', [$invoice->id]);
    }

    public function getConfirmUrl($invoice)
    {
        return $this->getModuleUrl($invoice, 'confirm');
    }

    public function getReturnUrl($invoice)
    {
        return $this->getModuleUrl($invoice, 'return');
    }

    public function getCancelUrl($invoice)
    {
        return $this->getModuleUrl($invoice, 'cancel');
    }

    public function getNotifyUrl($invoice)
    {
        return route('portal.' . $this->alias . '.invoices.notify', $invoice->id);
    }

    public function getModuleUrl($invoice, $suffix)
    {
        return request()->isPortal($invoice->company_id)
                ? route('portal.' . $this->alias . '.invoices.' . $suffix, $invoice->id)
                : URL::signedRoute('signed.' . $this->alias . '.invoices.' . $suffix, [$invoice->id]);
    }

    public function getLogger()
    {
        $log = new Logger($this->alias);
        $log->pushHandler(new StreamHandler(storage_path('logs/' . $this->alias . '.log')), Logger::INFO);

        return $log;
    }

    public function dispatchPaidEvent($invoice, $request)
    {
        $request['company_id'] = $invoice->company_id;
        $request['account_id'] = setting($this->alias . '.account_id', setting('default.account'));
        $request['amount'] = $invoice->amount;
        $request['payment_method'] = $this->alias;
        $request['reference'] = $this->getReference($invoice);
        $request['type'] = 'income';

        event(new PaymentReceived($invoice, $request));
    }

    public function setReference($invoice, $reference)
    {
        session([
            $this->alias . '_' . $invoice->id . '_reference' => $reference
        ]);
    }

    public function getReference($invoice)
    {
        return session($this->alias . '_' . $invoice->id . '_reference');
    }

    public function forgetReference($invoice)
    {
        session()->forget($this->alias . '_' . $invoice->id . '_reference');
    }

    public function setContactFirstLastName(&$invoice)
    {
        $contact = explode(" ", $invoice->contact_name);

        $last_name = array_pop($contact);
        $first_name = implode(" ", $contact);

        $invoice->first_name = $first_name;
        $invoice->last_name = $last_name;
    }
}
