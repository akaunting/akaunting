<?php

namespace App\BulkActions\Purchases;

use App\Abstracts\BulkAction;
use App\Events\Purchase\BillCancelled;
use App\Events\Purchase\BillReceived;
use App\Exports\Purchases\Bills as Export;
use App\Jobs\Purchase\CreateBillHistory;
use App\Jobs\Purchase\DeleteBill;
use App\Models\Purchase\Bill;

class Bills extends BulkAction
{
    public $model = Bill::class;

    public $actions = [
        'received' => [
            'name' => 'bills.mark_received',
            'message' => 'bulk_actions.message.received',
            'permission' => 'update-purchases-bills',
        ],
        'cancelled' => [
            'name' => 'general.cancel',
            'message' => 'bulk_actions.message.cancelled',
            'permission' => 'update-purchases-bills',
        ],
        'delete' => [
            'name' => 'general.delete',
            'message' => 'bulk_actions.message.delete',
            'permission' => 'delete-purchases-bills',
        ],
        'export' => [
            'name' => 'general.export',
            'message' => 'bulk_actions.message.export',
        ],
    ];

    public function received($request)
    {
        $bills = $this->getSelectedRecords($request);

        foreach ($bills as $bill) {
            event(new BillReceived($bill));
        }
    }

    public function cancelled($request)
    {
        $bills = $this->getSelectedRecords($request);

        foreach ($bills as $bill) {
            event(new BillCancelled($bill));
        }
    }

    public function duplicate($request)
    {
        $bills = $this->getSelectedRecords($request);

        foreach ($bills as $bill) {
            $clone = $bill->duplicate();

            $description = trans('messages.success.added', ['type' => $clone->bill_number]);

            $this->dispatch(new CreateBillHistory($clone, 0, $description));
        }
    }

    public function destroy($request)
    {
        $bills = $this->getSelectedRecords($request);

        foreach ($bills as $bill) {
            try {
                $this->dispatch(new DeleteBill($bill));
            } catch (\Exception $e) {
                flash($e->getMessage())->error();
            }
        }
    }

    public function export($request)
    {
        $selected = $this->getSelectedInput($request);

        return \Excel::download(new Export($selected), \Str::filename(trans_choice('general.bills', 2)) . '.xlsx');
    }
}
