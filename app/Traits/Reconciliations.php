<?php

namespace App\Traits;

use App\Models\Banking\Reconciliation;
use App\Models\Banking\Transaction;
use App\Utilities\Date;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * The rules of a reconciliation, shared by its page, the API and the bulk actions. The page works the difference out
 * as the transactions are checked and lets a reconciliation be reconciled when it is 0, the others do it here.
 */
trait Reconciliations
{
    /**
     * The balance of the account before the period, the one the reconciliation starts from.
     *
     * @param  \App\Models\Banking\Account  $account
     * @param  mixed  $started_at
     * @return float
     */
    public function getOpeningBalance($account, $started_at)
    {
        $total = $account->opening_balance;

        foreach ($account->income_transactions()->whereDate('paid_at', '<', $started_at)->get() as $item) {
            $total += $item->amount;
        }

        foreach ($account->expense_transactions()->whereDate('paid_at', '<', $started_at)->get() as $item) {
            $total -= $item->amount;
        }

        return $total;
    }

    /**
     * The transactions a reconciliation can clear, the ones its page lists: of its account and in its period, but
     * not in the period of another reconciled reconciliation of the account, which cleared them
     * (Reconciliation::scopeReconciledOverlapping()).
     *
     * @param  int  $account_id
     * @param  mixed  $started_at
     * @param  mixed  $ended_at
     * @param  int|null  $except_id  the reconciliation itself
     * @return Builder
     */
    public function getReconcilableTransactions($account_id, $started_at, $ended_at, $except_id = null): Builder
    {
        $started_at = Date::parse($started_at)->startOfDay();
        $ended_at = Date::parse($ended_at)->endOfDay();

        $query = Transaction::where('account_id', $account_id)
            ->whereBetween('paid_at', [$started_at->toDateTimeString(), $ended_at->toDateTimeString()]);

        $others = Reconciliation::reconciledOverlapping($account_id, $started_at, $ended_at, $except_id)->get(['started_at', 'ended_at']);

        foreach ($others as $other) {
            $query->whereNotBetween('paid_at', [$other->started_at->toDateTimeString(), $other->ended_at->toDateTimeString()]);
        }

        return $query;
    }

    /**
     * The id of a transaction from its key in the transactions of a reconciliation, its type and id: "income_12" or
     * "expense_12" as the page posts them, or the type of the transaction, i.e. "expense-transfer_12", as a client
     * of the API may send it.
     *
     * @param  mixed  $key
     * @return int|null
     */
    public function getReconciliationTransactionId($key): ?int
    {
        return preg_match('/^.+_(\d+)$/', (string) $key, $matches) ? (int) $matches[1] : null;
    }

    /**
     * The ids of the transactions checked in the transactions of a reconciliation. The page posts a checked one as
     * true and one checked and then unchecked as "false", the API may send an amount or 1.
     *
     * @param  mixed  $transactions
     * @return array<int>
     */
    public function getCheckedTransactionIds($transactions): array
    {
        $ids = [];

        foreach ((array) $transactions as $key => $value) {
            if (empty($value) || ($value === 'false')) {
                continue;
            }

            if ($id = $this->getReconciliationTransactionId($key)) {
                $ids[] = $id;
            }
        }

        return array_values(array_unique($ids));
    }

    /**
     * What the bank statement and the books are apart by, as the page works it out: the closing balance less the
     * opening balance and the checked transactions, in the currency of the account. A reconciliation can be
     * reconciled when it is 0.
     *
     * @param  \App\Models\Banking\Account  $account
     * @param  mixed  $started_at
     * @param  mixed  $closing_balance
     * @param  Collection  $checked  the checked transactions
     * @return float
     */
    public function getReconciliationDifference($account, $started_at, $closing_balance, Collection $checked): float
    {
        $cleared = (float) $this->getOpeningBalance($account, $started_at);

        foreach ($checked as $transaction) {
            $amount = (float) $transaction->amount_for_account;

            $cleared += $transaction->isIncome() ? $amount : -$amount;
        }

        $precision = (int) currency($account->currency_code)->getPrecision();

        return round((float) $closing_balance - $cleared, $precision);
    }

    /**
     * Marks the transactions a reconciliation cleared as not reconciled, when it is unreconciled or deleted.
     *
     * @param  Reconciliation  $reconciliation
     * @return void
     */
    public function releaseReconciledTransactions(Reconciliation $reconciliation): void
    {
        $this->getReconcilableTransactions(
            $reconciliation->account_id,
            $reconciliation->started_at,
            $reconciliation->ended_at,
            $reconciliation->id
        )->isReconciled()->each(function ($transaction) {
            $transaction->reconciled = 0;
            $transaction->save();
        });
    }
}
