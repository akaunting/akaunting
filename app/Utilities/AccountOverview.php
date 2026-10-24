<?php

namespace App\Utilities;

use App\Models\Banking\Reconciliation;
use App\Models\Banking\Transaction;
use App\Traits\Transactions;
use Illuminate\Support\Collection;

/**
 * What the account list shows beside each account, read for the whole page at once.
 *
 * Every figure is gathered with one query for all the accounts rather than one per card, so
 * the list costs the same whether it holds two accounts or forty.
 */
class AccountOverview
{
    use Transactions;

    /**
     * The figures of each account, keyed by its id.
     *
     * @return array<int, array{balance_history: array, statement: array|null, reconciliation: array|null}>
     */
    public function get(Collection $accounts): array
    {
        if ($accounts->isEmpty()) {
            return [];
        }

        $ids = $accounts->pluck('id')->all();

        $today = Date::today();
        $days = $this->getHistoryDays();
        $start = $today->copy()->subDays($days - 1);

        $movements = $this->getDailyMovements($ids, $start);

        // Turned off, nothing about reconciling is read or shown, the last statement included
        $enabled = $this->isReconciliationEnabled();

        $reconciliations = $enabled ? $this->getReconciliations($ids) : collect();
        $statements = $this->getStatements($reconciliations);
        $unreconciled = $enabled ? $this->getUnreconciledDays($ids, $today) : [];

        $overview = [];

        foreach ($accounts as $account) {
            $last_ended_at = $statements[$account->id]['date'] ?? null;

            $overview[$account->id] = [
                'balance_history'   => $this->getBalanceHistory((float) $account->balance, $movements[$account->id] ?? [], $start, $days, $today),
                'statement'         => $statements[$account->id] ?? null,
                'reconciliation'    => $enabled ? $this->getReconciliation(
                    $unreconciled[$account->id] ?? [],
                    $last_ended_at,
                    $this->getDraft($reconciliations, $account->id, $last_ended_at),
                    $today
                ) : null,
            ];
        }

        return $overview;
    }

    public function isReconciliationEnabled(): bool
    {
        return (bool) config('accounts.reconciliation.enabled', true);
    }

    /**
     * What is waiting to be reconciled on an account, and the reconciliation that takes it on.
     *
     * Read from the account's own reconciliations rather than a fixed number of days: what is
     * waiting is everything dated after the last one that was finished, up to today, or, on an
     * account never reconciled, everything not reconciled yet. A reconciliation left as a draft
     * is the one to carry on with. Otherwise a new one starts the day after the last, or on the
     * first day waiting, and covers a month at most, so a long gap is taken a statement at a
     * time, the way the bank sends them. What is still not reconciled on or before the last
     * reconciliation was added to a period already closed: it is counted apart, since taking it
     * into the next one would reopen that period.
     *
     * @param  array<string, int>  $days  How many transactions are not reconciled on each day, keyed by Y-m-d.
     * @param  mixed  $last_ended_at  The end of the last finished reconciliation, if any.
     * @param  object|null  $draft  The reconciliation left as a draft, if any.
     * @return array{count: int, earlier: int, started_at: Date|null, ended_at: Date|null, draft_id: int|null}
     */
    public function getReconciliation(array $days, $last_ended_at, $draft, Date $today): array
    {
        $last_day = $last_ended_at ? Date::parse($last_ended_at)->toDateString() : null;

        $earlier = 0;
        $waiting = [];

        foreach ($days as $day => $count) {
            if ($last_day && $day <= $last_day) {
                $earlier += $count;
            } else {
                $waiting[$day] = $count;
            }
        }

        ksort($waiting);

        $started_at = $ended_at = null;

        if ($draft) {
            $started_at = Date::parse($draft->started_at)->startOfDay();
            $ended_at = Date::parse($draft->ended_at)->startOfDay();
        } else {
            if ($last_day) {
                $started_at = Date::parse($last_day)->addDay();
            } elseif (! empty($waiting)) {
                $started_at = Date::parse(array_key_first($waiting));
            }

            // Reconciled up to today already: the next statement has not begun
            if ($started_at && $started_at->greaterThan($today)) {
                $started_at = null;
            }

            if ($started_at) {
                $ended_at = $started_at->copy()->addMonthNoOverflow()->subDay();

                if ($ended_at->greaterThan($today)) {
                    $ended_at = $today->copy();
                }
            }
        }

        return [
            'count'         => array_sum($waiting),
            'earlier'       => $earlier,
            'started_at'    => $started_at,
            'ended_at'      => $ended_at,
            'draft_id'      => $draft->id ?? null,
        ];
    }

