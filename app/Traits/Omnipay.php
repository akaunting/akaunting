<?php

namespace App\Traits;

trait Omnipay
{
    public function authorize($invoice, $request, $gateway, $extra_options = [])
    {
        $default_options = [
            'amount' => $invoice->amount,
            'currency' => $invoice->currency_code,
            'transactionId' => $invoice->id,
            'returnUrl' => $this->getReturnUrl($invoice),
            'cancelUrl' => $this->getCancelUrl($invoice),
        ];

        $options = array_merge($default_options, $extra_options);

        try {
            $response = $gateway->authorize($options)->send();
        } catch (\Exception $e) {
            $this->logger->info($this->module->getName() . ':: Invoice: ' . $invoice->id . ' - Error: '. $e->getMessage());

            $message = $e->getMessage();

            return response()->json([
                'error' => $message,
                'redirect' => false,
                'success' => false,
                'data' => false,
            ]);
        }

        if ($response->isSuccessful()) {
            $this->setReference($invoice, $response->getTransactionReference());

            $response = $gateway->capture([
                'amount' => $invoice->amount,
                'currency' => $invoice->currency_code,
                'transactionId' => $this->getReference($invoice),
            ])->send();

            return $this->finish($invoice, $request);
        }

        return $this->failure($invoice, $response);
    }

    public function purchase($invoice, $request, $gateway, $extra_options = [])
    {
        $default_options = [
            'amount' => $invoice->amount,
            'currency' => $invoice->currency_code,
            'transactionId' => $invoice->id,
            'returnUrl' => $this->getReturnUrl($invoice),
            'cancelUrl' => $this->getCancelUrl($invoice),
        ];

        $options = array_merge($default_options, $extra_options);

        try {
            $response = $gateway->purchase($options)->send();
        } catch (\Exception $e) {
            $this->logger->info($this->module->getName() . ':: Invoice: ' . $invoice->id . ' - Error: '. $e->getMessage());

            $message = $e->getMessage();

            return response()->json([
                'error' => $message,
                'redirect' => false,
                'success' => false,
                'data' => false,
            ]);
        }

        if ($response->isSuccessful()) {
            $this->setReference($invoice, $response->getTransactionReference());

            return $this->finish($invoice, $request);
        }

        if ($response->isRedirect()) {
            $this->setReference($invoice, $response->getTransactionReference());

            return response()->json([
                'error' => false,
                'redirect' => $response->getRedirectUrl(),
                'success' => false,
                'data' => $response->getRedirectData(),
            ]);
        }

        return $this->failure($invoice, $response);
    }

    public function completePurchase($invoice, $request, $gateway, $extra_options = [])
    {
        $default_options = [
            'amount' => $invoice->amount,
            'currency' => $invoice->currency_code,
            'transactionId' => $invoice->id,
            'transactionReference' => $this->getReference($invoice),
            //'returnUrl' => $this->getReturnUrl($invoice),
            //'cancelUrl' => $this->getCancelUrl($invoice),
        ];

        $options = array_merge($default_options, $extra_options);

        $response = $gateway->completePurchase($options)->send();

        if ($response->isSuccessful()) {
            return $this->finish($invoice, $request);
        }

        if ($response->isCancelled()) {
            return $this->cancel($invoice);
        }

        return $this->failure($invoice, $response, true);
    }

    public function failure($invoice, $response, $force_redirect = false)
    {
        $data = $response->getData();
        $message = $response->getMessage();

        if (isset($data['error'])) {
            $this->logger->info($this->module->getName() . ':: Invoice: ' . $invoice->id . ' - Error Type: ' . $data['error']['type'] . ' - Error Message: ' . $message);
        } else {
            $this->logger->info($this->module->getName() . ':: Invoice: ' . $invoice->id . ' - Error Message: ' . $message);
        }

        $invoice_url = $this->getInvoiceUrl($invoice);

        flash($message)->error();

        if ($force_redirect) {
            return redirect($invoice_url);
        }

        return response()->json([
            'error' => $message,
            'redirect' => $invoice_url,
            'success' => false,
            'data' => false,
        ]);
    }
}
