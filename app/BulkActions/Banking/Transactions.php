<?php

namespace App\BulkActions\Banking;

use App\Abstracts\BulkAction;
use App\Exports\Banking\Transactions as Export;
use App\Models\Banking\Transaction;

class Transactions extends BulkAction
{
    public $model = Transaction::class;

    public $actions = [
        'delete' => [
            'name' => 'general.delete',
            'message' => 'bulk_actions.message.delete',
            'permission' => 'delete-banking-transactions',
        ],
        'export' => [
            'name' => 'general.export',
            'message' => 'bulk_actions.message.export',
            'type' => 'download',
        ],
    ];

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
