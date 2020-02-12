<?php

namespace App\BulkActions\Purchases;

use App\Abstracts\BulkAction;
use App\Exports\Purchases\Payments as Export;
use App\Models\Banking\Transaction;

class Payments extends BulkAction
{
    public $model = Transaction::class;

    public $actions = [
        'export' => [
            'name' => 'general.export',
            'message' => 'bulk_actions.message.export',
        ],
        'delete' => [
            'name' => 'general.delete',
            'message' => 'bulk_actions.message.delete',
            'permission' => 'delete-purchases-payments',
        ],
    ];

    public function destroy($request)
    {
        $this->deleteTransactions($request);
    }

    public function export($request)
    {
        $selected = $this->getSelectedInput($request);

        return \Excel::download(new Export($selected), \Str::filename(trans_choice('general.payments', 2)) . '.xlsx');
    }
}
