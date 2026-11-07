<?php

namespace Tests\Feature\Banking;

use App\Jobs\Banking\CreateBankingDocumentTransaction;
use App\Jobs\Document\CreateDocument;
use App\Models\Banking\Account;
use App\Models\Banking\Transaction;
use App\Models\Document\Document;
use App\Models\Setting\Category;
use App\Models\Setting\Currency;
use Tests\Feature\FeatureTestCase;

/**
 * What the bulk edit of transactions does to the currency of the transactions.
 *
 * When no account is picked, the modal still sends the currency of the account
 * of one of the selected transactions and a rate of 1.
 */
class TransactionBulkEditTest extends FeatureTestCase
{
    /**
     * Reports convert every transaction at its own rate, so a bulk edit that does not
     * change the account has to leave the currency and the rate as they are.
     */
    public function testItKeepsTheCurrencyAndRateWhenTheAccountIsNotChanged(): void
    {
        $account = $this->createAccountInCurrency(0.85);

        $foreign = $this->createTransaction($account, 100);
        $default = $this->createTransaction(Account::find(setting('default.account')), 50);

        $category = Category::factory()->income()->enabled()->create();

        // What the modal sends when only the category is picked
        $this->editInBulk([$default, $foreign], [
            'category_id' => $category->id,
            'currency_code' => $account->currency_code,
            'currency_rate' => '1',
        ]);

        $foreign->refresh();
        $default->refresh();

        $this->assertEquals($category->id, $foreign->category_id);
        $this->assertEquals($category->id, $default->category_id);

        $this->assertSame($account->currency_code, $foreign->currency_code);
        $this->assertEquals(0.85, $foreign->currency_rate);

        $this->assertSame(default_currency(), $default->currency_code);
        $this->assertEquals(1, $default->currency_rate);

        // 100 at 0.85 in the company currency
        $this->assertEquals(117.65, round($foreign->getAmountConvertedToDefault(), 2));
    }

    /**
     * A transaction is in the currency of its account, so a new account brings its
     * currency and the rate of it, also when the request carries other values.
     */
    public function testItTakesTheCurrencyAndRateOfThePickedAccount(): void
    {
        $account = $this->createAccountInCurrency(0.85);

        $transaction = $this->createTransaction(Account::find(setting('default.account')), 50);

        $this->editInBulk([$transaction], [
            'account_id' => $account->id,
            'currency_code' => default_currency(),
            'currency_rate' => '1',
        ]);

        $transaction->refresh();

        $this->assertEquals($account->id, $transaction->account_id);
        $this->assertSame($account->currency_code, $transaction->currency_code);
        $this->assertEquals(0.85, $transaction->currency_rate);

        // The amount is not converted, the same as on the edit form
        $this->assertEquals(50, $transaction->amount);
    }

    /**
     * A transaction moved to another account in its own currency keeps the rate it was
     * entered with, also when the rate of the currency has changed since then.
     */
    public function testItKeepsTheRateWhenTheAccountIsInTheSameCurrency(): void
    {
        $account = $this->createAccountInCurrency(0.90);
        $other = Account::factory()->enabled()->create(['currency_code' => $account->currency_code]);

        $transaction = $this->createTransaction($account, 100);

        $account->currency->update(['rate' => 0.85]);

        $this->editInBulk([$transaction], [
            'account_id' => $other->id,
            'currency_code' => $account->currency_code,
            'currency_rate' => '1',
        ]);

        $transaction->refresh();

        $this->assertEquals($other->id, $transaction->account_id);
        $this->assertSame($account->currency_code, $transaction->currency_code);
        $this->assertEquals(0.90, $transaction->currency_rate);
    }

    /**
     * What a document counts as paid comes from the amounts of its payments, so a payment
     * is not moved to an account in another currency. The other transactions still are.
     */
    public function testItDoesNotMoveADocumentPaymentToAnAccountInAnotherCurrency(): void
    {
        $account = $this->createAccountInCurrency(0.85);
        $default = Account::find(setting('default.account'));

        $invoice = $this->dispatch(new CreateDocument(Document::factory()->invoice()->items()->raw([
            'currency_code' => default_currency(),
            'currency_rate' => 1,
            'status' => 'sent',
        ])));

        $payment = $this->dispatch(new CreateBankingDocumentTransaction($invoice, [
            'amount' => round($invoice->amount / 2, 2),
            'account_id' => $default->id,
            'currency_code' => default_currency(),
            'currency_rate' => 1,
            'type' => 'income',
        ]));

        $invoice->refresh();
        $paid = $invoice->paid;
        $amount = $payment->amount;

        $transaction = $this->createTransaction($default, 50);

        $this->editInBulk([$payment, $transaction], [
            'account_id' => $account->id,
            'currency_code' => default_currency(),
            'currency_rate' => '1',
        ]);

        $payment->refresh();
        $transaction->refresh();
        $invoice->refresh();

        $this->assertEquals($default->id, $payment->account_id);
        $this->assertSame(default_currency(), $payment->currency_code);
        $this->assertEquals(1, $payment->currency_rate);
        $this->assertEquals($amount, $payment->amount);
        $this->assertEquals($paid, $invoice->paid);
        $this->assertSame('partial', $invoice->status);

        // The skipped payment is named in an error, the other transaction gives none
        $errors = session('flash_notification')->where('level', 'danger');

        $this->assertCount(1, $errors);
        $this->assertSame(trans('messages.warning.disable_code', [
            'name' => $payment->number,
            'text' => strtolower(trans_choice('general.invoices', 1)),
        ]), $errors->first()->message);

        $this->assertEquals($account->id, $transaction->account_id);
        $this->assertSame($account->currency_code, $transaction->currency_code);
        $this->assertEquals(0.85, $transaction->currency_rate);
    }

    private function createAccountInCurrency(float $rate): Account
    {
        $currency = Currency::factory()->create(['rate' => $rate, 'enabled' => 1]);

        $this->assertNotEquals(default_currency(), $currency->code);

        return Account::factory()->enabled()->create(['currency_code' => $currency->code]);
    }

    private function createTransaction(Account $account, float $amount): Transaction
    {
        return Transaction::factory()->income()->create([
            'account_id' => $account->id,
            'currency_code' => $account->currency_code,
            'currency_rate' => $account->currency->rate,
            'amount' => $amount,
        ]);
    }

    private function editInBulk(array $transactions, array $picked): void
    {
        $this->loginAs()
            ->post(route('bulk-actions.action', ['group' => 'banking', 'type' => 'transactions']), array_merge([
                'handle' => 'update',
                'selected' => array_map(fn ($transaction) => $transaction->id, $transactions),
                'payment_method' => '',
                'account_id' => '',
                'category_id' => '',
                'contact_id' => '',
            ], $picked))
            ->assertStatus(200);
    }
}
