<?php

namespace App\BulkActions\Expenses;

use App\Abstracts\BulkAction;
use App\Exports\Expenses\Vendors as Export;
use App\Models\Common\Contact;

class Vendors extends BulkAction
{
    public $model = Contact::class;

    public $actions = [
        'enable' => [
            'name' => 'general.enable',
            'message' => 'bulk_actions.message.enable',
            'permission' => 'update-expenses-vendors',
        ],
        'disable' => [
            'name' => 'general.disable',
            'message' => 'bulk_actions.message.disable',
            'permission' => 'update-expenses-vendors',
        ],
        'delete' => [
            'name' => 'general.delete',
            'message' => 'bulk_actions.message.delete',
            'permission' => 'delete-expenses-vendors',
        ],
        'export' => [
            'name' => 'general.export',
            'message' => 'bulk_actions.message.export',
        ],
    ];

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

        return \Excel::download(new Export($selected), trans_choice('general.vendors', 2) . '.xlsx');
    }
}
