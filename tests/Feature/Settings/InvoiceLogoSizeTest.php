<?php

namespace Tests\Feature\Settings;

use App\Models\Document\Document;
use Tests\Feature\FeatureTestCase;

/**
 * The logo of documents and transactions is resized to the width and height in the invoice
 * settings. A 0 there made GD throw a ValueError, which the \Exception catch around the resize
 * let through, and the invoice page failed (Sentry CLOUD-PROD-LARAVEL-1V9F).
 */
class InvoiceLogoSizeTest extends FeatureTestCase
{
    public function testItShouldFallBackToTheDefaultForASizeThatIsNotAPositiveNumber()
    {
        $this->loginAs();

        setting(['invoice.logo_size_width' => '0', 'invoice.logo_size_height' => '']);

        $this->assertSame([128, 128], invoice_logo_size());

        setting(['invoice.logo_size_width' => '200', 'invoice.logo_size_height' => '-5']);

        $this->assertSame([200, 128], invoice_logo_size());
    }

    public function testItShouldShowTheInvoiceWithALogoSizeOfZero()
    {
        $this->loginAs();

        setting(['invoice.logo_size_width' => '0', 'invoice.logo_size_height' => '0']);
        setting()->save();

        $invoice = Document::factory()->invoice()->create();

        $this->get(route('invoices.show', $invoice->id))
            ->assertOk();
    }

    public function testItShouldNotSaveALogoSizeOfZero()
    {
        $this->withExceptionHandling()
            ->loginAs()
            ->patchJson(route('settings.invoice.update'), [
                '_prefix'           => 'invoice',
                'logo_size_width'   => '0',
                'logo_size_height'  => '128',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['logo_size_width']);
    }
}
