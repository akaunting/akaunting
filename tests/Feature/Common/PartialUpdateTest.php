<?php

namespace Tests\Feature\Common;

use App\Jobs\Auth\CreateUser;
use App\Jobs\Banking\CreateTransaction;
use App\Jobs\Banking\MatchBankingDocumentTransaction;
use App\Jobs\Banking\UpdateTransaction;
use App\Jobs\Common\CreateContact;
use App\Jobs\Common\CreateItem;
use App\Jobs\Common\UpdateContact;
use App\Jobs\Common\UpdateItem;
use App\Jobs\Document\CreateDocument;
use App\Jobs\Document\UpdateDocument;
use App\Models\Auth\User;
use App\Models\Banking\Transaction;
use App\Models\Common\Contact;
use App\Models\Common\Item;
use App\Models\Document\Document;
use App\Models\Setting\Tax;
use App\Traits\Uploads;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Tests\Feature\FeatureTestCase;

/**
 * The update jobs are written for the forms and the API, which send the whole record. Enabling,
 * disabling, the bulk actions and the other flows send only what they change, and the jobs took
 * what they left out as removed: the taxes, the contact persons, the pictures and attachments,
 * and the type of a transaction. They keep it now, while saving the whole record still removes
 * what it no longer lists.
 */
class PartialUpdateTest extends FeatureTestCase
{
    use Uploads;

    public function testItShouldKeepTheTaxesAndPictureOfAnItemWhenDisablingAndEnablingIt()
    {
        $item = $this->createItem();

        $this->loginAs()->get(route('items.disable', $item->id))->assertOk()->assertJson(['success' => true]);

        $this->assertFalse($item->fresh()->enabled);
        $this->assertItemKept($item);

        $this->loginAs()->get(route('items.enable', $item->id))->assertOk()->assertJson(['success' => true]);

        $this->assertTrue($item->fresh()->enabled);
        $this->assertItemKept($item);
    }

    public function testItShouldKeepTheTaxesAndPictureOfAnItemInBulkEdit()
    {
        $item = $this->createItem();
        $type = ($item->type == 'product') ? 'service' : 'product';

        $this->bulkAction('common', 'items', 'update', $item, ['type' => $type]);

        $this->assertSame($type, $item->fresh()->type);
        $this->assertItemKept($item);
    }

    public function testItShouldReplaceTheTaxesOfAnItemSavedAsAWhole()
    {
        $item = $this->createItem();

        $request = Item::factory()->enabled()->raw(['name' => $item->name]);

        $this->dispatch(new UpdateItem($item, $request));

        $this->assertSame(0, $item->taxes()->count());
        $this->assertFalse($item->fresh()->hasMedia('picture'));
    }

    public function testItShouldKeepThePersonsAndLogoOfACustomerWhenDisablingAndEnablingIt()
    {
        $customer = $this->createCustomer();

        $this->loginAs()->get(route('customers.disable', $customer->id))->assertOk()->assertJson(['success' => true]);

        $this->assertEquals(0, $customer->fresh()->enabled);
        $this->assertCustomerKept($customer);

        $this->loginAs()->get(route('customers.enable', $customer->id))->assertOk()->assertJson(['success' => true]);

        $this->assertEquals(1, $customer->fresh()->enabled);
        $this->assertCustomerKept($customer);
    }

    public function testItShouldKeepThePersonsAndLogoOfACustomerInBulkDisableAndEdit()
    {
        $customer = $this->createCustomer();

        $this->bulkAction('sales', 'customers', 'disable', $customer);

        $this->assertEquals(0, $customer->fresh()->enabled);
        $this->assertCustomerKept($customer);

        $this->bulkAction('sales', 'customers', 'update', $customer, ['country' => 'TR']);

        $this->assertSame('TR', $customer->fresh()->country);
        $this->assertCustomerKept($customer);
    }

    public function testItShouldReplaceThePersonsOfACustomerSavedAsAWhole()
    {
        $customer = $this->createCustomer();

        $request = Contact::factory()->customer()->enabled()->raw(['name' => $customer->name, 'email' => $customer->email]);

        $this->dispatch(new UpdateContact($customer, $request));

        $this->assertSame(0, $customer->contact_persons()->count());
        $this->assertFalse($customer->fresh()->hasMedia('logo'));
    }

    public function testItShouldKeepThePictureOfAUserWhenDisablingItAndInBulkEdit()
    {
        $user = $this->createUser();

        $this->loginAs()->get(route('users.disable', $user->id))->assertOk()->assertJson(['success' => true]);

        $this->assertFalse((bool) $user->fresh()->enabled);
        $this->assertTrue($user->fresh()->hasMedia('picture'));

        $this->bulkAction('auth', 'users', 'update', $user, ['locale' => 'en-GB']);

        $this->assertTrue($user->fresh()->hasMedia('picture'));
    }

    public function testItShouldKeepTheTaxesAndAttachmentOfATransactionInBulkEdit()
    {
        $transaction = $this->createTransaction(Transaction::factory()->income());

        $this->bulkAction('banking', 'transactions', 'update', $transaction, ['payment_method' => 'offline-payments.bank_transfer.2']);

        $this->assertSame('offline-payments.bank_transfer.2', $transaction->fresh()->payment_method);
        $this->assertTransactionKept($transaction, Transaction::INCOME_TYPE);
    }

