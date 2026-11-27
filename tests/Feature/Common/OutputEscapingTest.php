<?php

namespace Tests\Feature\Common;

use App\Imports\Common\Sheets\Items as ItemsImport;
use App\Jobs\Banking\CreateBankingDocumentTransaction;
use App\Jobs\Banking\CreateTransfer;
use App\Jobs\Common\CreateContact;
use App\Jobs\Common\CreateItem;
use App\Jobs\Document\CreateDocument;
use App\Models\Common\Contact;
use App\Models\Banking\Transfer;
use App\Models\Document\Document;
use App\Models\Setting\Tax;
use App\Notifications\Sale\Invoice as InvoiceNotification;
use App\Traits\Charts;
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

    public function testItShouldKeepTheVueExpressionsOfAValueAsText()
    {
        $this->assertSame('{&#8203;{ 7*7 }}', escape_html('{{ 7*7 }}'));
        $this->assertSame('{&#8203;{ 7*7 }}', escape_vue('{{ 7*7 }}'));

        // The HTML the views build keeps its own Vue expressions
        $this->assertSame('{{ form.name }}', escape_vue(new \Illuminate\Support\HtmlString('{{ form.name }}')));

        // The JSON given to the Vue components keeps the braces of its nested objects
        $this->assertSame(e(json_encode(['a' => ['b' => 1]])), escape_vue(json_encode(['a' => ['b' => 1]])));

        $this->dispatch(new CreateContact(array_merge(Contact::factory()->customer()->enabled()->raw(), ['name' => '{{ 7*7 }}'])));

        $response = $this->loginAs()
            ->get(route('customers.index'))
            ->assertOk()
            ->assertSee('{&#8203;{ 7*7 }}', false);

        $this->assertStringNotContainsString('{{ 7*7 }}', $response->getContent());
    }

    public function testItShouldEscapeTheNumberOfAChildInvoice()
    {
        $recurring = $this->dispatch(new CreateDocument(Document::factory()->invoice()->items()->recurring()->raw()));

        Document::factory()->invoice()->create([
            'parent_id' => $recurring->id,
            'document_number' => $this->payload,
        ]);

        $this->loginAs()
            ->get(route('recurring-invoices.show', $recurring->id))
            ->assertOk()
            ->assertDontSee('<svg/onload', false)
            ->assertSee(escape_html($this->payload), false);
    }

    public function testItShouldNotRunTheUpdateOfAVersionThatIsNotOne()
    {
        $this->loginAs()
            ->withExceptionHandling()
            ->get(url($this->company->id . '/install/updates/run/core/' . rawurlencode('{{ 7*7 }}')))
            ->assertNotFound();
    }

    public function testItShouldGiveTheEditorItsValueAsAString()
    {
        $html = (string) $this->blade('<x-form.group.editor name="body" :value="$value" />', [
            'value' => 'Dear ${alert(1)} `x`',
        ]);

        // Not a template literal, where ${} would run
        $this->assertStringNotContainsString(':value="`', $html);
        $this->assertStringContainsString(":value=\"'Dear \${alert(1)} `x`'\"", $html);
    }

    public function testItShouldEscapeTheLabelsOfADonutChart()
    {
        $chart = new class {
            use Charts;
        };

        $chart->addMoneyToDonutChart('#000000', 10, $this->payload);

        $this->assertStringContainsString(escape_html($this->payload), $chart->donut['labels'][0]);
        $this->assertStringNotContainsString('<svg', $chart->donut['labels'][0]);
    }

    public function testItShouldEscapeThePaymentMethodOfAPaymentOnTheSignedInvoice()
    {
        $invoice = $this->dispatch(new CreateDocument(Document::factory()->invoice()->sent()->items()->raw()));

        $this->dispatch(new CreateBankingDocumentTransaction($invoice, [
            'type' => 'income',
            'payment_method' => 'offline-payments.cash.1',
        ]));

        // The customer sees the name of the payment method
        setting(['offline-payments.methods' => json_encode([[
            'code' => 'offline-payments.cash.1',
            'name' => $this->payload,
            'customer' => '1',
            'order' => '1',
            'description' => null,
        ]])])->save();

        \App\Utilities\Modules::clearPaymentMethodsCache();

        $this->get(signed_route('signed.invoices.show', ['company_id' => $invoice->company_id, 'invoice' => $invoice->id]))
            ->assertOk()
            ->assertDontSee('<svg/onload', false)
            ->assertSee(escape_html($this->payload), false);
    }

    public function testItShouldEscapeTheValuesInTheBodyOfAnEmail()
    {
        $this->loginAs();

        $invoice = $this->dispatch(new CreateDocument(Document::factory()->invoice()->sent()->items()->raw([
            'contact_name' => $this->payload,
        ])));

        $notification = new InvoiceNotification($invoice, 'invoice_new_customer');

        $this->assertStringNotContainsString('<svg/onload', $notification->getBody());
        $this->assertStringContainsString(escape_html($this->payload), $notification->getBody());

        // The subject is text, and a line break a value carries stays in the body
        $this->assertSame($invoice->document_number . ' invoice created', $notification->getSubject());
        $this->assertSame("A &amp; B<br />\nC", $notification->escapeTagValue(nl2br("A & B\nC")));
    }

    public function testItShouldEscapeTheTextOfAnAppReview()
    {
        $html = view('components.layouts.modules.reviews', [
            'reviews' => (object) ['data' => [(object) [
                'author' => 'Someone',
                'created_at' => '2026-01-01',
                'rating' => 5,
                'text' => $this->payload . "\nSecond line",
            ]]],
        ])->render();

        $this->assertStringNotContainsString('<svg/onload', $html);
        $this->assertStringContainsString(escape_html($this->payload) . "<br />\nSecond line", $html);
    }

    public function testItShouldShowTheTitleOfACallToActionAsText()
    {
        $html = view('components.layouts.modules.show.features', [
            'module' => (object) ['call_to_actions' => [(object) [
                'position' => 'left',
                'title' => $this->payload,
                'description' => 'Text',
                'thumb' => (object) ['path_string' => '', 'alt_attribute' => ''],
            ]]],
        ])->render();

        $this->assertStringNotContainsString('<svg/onload', $html);
        $this->assertStringContainsString(escape_html($this->payload), $html);
    }

    public function testItShouldEscapeTheErrorAndTheCompanyNameInTheInvalidEmailMail()
    {
        $this->loginAs();

        setting(['company.name' => $this->payload]);

        $html = (string) (new \App\Notifications\Email\InvalidEmail('a@b.c', 'customer', $this->payload))
            ->toMail(user())
            ->render();

        $this->assertStringNotContainsString('<svg/onload', $html);
        // The CSS inliner gives the tags a style and writes the quotes back
        $this->assertStringContainsString('X"&gt;&lt;svg/onload=alert(1)&gt;</i>', $html);
        $this->assertStringContainsString('<br> X"&gt;&lt;svg/onload=alert(1)&gt;', $html);
    }

    public function testItShouldQuoteTheAttributesOfATableAction()
    {
        $actions = new \App\View\Components\Table\Actions(false, [[
            'type' => 'button',
            'title' => 'Test',
            'icon' => 'edit',
            'url' => '#',
            'attributes' => [
                'id' => 'x onmouseover=alert(1)',
                'required' => true,
            ],
        ]]);

        $this->assertSame(' id="x onmouseover=alert(1)" required', $actions->actions[0]['attributes']);
    }
}
