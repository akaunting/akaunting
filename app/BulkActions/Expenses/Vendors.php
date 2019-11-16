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
            'permission' => 'update-expenses-vendors'
        ],
        'disable' => [
            'name' => 'general.disable',
            'message' => 'bulk_actions.message.disable',
            'permission' => 'update-expenses-vendors'
        ],
        'duplicate' => [
            'name' => 'general.duplicate',
            'message' => 'bulk_actions.message.duplicate',
            'permission' => 'create-expenses-vendors',
            'multiple' => true
        ],
        'export' => [
            'name' => 'general.export',
            'message' => 'bulk_actions.message.exports',
        ],
        'delete' => [
            'name' => 'general.delete',
            'message' => 'bulk_actions.message.deletes',
            'permission' => 'delete-expenses-vendors'
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

        return \Excel::download(new Export($selected), trans_choice('general.vendors', 2) . '.xlsx');
    }

    protected function getRelationships($contact)
    {
        $rels = [
            'bills' => 'bills',
            'payments' => 'payments',
        ];

        $relationships = $this->countRelationships($contact, $rels);

        return $relationships;
    }
}