    public function testItShouldKeepTheTypeTaxesAndAttachmentOfAnEndedRecurringExpense()
    {
        $transaction = $this->createTransaction(Transaction::factory()->expense()->recurring());

        $this->loginAs()->get(route('recurring-transactions.end', $transaction->id))->assertRedirect();

        $this->assertTransactionKept($transaction, Transaction::EXPENSE_RECURRING_TYPE);
    }

    public function testItShouldKeepTheAttachmentOfATransactionMatchedToAnInvoice()
    {
        $invoice = Document::factory()->invoice()->sent()->create()->refresh();

        $transaction = $this->createTransaction(Transaction::factory()->income(), [
            'amount' => $invoice->amount,
            'currency_code' => $invoice->currency_code,
            'currency_rate' => $invoice->currency_rate,
            'contact_id' => $invoice->contact_id,
        ]);

        $this->dispatch(new MatchBankingDocumentTransaction($invoice, $transaction->fresh()));

        $this->assertSame($invoice->id, $transaction->fresh()->document_id);
        $this->assertTransactionKept($transaction, Transaction::INCOME_TYPE);
    }

    public function testItShouldReplaceTheTaxesAndAttachmentOfATransactionSavedAsAWhole()
    {
        $transaction = $this->createTransaction(Transaction::factory()->income());

        $request = Transaction::factory()->income()->raw(['amount' => $transaction->amount]);

        $this->dispatch(new UpdateTransaction($transaction->fresh(), $request));

        $this->assertSame(0, $transaction->taxes()->count());
        $this->assertFalse($transaction->fresh()->hasMedia('attachment'));
    }

    public function testItShouldKeepTheAttachmentOfAnEndedRecurringInvoice()
    {
        $invoice = $this->createRecurringInvoice();

        $this->loginAs()->get(route('recurring-invoices.end', $invoice->id))->assertRedirect();

        $this->assertTrue($invoice->fresh()->hasMedia('attachment'));
    }

    public function testItShouldRemoveTheAttachmentOfAnInvoiceSavedAsAWholeWithoutIt()
    {
        $invoice = $this->createRecurringInvoice();

        $request = Document::factory()->invoice()->items()->recurring()->raw();

        $this->dispatch(new UpdateDocument($invoice->fresh(), $request));

        $this->assertFalse($invoice->fresh()->hasMedia('attachment'));
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function bulkAction(string $group, string $type, string $handle, $model, array $data = []): void
    {
        $this->loginAs()
            ->post(route('bulk-actions.action', ['group' => $group, 'type' => $type]), ['handle' => $handle, 'selected' => [$model->id]] + $data)
            ->assertOk();
    }

    private function createItem(): Item
    {
        $request = Item::factory()->enabled()->raw();
        $request['tax_ids'] = [Tax::factory()->enabled()->create()->id];

        $item = $this->dispatch(new CreateItem($request));
        $item->attachMedia($this->getMedia(UploadedFile::fake()->image('picture.png', 10, 10), 'items'), 'picture');

        $this->assertItemKept($item);

        return $item;
    }

    private function assertItemKept(Item $item): void
    {
        $this->assertSame(1, $item->taxes()->count());
        $this->assertTrue($item->fresh()->hasMedia('picture'));
    }

    private function createCustomer(): Contact
    {
        $request = Contact::factory()->customer()->enabled()->raw();
        $request['contact_persons'] = [['name' => 'Person', 'email' => 'person@example.com', 'phone' => '1']];

        $customer = $this->dispatch(new CreateContact($request));
        $customer->attachMedia($this->getMedia(UploadedFile::fake()->image('logo.png', 10, 10), 'customers'), 'logo');

        $this->assertCustomerKept($customer);

        return $customer;
    }

    private function assertCustomerKept(Contact $customer): void
    {
        $this->assertSame(1, $customer->contact_persons()->count());
        $this->assertTrue($customer->fresh()->hasMedia('logo'));
    }

    private function createUser(): User
    {
        $request = User::factory()->enabled()->raw();
        $request['email'] = Str::lower(Str::random(12)) . '@example.com';

        $user = $this->dispatch(new CreateUser($request));
        $user->attachMedia($this->getMedia(UploadedFile::fake()->image('picture.png', 10, 10), 'users'), 'picture');

        $this->assertTrue($user->fresh()->hasMedia('picture'));

        return $user;
    }

    private function createTransaction($factory, array $attributes = []): Transaction
    {
        $request = $factory->raw($attributes);
        $request['tax_ids'] = [Tax::factory()->enabled()->create(['type' => 'normal', 'rate' => 10])->id];

        $this->loginAs();

        $transaction = $this->dispatch(new CreateTransaction($request));
        $transaction->attachMedia($this->getMedia(UploadedFile::fake()->image('attachment.png', 10, 10), 'transactions'), 'attachment');

        $this->assertTransactionKept($transaction, $transaction->type);

        return $transaction;
    }

    private function assertTransactionKept(Transaction $transaction, string $type): void
    {
        $transaction = Transaction::withoutGlobalScopes()->find($transaction->id);

        $this->assertSame($type, $transaction->type);
        $this->assertSame(1, $transaction->taxes()->count());
        $this->assertTrue($transaction->hasMedia('attachment'));
    }

    private function createRecurringInvoice(): Document
    {
        $invoice = $this->dispatch(new CreateDocument(Document::factory()->invoice()->items()->recurring()->raw()));
        $invoice->attachMedia($this->getMedia(UploadedFile::fake()->image('attachment.png', 10, 10), 'invoices'), 'attachment');

        $this->assertTrue($invoice->fresh()->hasMedia('attachment'));

        return $invoice;
    }
}
