<?php

namespace App\BulkActions\Purchases;

use App\Abstracts\BulkAction;
use App\Exports\Purchases\Vendors as Export;
use App\Jobs\Common\UpdateContact;
use App\Models\Common\Contact;

class Vendors extends BulkAction
{
    public $model = Contact::class;

    public $text = 'general.vendors';

    public $path = [
        'group' => 'purchases',
        'type' => 'vendors',
    ];

    public $actions = [
        'edit' => [
            'icon'          => 'edit',
            'name'          => 'general.edit',
            'message'       => '',
            'permission'    => 'update-purchases-vendors',
            'type'          => 'modal',
            'handle'        => 'update',
        ],
        'enable'    => [
            'icon'          => 'check_circle',
            'name'          => 'general.enable',
            'message'       => 'bulk_actions.message.enable',
            'permission'    => 'update-purchases-vendors',
        ],
        'disable'   => [
            'icon'          => 'hide_source',
            'name'          => 'general.disable',
            'message'       => 'bulk_actions.message.disable',
            'permission'    => 'update-purchases-vendors',
        ],
        'delete'    => [
            'icon'          => 'delete',
            'name'          => 'general.delete',
            'message'       => 'bulk_actions.message.delete',
            'permission'    => 'delete-purchases-vendors',
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

        return $this->response('bulk-actions.purchases.vendors.edit', compact('selected'));
    }

    public function update($request)
    {
        $vendors = $this->getSelectedRecords($request);

        foreach ($vendors as $vendor) {
            try {
                $request->merge([
                    'enabled' => $vendor->enabled,
                    'uploaded_logo' => $vendor->logo,
                ]); // for update job authorize..

                $this->dispatch(new UpdateContact($vendor, $this->getUpdateRequest($request)));
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

        return $this->exportExcel(new Export($selected), trans_choice('general.vendors', 2));
    }
}
