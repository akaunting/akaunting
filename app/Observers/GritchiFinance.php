<?php

namespace App\Observers;

use App\Abstracts\Observer;
use App\Models\Banking\Transaction;
use App\Models\Document\Document;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class GritchiFinance extends Observer
{
    public function saved($model): void
    {
        if ($model instanceof Document) {
            $this->syncInvoice($model);
            return;
        }

        if ($model instanceof Transaction) {
            $this->syncPayment($model);
        }
    }

    private function syncInvoice(Document $document): void
    {
        if (! $this->shouldSyncInvoice($document)) {
            return;
        }

        $this->post([
            'event' => 'invoice.saved',
            'invoice' => [
                'id' => $document->id,
                'number' => $document->document_number,
                'status' => $document->status,
                'amount' => $document->amount,
                'paid_amount' => $document->paid,
                'balance' => max((float) $document->amount - (float) $document->paid, 0),
                'currency' => $document->currency_code,
                'issued_on' => optional($document->issued_at)->toDateString(),
                'due_on' => optional($document->due_at)->toDateString(),
                'customer' => $this->customerPayload($document),
            ],
        ]);
    }

    private function syncPayment(Transaction $transaction): void
    {
        if (! $this->shouldSyncPayment($transaction)) {
            return;
        }

        $this->post([
            'event' => 'payment.saved',
            'payment' => [
                'id' => $transaction->id,
                'number' => $transaction->number,
                'status' => 'paid',
                'amount' => $transaction->amount,
                'currency' => $transaction->currency_code,
                'paid_on' => optional($transaction->paid_at)->toDateString(),
                'invoice_id' => $transaction->document_id,
                'customer' => $this->customerPayload($transaction),
            ],
        ]);
    }

    private function shouldSyncInvoice(Document $document): bool
    {
        return (bool) $this->webhookUrl()
            && $document->type === Document::INVOICE_TYPE
            && ! empty($document->contact_email);
    }

    private function shouldSyncPayment(Transaction $transaction): bool
    {
        return (bool) $this->webhookUrl()
            && $transaction->type === Transaction::INCOME_TYPE
            && ! empty($transaction->contact->email);
    }

    private function post(array $payload): void
    {
        try {
            (new Client(['timeout' => 5]))->post($this->webhookUrl(), [
                'headers' => $this->headers(),
                'json' => $payload,
            ]);
        } catch (GuzzleException $e) {
            report($e);
        }
    }

    private function customerPayload($model): array
    {
        $contact = $model->contact;

        return [
            'id' => $contact->id,
            'name' => $contact->name,
            'email' => $contact->email ?: ($model->contact_email ?? null),
            'phone' => $contact->phone ?: ($model->contact_phone ?? null),
        ];
    }

    private function webhookUrl(): ?string
    {
        if ($url = env('GRITCHI_PORTAL_FINANCE_WEBHOOK_URL')) {
            return $url;
        }

        if (! $url = env('GRITCHI_PORTAL_WEBHOOK_URL')) {
            return null;
        }

        return preg_replace('#/webhooks/akaunting$#', '/webhooks/akaunting/finance', $url);
    }

    private function headers(): array
    {
        $headers = ['Accept' => 'application/json'];

        if ($secret = env('GRITCHI_WEBHOOK_SECRET')) {
            $headers['X-Gritchi-Webhook-Secret'] = $secret;
        }

        return $headers;
    }
}
