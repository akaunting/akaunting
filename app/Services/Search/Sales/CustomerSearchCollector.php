<?php

namespace App\Services\Search\Sales;

use App\Models\Common\Contact;
use App\Services\Search\SearchCollectorInterface;

class CustomerSearchCollector implements SearchCollectorInterface
{
    private const COLOR = '#328aef';

    public function collectByKeyword(string $keyword): array
    {
        $contacts = Contact::customer()
            ->enabled()
            ->usingSearchString($keyword)
            ->get();

        return array_map(static function (Contact $contact) {
            return [
                'id' => $contact->id,
                'name' => $contact->name,
                'type' => trans_choice('general.customers', 1),
                'color' => self::COLOR,
                'href' => route('customers.show', $contact->id)
            ];
        }, iterator_to_array($contacts));
    }
}