    /**
     * The balance at the end of each day, oldest first.
     *
     * Worked out backwards from the balance the account has now: what came in or went out on a
     * day is taken off to reach the day before it. A transaction dated after today is already
     * in the balance, so it is taken off before the first step.
     *
     * @param  array<string, float>  $movements  The net change of each day, keyed by Y-m-d.
     * @return array<int, float>
     */
    public function getBalanceHistory(float $balance, array $movements, Date $start, int $days, Date $today): array
    {
        $last_day = $today->toDateString();

        foreach ($movements as $day => $net) {
            if ($day > $last_day) {
                $balance -= $net;
            }
        }

        $history = [];

        for ($index = $days - 1; $index >= 0; $index--) {
            $day = $start->copy()->addDays($index)->toDateString();

            $history[$index] = round($balance, 4);

            $balance -= $movements[$day] ?? 0;
        }

        ksort($history);

        return array_values($history);
    }

    protected function getHistoryDays(): int
    {
        return max(2, (int) config('accounts.balance_history_days', 30));
    }

    /**
     * What came in less what went out, for each account and day from the start onwards.
     *
     * @return array<int, array<string, float>>
     */
    protected function getDailyMovements(array $ids, Date $start): array
    {
        $income_types = $this->getIncomeTypes();
        $expense_types = $this->getExpenseTypes();

        $placeholders = implode(',', array_fill(0, count($income_types), '?'));

        $rows = Transaction::query()
            ->whereIn('account_id', $ids)
            ->whereIn('type', array_merge($income_types, $expense_types))
            ->where('paid_at', '>=', $start->copy()->startOfDay())
            ->selectRaw('account_id, DATE(paid_at) as day, SUM(CASE WHEN type IN (' . $placeholders . ') THEN amount ELSE -amount END) as net', $income_types)
            ->groupBy('account_id', 'day')
            ->toBase()
            ->get();

        $movements = [];

        foreach ($rows as $row) {
            $movements[$row->account_id][substr((string) $row->day, 0, 10)] = (float) $row->net;
        }

        return $movements;
    }

    /**
     * Every reconciliation of the accounts, latest first, read once for the last statements and
     * the drafts alike.
     */
    protected function getReconciliations(array $ids): Collection
    {
        return Reconciliation::query()
            ->whereIn('account_id', $ids)
            ->orderByDesc('ended_at')
            ->get(['id', 'account_id', 'started_at', 'ended_at', 'closing_balance', 'reconciled']);
    }

    /**
     * The closing balance of the last reconciliation that was finished, as the latest
     * statement the company checked each account against.
     *
     * @return array<int, array{balance: float, date: mixed}>
     */
    protected function getStatements(Collection $reconciliations): array
    {
        return $reconciliations
            ->where('reconciled', 1)
            ->unique('account_id')
            ->mapWithKeys(fn ($reconciliation) => [
                $reconciliation->account_id => [
                    'balance'   => (float) $reconciliation->closing_balance,
                    'date'      => $reconciliation->ended_at,
                ],
            ])
            ->all();
    }

    /**
     * The latest reconciliation of the account left as a draft, unless one finished since has
     * gone past it.
     */
    protected function getDraft(Collection $reconciliations, int $account_id, $last_ended_at)
    {
        $draft = $reconciliations
            ->where('account_id', $account_id)
            ->where('reconciled', 0)
            ->first();

        if (! $draft || ($last_ended_at && Date::parse($draft->ended_at)->lessThanOrEqualTo(Date::parse($last_ended_at)))) {
            return null;
        }

        return $draft;
    }

    /**
     * How many transactions of each account are not reconciled yet, by day, up to today: one
     * dated later cannot be on a bank statement yet.
     *
     * @return array<int, array<string, int>>
     */
    protected function getUnreconciledDays(array $ids, Date $today): array
    {
        $rows = Transaction::query()
            ->whereIn('account_id', $ids)
            ->whereIn('type', array_merge($this->getIncomeTypes(), $this->getExpenseTypes()))
            ->where('reconciled', 0)
            ->where('paid_at', '<=', $today->copy()->endOfDay())
            ->selectRaw('account_id, DATE(paid_at) as day, COUNT(*) as total')
            ->groupBy('account_id', 'day')
            ->toBase()
            ->get();

        $days = [];

        foreach ($rows as $row) {
            $days[$row->account_id][substr((string) $row->day, 0, 10)] = (int) $row->total;
        }

        return $days;
    }
}
