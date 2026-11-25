<?php

namespace Tests\Feature\Common;

use App\Jobs\Common\CreateItem;
use App\Models\Common\Item;
use App\Jobs\Document\CreateDocument;
use App\Models\Document\Document;
use Tests\Feature\FeatureTestCase;

class SameSiteTest extends FeatureTestCase
{
    public function testItShouldNotChangeDataFromALinkOnAnotherSite()
    {
        $item = $this->createItem();
        $invoice = $this->dispatch(new CreateDocument(Document::factory()->invoice()->sent()->items()->raw()));

        $this->loginAs()->withExceptionHandling();

        $this->withHeaders(['Sec-Fetch-Site' => 'cross-site'])
            ->get(route('items.disable', $item->id))
            ->assertForbidden();

        $this->withHeaders(['Sec-Fetch-Site' => 'cross-site'])
            ->get(route('invoices.cancelled', $invoice->id))
            ->assertForbidden();

        // Older browsers send the Referer only
        $this->withHeaders(['Sec-Fetch-Site' => '', 'Referer' => 'https://another.site/page'])
            ->get(route('items.disable', $item->id))
            ->assertForbidden();

        $this->withHeaders(['Sec-Fetch-Site' => 'cross-site', 'Referer' => ''])
            ->get(route('logout'))
            ->assertForbidden();

        $this->assertTrue((bool) $item->fresh()->enabled);
        $this->assertNotSame('cancelled', $invoice->fresh()->status);
        $this->assertAuthenticated();
    }

    public function testItShouldStillChangeDataFromTheSameSite()
    {
        $item = $this->createItem();

        $this->loginAs()
            ->withHeaders(['Sec-Fetch-Site' => 'same-origin'])
            ->get(route('items.disable', $item->id))
            ->assertOk();

        $this->assertFalse((bool) $item->fresh()->enabled);

        $this->withHeaders(['Sec-Fetch-Site' => '', 'Referer' => url('/')])
            ->get(route('items.enable', $item->id))
            ->assertOk();

        $this->assertTrue((bool) $item->fresh()->enabled);

        // A bookmark or an address typed in sends neither
        $this->withHeaders(['Sec-Fetch-Site' => '', 'Referer' => ''])
            ->get(route('items.disable', $item->id))
            ->assertOk();

        $this->assertFalse((bool) $item->fresh()->enabled);
    }

    public function testItShouldStillOpenPagesFromALinkOnAnotherSite()
    {
        $invoice = $this->dispatch(new CreateDocument(Document::factory()->invoice()->sent()->items()->raw()));

        $this->loginAs()
            ->withHeaders(['Sec-Fetch-Site' => 'cross-site'])
            ->get(route('invoices.show', $invoice->id))
            ->assertOk();
    }

    protected function createItem(): Item
    {
        return $this->dispatch(new CreateItem(Item::factory()->enabled()->raw(['enabled' => 1])));
    }
}
