<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Expense\Bill;

class Export extends Controller
{
    private $exporter;

    public function __construct(\Laracsv\Export $exporter)
    {
        $this->exporter = $exporter;
        parent::__construct();
    }

    private function download_expenses_bills()
    {
        $bills = Bill::all();
        $cols = [
            'id',
            'company_id',
            'bill_number',
            'order_number',
            'bill_status_code',
            'billed_at',
            'due_at',
            'amount',
            'currency_code',
            'currency_rate',
            'vendor_id',
            'vendor_name',
            'vendor_email',
            'vendor_tax_number',
            'vendor_phone',
            'vendor_address',
            'notes',
            'created_at',
            'updated_at',
            'deleted_at',
            'category_id',
            'parent_id'
        ];
        $this->exporter->build($bills, $cols)->download();
        die;
    }

    /**
     * Download specific export.
     *
     * @param  $group
     * @param  $type
     * @return Response
     */
    public function download($group, $type)
    {
        $path = $group . '/' . $type;

        if ($path === 'expenses/bills') {
            $this->download_expenses_bills();
            die;
        }

        $message = (trans('export.unsupported'));
        flash($message)->error()->important();
    }
}
