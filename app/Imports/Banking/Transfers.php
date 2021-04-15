<?php

namespace App\Imports\Banking;

use App\Abstracts\Import;
use App\Models\Banking\Transaction;
use App\Models\Banking\Transfer as Model;
use App\Models\Setting\Category;
use App\Traits\Currencies;
use App\Utilities\Date;

class Transfers extends Import
{
    use Currencies;

    public function model(array $row)
    {
        return new Model($row);
    }

    public function map($row): array
    {
        $row = parent::map($row);

        $row['transferred_at'] = Date::parse($row['transferred_at'])->format('Y-m-d');
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
            'from_currency_rate' => 'required',
            'to_account_id' => 'required|integer',
            'to_currency_code' => 'required|string|currency',
            'to_currency_rate' => 'required',
            'amount' => 'required|amount',
            'transferred_at' => 'required|date_format:Y-m-d',
            'payment_method' => 'required|string',
        ];
    }

    private function getExpenseTransactionId($row)
    {
        $expense_transaction = Transaction::create([
            'company_id' => company_id(),
            'type' => 'expense',
            'account_id' => $row['from_account_id'],
            'paid_at' => $row['transferred_at'],
            'currency_code' => $row['from_currency_code'],
            'currency_rate' => $row['from_currency_rate'],
            'amount' => $row['amount'],
            'contact_id' => 0,
            'description' => $row['description'],
            'category_id' => Category::transfer(), // Transfer Category ID
            'payment_method' => $row['payment_method'],
            'reference' => $row['reference'],
        ]);

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

        $income_transaction = Transaction::create([
            'company_id' => company_id(),
            'type' => 'income',
            'account_id' => $row['to_account_id'],
            'paid_at' => $row['transferred_at'],
            'currency_code' => $row['to_currency_code'],
            'currency_rate' => $row['to_currency_rate'],
            'amount' => $amount,
            'contact_id' => 0,
            'description' => $row['description'],
            'category_id' => Category::transfer(), // Transfer Category ID
            'payment_method' => $row['payment_method'],
            'reference' => $row['reference'],
        ]);

        return $income_transaction->id;
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
}
