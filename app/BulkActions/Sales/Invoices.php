<?php

namespace App\BulkActions\Sales;

use App\Abstracts\BulkAction;
use App\Events\Sale\InvoiceCancelled;
use App\Events\Sale\InvoiceCreated;
use App\Events\Sale\InvoiceSent;
use App\Events\Sale\PaymentReceived;
use App\Exports\Sales\Invoices as Export;
use App\Jobs\Sale\DeleteInvoice;
use App\Models\Sale\Invoice;

class Invoices extends BulkAction
{
    public $model = Invoice::class;

    public $actions = [
        'paid' => [
            'name' => 'invoices.mark_paid',
            'message' => 'bulk_actions.message.paid',
            'permission' => 'update-sales-invoices',
        ],
        'sent' => [
            'name' => 'invoices.mark_sent',
            'message' => 'bulk_actions.message.sent',
            'permission' => 'update-sales-invoices',
        ],
        'cancelled' => [
            'name' => 'general.cancel',
            'message' => 'bulk_actions.message.cancelled',
            'permission' => 'update-sales-invoices',
        ],
        'delete' => [
            'name' => 'general.delete',
            'message' => 'bulk_actions.message.delete',
            'permission' => 'delete-sales-invoices',
        ],
        'export' => [
            'name' => 'general.export',
            'message' => 'bulk_actions.message.export',
        ],
    ];

    public function paid($request)
    {
        $invoices = $this->getSelectedRecords($request);

        foreach ($invoices as $invoice) {
            event(new PaymentReceived($invoice));
        }
    }

    public function sent($request)
    {
        $invoices = $this->getSelectedRecords($request);

        foreach ($invoices as $invoice) {
            event(new InvoiceSent($invoice));
        }
    }

    public function cancelled($request)
    {
        $invoices = $this->getSelectedRecords($request);

        foreach ($invoices as $invoice) {
            event(new InvoiceCancelled($invoice));
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

        return \Excel::download(new Export($selected), \Str::filename(trans_choice('general.invoices', 2)) . '.xlsx');
    }
}
