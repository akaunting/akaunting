<?php

namespace App\BulkActions\Banking;

use App\Abstracts\BulkAction;
use App\Exports\Banking\Transactions as Export;
use App\Jobs\Banking\UpdateTransaction;
use App\Models\Banking\Transaction;
use App\Traits\Transactions as TransactionsTrait;

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

        foreach ($transactions as $transaction) {
            try {
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
