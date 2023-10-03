<?php

namespace App\BulkActions\Sales;

use App\Abstracts\BulkAction;
use App\Exports\Sales\Customers as Export;
use App\Jobs\Common\UpdateContact;
use App\Models\Common\Contact;

class Customers extends BulkAction
{
    public $model = Contact::class;

    public $text = 'general.customers';

    public $path = [
        'group' => 'sales',
        'type' => 'customers',
    ];

    public $actions = [
        'edit' => [
            'icon'          => 'edit',
            'name'          => 'general.edit',
            'message'       => '',
            'permission'    => 'update-sales-customers',
            'type'          => 'modal',
            'handle'        => 'update',
        ],
        'enable'    => [
            'icon'          => 'check_circle',
            'name'          => 'general.enable',
            'message'       => 'bulk_actions.message.enable',
            'permission'    => 'update-sales-customers',
        ],
        'disable'   => [
            'icon'          => 'hide_source',
            'name'          => 'general.disable',
            'message'       => 'bulk_actions.message.disable',
            'permission'    => 'update-sales-customers',
        ],
        'delete'    => [
            'icon'          => 'delete',
            'name'          => 'general.delete',
            'message'       => 'bulk_actions.message.delete',
            'permission'    => 'delete-sales-customers',
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

        return $this->response('bulk-actions.sales.customers.edit', compact('selected'));
    }

    public function update($request)
    {
        $customers = $this->getSelectedRecords($request);

        foreach ($customers as $customer) {
            try {
                $request->merge([
                    'enabled' => $customer->enabled,
                    'uploaded_logo' => $customer->logo,
                ]); // for update job authorize..

                $this->dispatch(new UpdateContact($customer, $this->getUpdateRequest($request)));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }

    public function disable($request)
    {
        $this->disableContacts($request);
    }

    public function destroy($request)
    {
        $this->deleteContacts($request);
    }

    public function export($request)
    {
        $selected = $this->getSelectedInput($request);

        return $this->exportExcel(new Export($selected), trans_choice('general.customers', 2));
    }
}
