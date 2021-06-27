<?php

namespace App\Traits;

use App\Models\Banking\Transaction;
use Illuminate\Support\Str;

trait Transactions
{
    public function isIncome()
    {
        $type = $this->type ?? $this->transaction->type ?? 'income';

        return in_array($type, $this->getIncomeTypes());
    }

    public function isExpense()
    {
        $type = $this->type ?? $this->transaction->type ?? 'expense';

        return in_array($type, $this->getExpenseTypes());
    }

    public function getIncomeTypes($return = 'array')
    {
        return $this->getTransactionTypes('income', $return);
    }

    public function getExpenseTypes($return = 'array')
    {
        return $this->getTransactionTypes('expense', $return);
    }

    public function getTransactionTypes($index, $return = 'array')
    {
        $types = (string) setting('transaction.type.' . $index);

        return ($return == 'array') ? explode(',', $types) : $types;
    }

    public function addIncomeType($new_type)
    {
        $this->addTransactionType($new_type, 'income');
    }

    public function addExpenseType($new_type)
    {
        $this->addTransactionType($new_type, 'expense');
    }

    public function addTransactionType($new_type, $index)
    {
        $types = explode(',', setting('transaction.type.' . $index));

        if (in_array($new_type, $types)) {
            return;
        }

        $types[] = $new_type;

        setting([
            'transaction.type.' . $index => implode(',', $types),
        ])->save();
    }

    public function getTransactionFileName(Transaction $transaction, string $separator = '-', string $extension = 'pdf'): string
    {
        return $this->getSafeTransactionNumber($transaction, $separator) . $separator . time() . '.' . $extension;
    }

    public function getSafeTransactionNumber(Transaction $transaction, string $separator = '-'): string
    {
        return Str::slug($transaction->id, $separator, language()->getShortCode());
    }

    protected function getSettingKey($type, $setting_key)
    {
        $key = '';
        $alias = config('type.' . $type . '.alias');

        if (!empty($alias)) {
            $key .= $alias . '.';
        }

        $prefix = config('type.' . $type . '.setting.prefix');

        $key .= $prefix . '.' . $setting_key;

        return $key;
    }
}
