<?php

namespace Tests\Feature\Document;

use App\Events\Document\PaymentReceived;
use App\Jobs\Document\CreateDocument;
use App\Listeners\Document\SendDocumentPaymentNotification;
use App\Models\Banking\Transaction;
use App\Models\Document\Document;
use App\Notifications\Portal\PaymentReceived as PaymentReceivedNotification;
use Illuminate\Support\Facades\Notification;
use Tests\Feature\FeatureTestCase;

class DocumentPaymentNotificationTest extends FeatureTestCase
{
    public function testItNotifiesWithTheCoreClassByDefault(): void
    {
        Notification::fake();

        $this->notifyPayment();

        Notification::assertSentTo($this->user, PaymentReceivedNotification::class, function ($notification) {
            return get_class($notification) === PaymentReceivedNotification::class;
        });
    }

    public function testItNotifiesWithTheClassOfTheDocumentType(): void
    {
        Notification::fake();

        config(['type.document.invoice.notification.payment_class' => ModulePaymentReceived::class]);

        $this->notifyPayment();

        Notification::assertSentTo($this->user, ModulePaymentReceived::class, function ($notification) {
            return $notification->template->alias === 'invoice_payment_admin';
        });

        Notification::assertNotSentTo($this->user, PaymentReceivedNotification::class, function ($notification) {
            return get_class($notification) === PaymentReceivedNotification::class;
        });
    }

    /**
     * A class that is missing or is no payment notification falls back to core's, so the payment is still notified.
     */
    public function testItFallsBackToTheCoreClassWhenTheClassOfTheDocumentTypeCannotBeUsed(): void
    {
        foreach (['Modules\Missing\Notifications\PaymentReceived', \stdClass::class, ''] as $class) {
            Notification::fake();

            config(['type.document.invoice.notification.payment_class' => $class]);

            $this->notifyPayment();

            Notification::assertSentTo($this->user, PaymentReceivedNotification::class, function ($notification) {
                return get_class($notification) === PaymentReceivedNotification::class;
            });
        }
    }

    protected function notifyPayment(): void
    {
        $invoice = $this->dispatch(new CreateDocument(
            Document::factory()->invoice()->items()->sent()->raw()
        ));

        Transaction::factory()->income()->create([
            'document_id' => $invoice->id,
            'contact_id' => $invoice->contact_id,
            'amount' => $invoice->amount,
            'currency_code' => $invoice->currency_code,
            'currency_rate' => $invoice->currency_rate,
        ]);

        (new SendDocumentPaymentNotification)->handle(new PaymentReceived($invoice->fresh(), ['type' => 'income']));
    }
}

class ModulePaymentReceived extends PaymentReceivedNotification
{
}
