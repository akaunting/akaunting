<?php

namespace App\BulkActions\Incomes;

use App\Abstracts\BulkAction;
use App\Exports\Incomes\Customers as Export;
use App\Models\Common\Contact;

class Customers extends BulkAction
{
    public $model = Contact::class;

    public $actions = [
        'enable' => [
            'name' => 'general.enable',
            'message' => 'bulk_actions.message.enable',
            'permission' => 'update-incomes-customers',
        ],
        'disable' => [
            'name' => 'general.disable',
            'message' => 'bulk_actions.message.disable',
            'permission' => 'update-incomes-customers',
        ],
        'duplicate' => [
            'name' => 'general.duplicate',
            'message' => 'bulk_actions.message.duplicate',
            'permission' => 'create-incomes-customers',
            'multiple' => true
        ],
        'delete' => [
            'name' => 'general.delete',
            'message' => 'bulk_actions.message.delete',
            'permission' => 'delete-incomes-customers',
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

        return \Excel::download(new Export($selected), trans_choice('general.customers', 2) . '.xlsx');
    }
}
