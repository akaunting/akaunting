<?php

namespace App\Observers;

use App\Abstracts\Observer;
use App\Models\Common\Contact;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class GritchiContact extends Observer
{
    public function saved(Contact $contact): void
    {
        if (! $this->shouldSync($contact)) {
            return;
        }

        try {
            (new Client(['timeout' => 5]))->post(env('GRITCHI_PORTAL_WEBHOOK_URL'), [
                'headers' => $this->headers(),
                'json' => [
                    'event' => 'customer.saved',
                    'customer' => [
                        'id' => $contact->id,
                        'type' => $contact->type,
                        'name' => $contact->name,
                        'email' => $contact->email,
                        'phone' => $contact->phone,
                        'address' => $contact->address,
                        'city' => $contact->city,
                        'state' => $contact->state,
                        'zip_code' => $contact->zip_code,
                        'country' => $contact->country,
                        'updated_at' => optional($contact->updated_at)->toIso8601String(),
                    ],
                ],
            ]);
        } catch (GuzzleException $e) {
            report($e);
        }
    }

    private function shouldSync(Contact $contact): bool
    {
        return (bool) env('GRITCHI_PORTAL_WEBHOOK_URL')
            && $contact->type === Contact::CUSTOMER_TYPE
            && ! empty($contact->email);
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
