<?php

namespace App\Traits;

use App\Events\Banking\TransactionPrinting;
use App\Models\Banking\Transaction;
use Illuminate\Support\Str;

trait Transactions
{
    public function isIncome(): bool
    {
        $type = $this->type ?? $this->transaction->type ?? $this->model->type ?? 'income';

        return in_array($type, $this->getIncomeTypes());
    }

    public function isNotIncome(): bool
    {
        return ! $this->isIncome();
    }

    public function isExpense(): bool
    {
        $type = $this->type ?? $this->transaction->type ?? $this->model->type ?? 'expense';

        return in_array($type, $this->getExpenseTypes());
    }

    public function isNotExpense()
    {
        return ! $this->isExpense();
    }

    public function isRecurringTransaction(): bool
    {
        $type = $this->type ?? $this->transaction->type ?? $this->model->type ?? 'income';

        return Str::endsWith($type, '-recurring');
    }

    public function isNotRecurringTransaction(): bool
    {
        return ! $this->isRecurring();
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
        $alias = config('type.transaction.' . $type . '.alias');

        if (!empty($alias)) {
            $key .= $alias . '.';
        }

        $prefix = config('type.transaction.' . $type . '.setting.prefix');

        $key .= $prefix . '.' . $setting_key;

        return $key;
    }

    public function storeTransactionPdfAndGetPath($transaction)
    {
        event(new TransactionPrinting($transaction));

        $view = view('banking.transactions.print_default', ['transaction' => $transaction])->render();
        $html = mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');

        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($html);

        $file_name = $this->getTransactionFileName($transaction);

        $pdf_path = storage_path('app/temp/' . $file_name);

        // Save the PDF file into temp folder
        $pdf->save($pdf_path);

        return $pdf_path;
    }

    public function getTranslationsForConnect($type = 'income')
    {
        $document_type = config('type.transaction.' . $type . '.document_type');
        $contact_type = config('type.transaction.' . $type . '.contact_type');

        return [
            'title' => trans('general.connect') . ' ' . trans_choice('general.' . Str::plural($document_type), 1),
            'cancel' => trans('general.cancel'),
            'save' => trans('general.save'),
            'action' => trans('general.actions'),
            'document' => trans_choice('general.' . Str::plural($document_type), 1),
            'total' => trans('invoices.total'),
            'category' => trans_choice('general.categories', 1),
            'account' => trans_choice('general.accounts', 1),
            'amount' => trans('general.amount'),
            'number' => trans_choice('general.numbers', 1),
            'notes' => trans_choice('general.notes', 2),
            'contact' => trans_choice('general.' . Str::plural($contact_type), 1),
            'no_data' => trans('general.no_data'),
            'placeholder_search' => trans('general.placeholder.search'),
            'add_an' => trans('general.form.add_an', ['field' => trans_choice('general.' . Str::plural($document_type), 1)]),
            'transaction' => trans_choice('general.' . Str::plural($type), 1),
            'difference' => trans('general.difference'),
        ];
    }

    public function getTransactionFormRoutesOfType($type)
    {
        return [
            'contact_index' => route(Str::plural(config('type.transaction.' . $type . '.contact_type')) . '.index'),
            'contact_modal' => route('modals.' . Str::plural(config('type.transaction.' . $type . '.contact_type')) . '.create'),
            'category_index' => route('modals.categories.create', ['type' => $type]),
            'category_modal' => route('categories.index', ['search' => 'type:' . $type]),
        ];
    }

    public function getRealTypeOfRecurringTransaction(string $recurring_type): string
    {
        return Str::replace('-recurring', '', $recurring_type);
    }

    public function getNextTransactionNumber($suffix = ''): string
    {
        $prefix = setting('transaction' . $suffix . '.number_prefix');
        $next   = setting('transaction' . $suffix . '.number_next');
        $digit  = setting('transaction' . $suffix . '.number_digit');

        return $prefix . str_pad($next, $digit, '0', STR_PAD_LEFT);
    }

    public function increaseNextTransactionNumber($suffix = ''): void
    {
        $next = setting('transaction' . $suffix . '.number_next', 1) + 1;

        setting(['transaction' . $suffix . '.number_next' => $next]);
        setting()->save();
    }
}
