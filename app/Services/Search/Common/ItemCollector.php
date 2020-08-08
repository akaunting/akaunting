<?php

namespace App\Services\Search\Common;

use App\Models\Common\Item;
use App\Services\Search\SearchCollectorInterface;

class ItemCollector implements SearchCollectorInterface
{
    private const COLOR = '#efad32';

    public function collectByKeyword(string $keyword): array
    {
        $items = Item::enabled()
            ->usingSearchString($keyword)
            ->get();

        return array_map(static function (Item $item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'type' => trans_choice('general.items', 1),
                'color' => self::COLOR,
                'href' => route('items.edit', $item->id),
            ];
        }, iterator_to_array($items));
    }
}

