<?php

namespace App\Services\Search\Purchases;

use App\Models\Common\Contact;
use App\Services\Search\SearchCollectorInterface;

class VendorCollector implements SearchCollectorInterface
{
    private const COLOR = '#efef32';

    public function collectByKeyword(string $keyword): array
    {
        $vendors = Contact::vendor()
            ->enabled()
            ->usingSearchString($keyword)
            ->get();

        return array_map(static function (Contact $vendor) {
            return [
                'id' => $vendor->id,
                'name' => $vendor->name,
                'type' => trans_choice('general.vendors', 1),
                'color' => self::COLOR,
                'href' => route('vendors.show', $vendor->id)
            ];
        }, iterator_to_array($vendors));
    }
}
