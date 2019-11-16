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
            'permission' => 'update-incomes-customers'
        ],
        'disable' => [
            'name' => 'general.disable',
            'message' => 'bulk_actions.message.disable',
            'permission' => 'update-incomes-customers'
        ],
        'duplicate' => [
            'name' => 'general.duplicate',
            'message' => 'bulk_actions.message.duplicate',
            'permission' => 'create-incomes-customers',
            'multiple' => true
        ],
        'export' => [
            'name' => 'general.export',
            'message' => 'bulk_actions.message.export',
        ],
        'delete' => [
            'name' => 'general.delete',
            'message' => 'bulk_actions.message.deletes',
            'permission' => 'delete-incomes-customers'
        ]
    ];

    public function duplicate($request)
    {
        $selected = $request->get('selected', []);

        $contacts = $this->model::find($selected);

        foreach ($contacts as $contact) {
            $clone = $contact->duplicate();
        }
    }

    public function delete($request)
    {
        $this->destroy($request);
    }

    public function destroy($request)
    {
        $selected = $request->get('selected', []);

        $contacts = $this->model::find($selected);

        foreach ($contacts as $contact) {
            if (!$relationships = $this->getRelationships($contact)) {
                $contact->delete();

                $message = trans('messages.success.deleted', ['type' => $contact->name]);

                return new Response($message);
            } else {
                $message = trans('messages.warning.deleted', ['name' => $contact->name, 'text' => implode(', ', $relationships)]);

                $this->response->errorUnauthorized($message);
            }
        }
    }

    public function export($request)
    {
        $selected = $request->get('selected', []);

        return \Excel::download(new Export($selected), trans_choice('general.customers', 2) . '.xlsx');
    }

    protected function getRelationships($contact)
    {
        $rels = [
            'invoices' => 'invoices',
            'revenues' => 'revenues',
        ];

        $relationships = $this->countRelationships($contact, $rels);

        return $relationships;
    }
}
