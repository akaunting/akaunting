<?php

namespace Tests\Feature;

use App\Models\Common\Contact;
use App\Models\Document\Document;
use App\Jobs\Document\CreateDocument;
use App\Jobs\Setting\CreateCurrency;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Tests\Feature\FeatureTestCase;

class PaymentTestCase extends FeatureTestCase
{
    public $alias;

    public $invoice_currency = null;

    public $setting_request;

    public $payment_request;

    public $customer = null;

    public $customer_user = null;

    public $invoice = null;

    public $is_portal = false;

    public function assertPaymentFromSigned()
    {
        $this->updateSetting();

        $this->createInvoice();

        $this->get($this->getShowUrl())
            ->assertOk()
            ->assertSeeText($this->setting_request['name']);

        $this->post($this->getConfirmUrl(), $this->payment_request)
            ->assertOk();

        $this->assertPayment();
    }

    public function assertPaymentFromPortal()
    {
        $this->is_portal = true;

        $this->updateSetting();

        $this->loginAsCustomer();

        $this->createInvoice();

        $this->loginAs($this->customer_user)
            ->get($this->getShowUrl())
            ->assertOk()
            ->assertSeeText($this->setting_request['name']);

        $this->loginAs($this->customer_user)
            ->post($this->getConfirmUrl(), $this->payment_request)
            ->assertOk();

        $this->assertPayment();
    }

    public function updateSetting()
    {
        if (in_array($this->invoice_currency, ['USD', 'EUR', 'GBP', 'TRY'])) {
            if ($this->invoice_currency != 'USD') {
                setting()->set('default.currency', $this->invoice_currency);
            }
        } elseif ($this->invoice_currency != null) {
            $this->dispatch(new CreateCurrency([
                'company_id'            => company_id(),
                'name'                  => config('money.currencies.' . $this->invoice_currency . '.name'),
                'code'                  => $this->invoice_currency,
                'rate'                  => config(['money.' . $this->invoice_currency . '.rate' => 1]),
                'enabled'               => 1,
                'symbol_first'          => config('money.currencies.' . $this->invoice_currency . '.symbol_first'),
                'decimal_mark'          => config('money.currencies.' . $this->invoice_currency . '.decimal_mark'),
                'thousands_separator'   => config('money.currencies.' . $this->invoice_currency . '.thousands_separator'),
                'default_currency'      => true,
            ]));
        }

        $module = module($this->alias);

        if (File::exists(base_path('modules/' . $module->getStudlyName() . '/Routes/admin.php'))) {
            $this->loginAs()
                ->post(route($this->alias . '.settings.update'), $this->setting_request)
                ->assertOk();
        } else {
            $this->loginAs()
                ->patch(route('settings.module.update', $this->alias), $this->setting_request)
                ->assertOk();
        }

        $this->assertFlashLevel('success');
    }

    public function getShowUrl()
    {
        return $this->is_portal
                ? route('portal.invoices.show', $this->invoice->id)
                : URL::signedRoute('signed.invoices.show', [$this->invoice->id]);
    }

    public function getConfirmUrl()
    {
        return $this->is_portal
                ? route('portal.' . $this->alias . '.invoices.confirm', $this->invoice->id)
                : URL::signedRoute('signed.' . $this->alias . '.invoices.confirm', [$this->invoice->id]);
    }

    public function assertPayment()
    {
        $this->assertFlashLevel('success');

        $invoice = Document::where('document_number', $this->invoice->document_number)->first();

        $this->assertEquals('paid', $invoice->status);

        $this->assertDatabaseHas('transactions', [
            'document_id'   => $invoice->id,
            'contact_id'    => $invoice->contact_id,
            'amount'        => $invoice->amount,
            'currency_code' => $invoice->currency_code,
            'currency_rate' => $invoice->currency_rate,
            'type'          => 'income',
        ]);
    }

    public function createInvoice()
    {
        $this->invoice = $this->dispatch(new CreateDocument($this->getInvoiceRequest()));
    }

    public function loginAsCustomer()
    {
        $this->customer = Contact::customer()->first();

        $this->customer_user = $this->customer->user;
    }

    public function getInvoiceRequest()
    {
        if ($this->is_portal) {
            return Document::factory()->invoice()->draft()->items()->raw([
                'contact_id' => $this->customer->id,
                'contact_name' => $this->customer->name,
            ]);
        }

        return Document::factory()->invoice()->draft()->items()->raw();
    }
}
