<?php

namespace Tests\Feature\Banking;

use App\Models\Banking\Account;
use App\Models\Banking\Transaction;
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
