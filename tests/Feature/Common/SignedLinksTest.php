<?php

namespace Tests\Feature\Common;

use App\Jobs\Document\CreateDocument;
use App\Models\Document\Document;
use Tests\Feature\FeatureTestCase;

class SignedLinksTest extends FeatureTestCase
{
    public function testItShouldNotExpireTheSignedLinksByDefault()
    {
        $invoice = $this->createInvoice();

        $url = signed_route('signed.invoices.show', ['company_id' => $invoice->company_id, 'invoice' => $invoice->id]);

        $this->assertStringNotContainsString('expires=', $url);

        $this->travel(365)->days();

        $this->get($url)->assertOk();
    }

    public function testItShouldExpireTheSignedLinksAfterTheDaysSet()
    {
        config(['app.signed_links.expire' => 30]);

        $invoice = $this->createInvoice();

        $url = signed_route('signed.invoices.show', ['company_id' => $invoice->company_id, 'invoice' => $invoice->id]);

        $this->assertStringContainsString('expires=', $url);

        $this->get($url)->assertOk();

        $this->travel(29)->days();

        $this->get($url)->assertOk();

        $this->travel(2)->days();

        $this->withExceptionHandling()->get($url)->assertForbidden();
    }

    public function testItShouldNotAcceptAnotherExpiry()
    {
        config(['app.signed_links.expire' => 1]);

        $invoice = $this->createInvoice();

        $url = signed_route('signed.invoices.show', ['company_id' => $invoice->company_id, 'invoice' => $invoice->id]);

        $this->withExceptionHandling()
            ->get(preg_replace('/expires=\d+/', 'expires=' . now()->addYears(10)->getTimestamp(), $url))
            ->assertForbidden();
    }

    protected function createInvoice()
    {
        return $this->dispatch(new CreateDocument(Document::factory()->invoice()->sent()->items()->raw()));
    }
}
