<?php

namespace Tests\Feature\Common;

use App\Imports\Common\Sheets\Items as ItemsImport;
use App\Jobs\Banking\CreateTransfer;
use App\Jobs\Common\CreateItem;
use App\Models\Banking\Transfer;
use App\Models\Document\Document;
use App\Models\Setting\Tax;
use Tests\Feature\FeatureTestCase;

class OutputEscapingTest extends FeatureTestCase
{
    protected string $payload = 'X"><svg/onload=alert(1)>';

    public function testItShouldEscapeHtml()
    {
        $this->assertSame('X&quot;&gt;&lt;svg/onload=alert(1)&gt;', escape_html($this->payload));
        $this->assertSame('Smith &amp; Co', escape_html('Smith & Co'));

        // An already escaped value is kept as it is when asked
        $this->assertSame('Smith &amp; Co', escape_html('Smith &amp; Co', false));
        $this->assertSame('', escape_html(null));
    }

    public function testItShouldEscapeTheNameInTheMessageOfADisabledTax()
    {
        $tax = Tax::factory()->enabled()->create(['name' => $this->payload]);

        $this->dispatch(new CreateItem(array_merge(\App\Models\Common\Item::factory()->enabled()->raw(), ['tax_ids' => [$tax->id]])));

        $message = $this->loginAs()
            ->patch(route('taxes.update', $tax->id), [
                'name' => 'Safe name',
                'rate' => $tax->rate,
                'type' => $tax->type,
                'enabled' => 0,
            ])
            ->assertOk()
            ->json('message');

        $this->assertStringContainsString(escape_html($this->payload), $message);
        $this->assertStringNotContainsString('<svg', $message);
    }

    public function testItShouldOnlySaveADayCountAsThePaymentTerms()
    {
        $this->loginAs()
            ->withExceptionHandling()
            ->patchJson(route('settings.invoice.update'), [
                '_prefix' => 'invoice',
                'payment_terms' => 'form.constructor.constructor(1)()',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('payment_terms');

        // A value saved before is shown as a number of days
        setting(['invoice.payment_terms' => 'form.constructor.constructor(1)()']);
        setting()->save();

        $this->loginAs()
            ->get(route('settings.invoice.edit'))
            ->assertOk()
            ->assertDontSee('form.constructor.constructor', false);
    }

    public function testItShouldOfferTheDateFormatsOfTheConfig()
    {
        $response = $this->loginAs()
            ->get(route('settings.localisation.edit'))
            ->assertOk();

        // The select gets its options as JSON, each format with a sample date
        foreach (['31 Dec 2017', '31 December 2017', '31 12 2017', '12 31 2017', '2017 12 31'] as $sample) {
            $response->assertSee($sample, false);
        }

        $response->assertSee(trans('settings.localisation.date.dash'), false);
    }

    public function testItShouldOnlySaveAnOfferedDateFormat()
    {
        $this->loginAs()
            ->withExceptionHandling()
            ->patchJson(route('settings.localisation.update'), [
                '_prefix' => 'localisation',
                'date_format' => '\<\i\m\g \s\r\c\=x\>',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('date_format');

        // A format saved before falls back to the default one
        setting(['localisation.date_format' => '\<\i\m\g\>']);
        setting()->save();

        $this->loginAs();

        $this->assertSame('d M Y', company_date_format());
    }

    public function testItShouldEscapeTheStatusInTheHiddenInputOfTheEditForm()
    {
        $invoice = Document::factory()->invoice()->create();
        $invoice->forceFill(['status' => $this->payload])->save();

        $this->loginAs()
            ->get(route('invoices.edit', $invoice->id))
            ->assertOk()
            ->assertDontSee('<svg/onload', false);
    }

    public function testItShouldStripTheMarkupFromAnImportedName()
    {
        $this->loginAs();

        $row = (new ItemsImport)->map(['name' => 'Tool<img src=x onerror=alert(1)>', 'type' => 'product']);

        $this->assertSame('Tool', $row['name']);
    }

    public function testItShouldEscapeTheAccountNamesOfATransfer()
    {
        $transfer = $this->dispatch(new CreateTransfer(Transfer::factory()->raw()));
        $transfer->expense_account->forceFill(['name' => $this->payload])->save();

        $this->loginAs()
            ->get(route('transfers.show', $transfer->id))
            ->assertOk()
            ->assertDontSee('<svg/onload', false)
            ->assertSee(escape_html($this->payload), false);
    }
}
