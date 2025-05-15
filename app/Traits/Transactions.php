<?php

namespace App\Traits;

use App\Events\Banking\TransactionPrinting;
use App\Models\Banking\Transaction;
use App\Interfaces\Utility\TransactionNumber;
use Illuminate\Support\Str;

trait Transactions
{
    public function isIncome(): bool
    {
        $type = $this->type ?? $this->transaction->type ?? $this->model->type ?? Transaction::INCOME_TYPE;

        return in_array($type, $this->getIncomeTypes());
    }

    public function isNotIncome(): bool
    {
        return ! $this->isIncome();
    }

    public function isExpense(): bool
    {
        $type = $this->type ?? $this->transaction->type ?? $this->model->type ?? Transaction::EXPENSE_TYPE;

        return in_array($type, $this->getExpenseTypes());
    }

    public function isNotExpense()
    {
        return ! $this->isExpense();
    }

    public function isRecurringTransaction(): bool
    {
        $type = $this->type ?? $this->transaction->type ?? $this->model->type ?? Transaction::INCOME_TYPE;

        return Str::endsWith($type, '-recurring');
    }

    public function isNotRecurringTransaction(): bool
    {
        return ! $this->isRecurring();
    }

    public function isTransferTransaction(): bool
    {
        $type = $this->type ?? $this->transaction->type ?? $this->model->type ?? Transaction::INCOME_TYPE;

        return Str::endsWith($type, '-transfer');
    }

    public function isNotTransferTransaction(): bool
    {
        return ! $this->isTransferTransaction();
    }

    public function isSplitTransaction(): bool
    {
        $type = $this->type ?? $this->transaction->type ?? $this->model->type ?? Transaction::INCOME_TYPE;

        return Str::endsWith($type, '-split');
    }

    public function isNotSplitTransaction(): bool
    {
        return ! $this->isSplitTransaction();
    }

    public function isDocumentTransaction(): bool
    {
        $document_id = $this->document_id ?? $this->transaction->document_id ?? $this->model->document_id ?? null;

        return ! empty($document_id);
    }

    public function isNotDocumentTransaction(): bool
    {
        return ! $this->isDocumentTransaction();
    }

    public function getIncomeTypes(string $return = 'array'): string|array
    {
        return $this->getTransactionTypes(Transaction::INCOME_TYPE, $return);
    }

    public function getExpenseTypes(string $return = 'array'): string|array
    {
        return $this->getTransactionTypes(Transaction::EXPENSE_TYPE, $return);
    }

    public function getTransactionTypes(string $index, string $return = 'array'): string|array
    {
        $types = (string) setting('transaction.type.' . $index);

        return ($return == 'array') ? explode(',', $types) : $types;
    }

    public function addIncomeType(string $new_type): void
    {
        $this->addTransactionType($new_type, 'income');
    }

    public function addExpenseType(string $new_type): void
    {
        $this->addTransactionType($new_type, 'expense');
    }

    public function addTransactionType(string $new_type, string $index): void
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

    protected function getTransactionSettingKey(string $type, string $setting_key): string
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

    public function storeTransactionPdfAndGetPath(Transaction $transaction): string
    {
        event(new TransactionPrinting($transaction));

        $real_type = $this->getRealTypeTransaction($transaction->type);

        $view = view('banking.transactions.print_default', compact('transaction', 'real_type'))->render();
        $html = mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');

        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($html);

        $file_name = $this->getTransactionFileName($transaction);

        $pdf_path = get_storage_path('app/temp/' . $file_name);

        // Save the PDF file into temp folder
        $pdf->save($pdf_path);

        return $pdf_path;
    }

    public function getTranslationsForConnect(string $type = Transaction::INCOME_TYPE): array
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
            'connect_tax' => trans('messages.warning.connect_tax', ['type' => $type]), 
        ];
    }

    public function getTransactionFormRoutesOfType(string $type): array
    {
        return [
            'contact_index' => route(Str::plural(config('type.transaction.' . $type . '.contact_type')) . '.index'),
            'contact_modal' => route('modals.' . Str::plural(config('type.transaction.' . $type . '.contact_type')) . '.create'),
            'category_index' => route('modals.categories.create', ['type' => $type]),
            'category_modal' => route('categories.index', ['search' => 'type:' . $type]),
        ];
    }

    public function getTypeTransaction(string $type = Transaction::INCOME_TYPE): string
    {
        return array_key_exists($type, config('type.transaction')) ? $type : Transaction::INCOME_TYPE;
    }

    public function getRealTypeTransaction(string $type): string
    {
        $type = $this->getRealTypeOfRecurringTransaction($type);
        $type = $this->getRealTypeOfTransferTransaction($type);
        $type = $this->getRealTypeOfSplitTransaction($type);

        return $type;
    }

    public function getTypeRecurringTransaction(string $type = Transaction::INCOME_RECURRING_TYPE): string
    {
        if (! Str::contains($type, '-recurring')) {
            return Transaction::INCOME_RECURRING_TYPE;
        }

        return array_key_exists($type, config('type.transaction')) ? $type : Transaction::INCOME_RECURRING_TYPE;
    }

    public function getRealTypeOfRecurringTransaction(string $recurring_type): string
    {
        return Str::replace('-recurring', '', $recurring_type);
    }

    public function getRealTypeOfTransferTransaction(string $transfer_type): string
    {
        return Str::replace('-transfer', '', $transfer_type);
    }

    public function getRealTypeOfSplitTransaction(string $transfer_type): string
    {
        return Str::replace('-split', '', $transfer_type);
    }

    public function getNextTransactionNumber($type = 'income', $suffix = ''): string
    {
        return app(TransactionNumber::class)->getNextNumber($type, $suffix, null);
    }

    public function increaseNextTransactionNumber($type = 'income', $suffix = ''): void
    {
        app(TransactionNumber::class)->increaseNextNumber($type, $suffix, null);
    }
}
