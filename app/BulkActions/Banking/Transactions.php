<?php

namespace App\BulkActions\Banking;

use App\Abstracts\BulkAction;
use App\Exports\Banking\Transactions as Export;
use App\Jobs\Banking\UpdateTransaction;
use App\Models\Banking\Account;
use App\Models\Banking\Transaction;
use App\Traits\Transactions as TransactionsTrait;
use Illuminate\Support\Str;

class Transactions extends BulkAction
{
    use TransactionsTrait;

    public $model = Transaction::class;

    public $text = 'general.transactions';

    public $path = [
        'group' => 'banking',
        'type' => 'transactions',
    ];

    public $actions = [
        'edit' => [
            'icon'          => 'edit',
            'name'          => 'general.edit',
            'message'       => '',
            'permission'    => 'update-banking-transactions',
            'type'          => 'modal',
            'handle'        => 'update',
        ],
        'delete'    => [
            'icon'          => 'delete',
            'name'          => 'general.delete',
            'message'       => 'bulk_actions.message.delete',
            'permission'    => 'delete-banking-transactions',
        ],
        'export'    => [
            'icon'          => 'file_download',
            'name'          => 'general.export',
            'message'       => 'bulk_actions.message.export',
            'type'          => 'download',
        ],
    ];

    public function edit($request)
    {
        $selected = $this->getSelectedInput($request);

        $real_type = 'income';
        $contact_type = 'customer';
        $account_currency_code = 'USD';

        $types = [];
        $transactions = $this->getSelectedRecords($request);

        foreach ($transactions as $transaction) {
            $r_type = $this->getRealTypeTransaction($transaction->type);

            if (in_array($r_type, $types)) {
                continue;
            }

            $types[] = $r_type;

            $real_type = $r_type;
            
            $contact_type = $transaction->contact->type;

            if (! $contact_type) {
                if ($real_type == Transaction::INCOME_TYPE
                    || $real_type == Transaction::INCOME_TRANSFER_TYPE
                    || $real_type == Transaction::INCOME_SPLIT_TYPE
                    || $real_type == Transaction::INCOME_RECURRING_TYPE
                ) {
                    $contact_type = 'customer';
                } else {
                    $contact_type = 'vendor';
                }
            }

            $account_currency_code = $transaction->account->currency_code;
        }

        $category_and_contact = count($types) > 1 ? false : true;

        return $this->response('bulk-actions.banking.transactions.edit', compact('selected', 'category_and_contact', 'real_type', 'contact_type', 'account_currency_code'));
    }

    public function update($request)
    {
        $transactions = $this->getSelectedRecords($request);

        // When no account is picked, the modal still sends the currency of the account of one of
        // the selected transactions and a rate of 1. A transaction is in the currency of its
        // account, so the currency and its rate change only with the account, and are taken from it
        $account = null;

        if ($request->get('account_id')) {
            $account = Account::with('currency')->findOrFail($request->get('account_id'));
        }

        foreach ($transactions as $transaction) {
            try {
                $request->offsetUnset('currency_code');
                $request->offsetUnset('currency_rate');

                // A transaction that stays in the same currency keeps the rate it was entered with
                if ($account && $transaction->currency_code != $account->currency_code) {
                    // The amount of a document payment is what the document counts as paid,
                    // so it can not be moved to an account in another currency
                    if ($transaction->document_id) {
                        $type = Str::plural($transaction->document->type ?? 'document');

                        flash(trans('messages.warning.disable_code', [
                            'name' => $transaction->number,
                            'text' => strtolower(trans_choice("general.$type", 1)),
                        ]))->error()->important();

                        continue;
                    }

                    $request->merge([
                        'currency_code' => $account->currency_code,
                        'currency_rate' => $account->currency->rate,
                    ]);
                }

                $request->merge([
                    'type' => $transaction->type,
                    'uploaded_attachment' => $transaction->attachment,
                ])->except([

                ]);

                $this->dispatch(new UpdateTransaction($transaction, $this->getUpdateRequest($request)));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }

    public function destroy($request)
    {
        $this->deleteTransactions($request);
    }

    public function export($request)
    {
        $selected = $this->getSelectedInput($request);

        return $this->exportExcel(new Export($selected), trans_choice('general.transactions', 2));
    }
}
