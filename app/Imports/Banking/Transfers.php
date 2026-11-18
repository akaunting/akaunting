<?php

namespace App\Imports\Banking;

use App\Abstracts\Import;
use App\Jobs\Banking\CreateTransaction;
use App\Models\Banking\Transaction;
use App\Models\Banking\Transfer as Model;
use App\Traits\Categories;
use App\Traits\Currencies;
use App\Traits\Jobs;
use App\Traits\Transactions;
use App\Utilities\Date;
use Illuminate\Support\Carbon;

class Transfers extends Import
{
    use Categories, Currencies, Jobs, Transactions;

    public $model = Model::class;

    public $columns = [
        'expense_transaction_id',
        'income_transaction_id',
    ];

    /**
     * The transactions linked to a transfer of this file so far.
     */
    protected array $linked_transaction_ids = [];

    /**
     * The transactions of the transfers there were before the file, read once.
     */
    protected ?array $transfer_transaction_ids = null;

    public function model(array $row)
    {
        if (self::hasRow($row)) {
            return;
        }

        return new Model($row);
    }

    public function map($row): array
    {
        $row = parent::map($row);

        $row['transferred_at'] = $this->normalizeTransferredAt($row['transferred_at'] ?? null);
        $row['from_currency_code'] = $this->getFromCurrencyCode($row);
        $row['to_currency_code'] = $this->getToCurrencyCode($row);
        $row['from_account_id'] = $this->getFromAccountId($row);
        $row['to_account_id'] = $this->getToAccountId($row);
        $row['expense_transaction_id'] = $this->getExpenseTransactionId($row);
        $row['income_transaction_id'] = $this->getIncomeTransactionId($row);

        return $row;
    }

    public function rules(): array
    {
        return [
            'from_account_id' => 'required|integer',
            'from_currency_code' => 'required|string|currency',
            'from_currency_rate' => 'required|gt:0',
            'to_account_id' => 'required|integer',
            'to_currency_code' => 'required|string|currency',
            'to_currency_rate' => 'required|gt:0',
            'amount' => 'required|amount',
            'transferred_at' => 'required|date_format:Y-m-d',
            'payment_method' => 'required|string',
        ];
    }

    private function getExpenseTransactionId($row)
    {
        $existing = $this->findTransferTransaction(
            Transaction::EXPENSE_TRANSFER_TYPE,
            $row['expense_transaction_number'] ?? null,
            $row['from_account_id'],
            $row['from_currency_code'],
            $row['transferred_at'],
            $row['amount'],
            $row['description'] ?? null
        );

        if ($existing) {
            return $existing->id;
        }

        $expense_transaction = $this->dispatch(new CreateTransaction([
            'company_id' => $row['company_id'],
            'number' => $this->getNextTransactionNumber(),
            'type' => Transaction::EXPENSE_TRANSFER_TYPE,
            'account_id' => $row['from_account_id'],
            'paid_at' => $row['transferred_at'],
            'currency_code' => $row['from_currency_code'],
            'currency_rate' => $row['from_currency_rate'],
            'amount' => $row['amount'],
            'contact_id' => 0,
            'description' => $row['description'],
            'category_id' => $this->getTransferCategoryId(),
            'payment_method' => $row['payment_method'],
            'reference' => $row['reference'],
            'created_by' => $row['created_by'],
        ]));

        $this->linked_transaction_ids[] = $expense_transaction->id;

        return $expense_transaction->id;
    }

    private function getIncomeTransactionId($row)
    {
        $amount = $row['amount'];

        // Convert amount if not same currency
        if ($row['from_currency_code'] !== $row['to_currency_code']) {
            $amount = $this->convertBetween(
                $amount,
                $row['from_currency_code'],
                $row['from_currency_rate'],
                $row['to_currency_code'],
                $row['to_currency_rate']
            );
        }

        $existing = $this->findTransferTransaction(
            Transaction::INCOME_TRANSFER_TYPE,
            $row['income_transaction_number'] ?? null,
            $row['to_account_id'],
            $row['to_currency_code'],
            $row['transferred_at'],
            $amount,
            $row['description'] ?? null
        );

        if ($existing) {
            return $existing->id;
        }

        $income_transaction = $this->dispatch(new CreateTransaction([
            'company_id' => $row['company_id'],
            'number' => $this->getNextTransactionNumber(),
            'type' => Transaction::INCOME_TRANSFER_TYPE,
            'account_id' => $row['to_account_id'],
            'paid_at' => $row['transferred_at'],
            'currency_code' => $row['to_currency_code'],
            'currency_rate' => $row['to_currency_rate'],
            'amount' => $amount,
            'contact_id' => 0,
            'description' => $row['description'],
            'category_id' => $this->getTransferCategoryId(),
            'payment_method' => $row['payment_method'],
            'reference' => $row['reference'],
            'created_by' => $row['created_by'],
        ]));

        $this->linked_transaction_ids[] = $income_transaction->id;

        return $income_transaction->id;
    }

