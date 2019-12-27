<?php

namespace App\BulkActions\Incomes;

use App\Abstracts\BulkAction;
use App\Events\Income\InvoiceCreated;
use App\Events\Income\InvoiceSent;
use App\Events\Income\PaymentReceived;
use App\Exports\Incomes\Invoices as Export;
use App\Jobs\Income\DeleteInvoice;
use App\Models\Income\Invoice;

class Invoices extends BulkAction
{
    public $model = Invoice::class;

    public $actions = [
        'paid' => [
            'name' => 'invoices.mark_paid',
            'message' => 'bulk_actions.message.paid',
            'permission' => 'update-incomes-invoices',
        ],
        'sent' => [
            'name' => 'invoice.mark_sent',
            'message' => 'bulk_actions.message.sent',
            'permission' => 'update-incomes-invoices',
        ],
        'delete' => [
            'name' => 'general.delete',
            'message' => 'bulk_actions.message.delete',
            'permission' => 'delete-incomes-invoices',
        ],
        'export' => [
            'name' => 'general.export',
            'message' => 'bulk_actions.message.export',
        ]
    ];

    public function paid($request)
    {
        $invoices = $this->getSelectedRecords($request);

        foreach ($invoices as $invoice) {
            event(new PaymentReceived($invoice, []));
        }
    }

    public function sent($request)
    {
        $invoices = $this->getSelectedRecords($request);

        foreach ($invoices as $invoice) {
            event(new InvoiceSent($invoice));
        }
    }

    public function duplicate($request)
    {
        $invoices = $this->getSelectedRecords($request);

        foreach ($invoices as $invoice) {
            $clone = $invoice->duplicate();

            event(new InvoiceCreated($clone));
        }
    }

    public function delete($request)
    {
        $this->destroy($request);
    }

    public function destroy($request)
    {
        $invoices = $this->getSelectedRecords($request);

        foreach ($invoices as $invoice) {
            try {
                $this->dispatch(new DeleteInvoice($invoice));
            } catch (\Exception $e) {
                flash($e->getMessage())->error();
            }
        }
    }

    public function export($request)
    {
        $selected = $this->getSelectedInput($request);

        return \Excel::download(new Export($selected), trans_choice('general.invoices', 2) . '.xlsx');
    }
}
