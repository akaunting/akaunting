<?php

namespace Tests\Feature\Common;

use App\Jobs\Banking\CreateTransaction;
use App\Models\Banking\Transaction;
use App\Models\Common\Contact;
use App\Models\Common\Item;
use Tests\Feature\FeatureTestCase;

/**
 * Covers App\Utilities\SearchStringColumns: a negated search string term also keeps the rows whose column is empty,
 * while "not column:NULL" still asks for the rows that have a value.
 */
class SearchStringColumnsTest extends FeatureTestCase
{
    public function testItShouldKeepEmptyColumnsUnderANegatedTerm()
    {
        $this->loginAs();

        $alpha = Contact::factory()->customer()->enabled()->create();
        $beta = Contact::factory()->customer()->enabled()->create();

        $ids = [];

        foreach (['alpha' => $alpha->id, 'beta' => $beta->id, 'none' => null] as $name => $contact_id) {
            $ids[$name] = $this->dispatch(new CreateTransaction(Transaction::factory()->income()->raw([
                'contact_id' => $contact_id,
                'amount' => 10,
            ])))->id;
        }

        $found = fn (string $search) => Transaction::query()
            ->usingSearchString($search)
            ->whereIn('id', $ids)
            ->pluck('id')
            ->sort()
            ->values()
            ->all();

        $this->assertSame([$ids['alpha']], $found('contact_id:' . $alpha->id));
        $this->assertSame([$ids['beta'], $ids['none']], $found('not contact_id:' . $alpha->id));
        $this->assertSame([$ids['none']], $found('not contact_id:' . $alpha->id . ',' . $beta->id));
        $this->assertSame([$ids['alpha'], $ids['none']], $found('contact_id:' . $alpha->id . ' or not contact_id:' . $beta->id));

        // The item picker asks for the items that have a price with "not sale_price:NULL"
        $priced = Item::factory()->enabled()->create(['sale_price' => 10]);
        $unpriced = Item::factory()->enabled()->create(['sale_price' => null]);

        $items = fn (string $search) => Item::query()
            ->usingSearchString($search)
            ->whereIn('id', [$priced->id, $unpriced->id])
            ->pluck('id')
            ->all();

        $this->assertSame([$priced->id], $items('not sale_price:NULL'));
        $this->assertSame([$unpriced->id], $items('sale_price:NULL'));
    }
}