    /**
     * The transaction of the given side of the transfer when there is one already, so the transfer is linked to it
     * instead of moving its money again.
     *
     * The transactions export writes a transfer's two transactions as well, and importing both files (a restore of
     * a backup does) brought each transfer's money in twice: once from the transactions file, once from the two
     * transactions opened here, and the first pair had no transfer.
     *
     * - By the number the transfers export writes beside the transfer. As the transactions import does, the number
     *   names the transaction: one a transfer has already is that transfer's, and the pair then names the transfer
     *   for hasRow(), which leaves the row out, so importing the file again does not add it again.
     * - In a file without the numbers: a transaction of the side's type, account, date and currency that no
     *   transfer has, the one with the transfer's description and the nearest amount first. The income side's amount
     *   is converted from the expense's here and may differ from the stored one by the rounding.
     */
    protected function findTransferTransaction(string $type, $number, $account_id, $currency_code, $transferred_at, $amount, $description): ?Transaction
    {
        $number = is_scalar($number) ? trim((string) $number) : '';

        if ($number !== '') {
            $transaction = Transaction::where('type', $type)
                ->where('number', $number)
                ->whereNotIn('id', $this->linked_transaction_ids)
                ->orderBy('id')
                ->first();
        } elseif (empty($account_id) || empty($transferred_at)) {
            $transaction = null;
        } else {
            $description = trim((string) $description);

            $transaction = Transaction::where('type', $type)
                ->where('account_id', $account_id)
                ->where('currency_code', $currency_code)
                ->whereDate('paid_at', Carbon::parse($transferred_at)->toDateString())
                ->whereNotIn('id', array_merge($this->getTransferTransactionIds(), $this->linked_transaction_ids))
                ->orderBy('id')
                ->get()
                ->sortBy([
                    fn ($a, $b) => (trim((string) $b->description) === $description) <=> (trim((string) $a->description) === $description),
                    fn ($a, $b) => abs((float) $a->amount - (float) $amount) <=> abs((float) $b->amount - (float) $amount),
                ])
                ->first();
        }

        if ($transaction) {
            $this->linked_transaction_ids[] = $transaction->id;
        }

        return $transaction;
    }

    /**
     * The transactions of the transfers there were before the file.
     */
    protected function getTransferTransactionIds(): array
    {
        if ($this->transfer_transaction_ids === null) {
            $this->transfer_transaction_ids = Model::query()
                ->toBase()
                ->get(['expense_transaction_id', 'income_transaction_id'])
                ->flatMap(fn ($transfer) => [$transfer->expense_transaction_id, $transfer->income_transaction_id])
                ->filter()
                ->map(fn ($id) => (int) $id)
                ->all();
        }

        return $this->transfer_transaction_ids;
    }

    private function getFromAccountId($row)
    {
        $row['account_id'] = $row['from_account_id'] ?? null;
        $row['account_name'] = $row['from_account_name'] ?? null;
        $row['account_number'] = $row['from_account_number'] ?? null;
        $row['currency_code'] = $row['from_currency_code'] ?? null;

        return $this->getAccountId($row);
    }

    private function getToAccountId($row)
    {
        $row['account_id'] = $row['to_account_id'] ?? null;
        $row['account_name'] = $row['to_account_name'] ?? null;
        $row['account_number'] = $row['to_account_number'] ?? null;
        $row['currency_code'] = $row['to_currency_code'] ?? null;

        return $this->getAccountId($row);
    }

    private function getFromCurrencyCode($row)
    {
        $row['currency_code'] = $row['from_currency_code'] ?? null;
        $row['currency_rate'] = $row['from_currency_rate'] ?? null;

        return $this->getCurrencyCode($row);
    }

    private function getToCurrencyCode($row)
    {
        $row['currency_code'] = $row['to_currency_code'] ?? null;
        $row['currency_rate'] = $row['to_currency_rate'] ?? null;

        return $this->getCurrencyCode($row);
    }

    protected function normalizeTransferredAt($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2}(?:\s+\d{2}:\d{2}:\d{2})?$/', $value)) {
            return substr((string) $value, 0, 10);
        }

        return Date::parseWithFallbackLocales($value, null, [$this->preferredLocale()])->format('Y-m-d');
    }
}
