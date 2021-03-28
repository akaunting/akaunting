<?php

namespace App\Traits;

trait Omnipay
{
    public $gateway;

    public $factory;

    public function authorize($invoice, $request, $extra_options = [])
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
            $response = $this->gateway->authorize($options)->send();
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

            $options['transactionReference'] = $response->getTransactionReference();

            $response = $this->gateway->capture($options)->send();

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

    public function purchase($invoice, $request, $extra_options = [])
    {
        $default_options = [
            'amount' => $invoice->amount - $invoice->paid,
            'currency' => $invoice->currency_code,
            'transactionId' => $invoice->id,
            'returnUrl' => $this->getReturnUrl($invoice),
            'cancelUrl' => $this->getCancelUrl($invoice),
        ];

        $options = array_merge($default_options, $extra_options);

        try {
            $response = $this->gateway->purchase($options)->send();
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

    public function completePurchase($invoice, $request, $extra_options = [])
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

        $response = $this->gateway->completePurchase($options)->send();

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

        flash($message)->error()->important();

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

    public function all()
    {
        return $this->callFactory('all');
    }

    public function replace($gateways)
    {
        return $this->callFactory('replace', [$gateways]);
    }

    public function register($class_name)
    {
        return $this->callFactory('register', [$class_name]);
    }

    public function create($class, $http_client = null, $http_request = null)
    {
        $this->gateway = $this->callFactory('create', [$class, $http_client, $http_request]);

        return $this->gateway;
    }

    public function callFactory($method, $parameters = [])
    {
        $factory = $this->getFactory();

        return call_user_func_array(array($factory, $method), (array) $parameters);
    }

    public function getFactory()
    {
        if (is_null($this->factory)) {
            $this->factory = new \Omnipay\Common\GatewayFactory();
        }

        return $this->factory;
    }

    public function setCardFirstLastName(&$request)
    {
        $contact = explode(" ", $request['cardName']);

        $last_name = array_pop($contact);
        $first_name = implode(" ", $contact);

        $request['cardFirstName'] = $first_name;
        $request['cardLastName'] = $last_name;
    }
}
