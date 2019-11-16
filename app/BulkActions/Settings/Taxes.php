<?php

namespace App\BulkActions\Settings;

use App\Abstracts\BulkAction;
use App\Models\Setting\Tax;

class Taxes extends BulkAction
{

    public $model = Tax::class;

    public $actions = [
        'enable' => [
            'name' => 'general.enable',
            'message' => 'bulk_actions.message.enable',
            'permission' => 'update-settings-taxes'
        ],
        'disable' => [
            'name' => 'general.disable',
            'message' => 'bulk_actions.message.disable',
            'permission' => 'update-settings-taxes'
        ],
        'delete' => [
            'name' => 'general.delete',
            'message' => 'bulk_actions.message.deletes',
            'permission' => 'delete-settings-taxes'
        ]
    ];

    public function disable($request)
    {
        $selected = $request->get('selected', []);

        $taxes = $this->model::find($selected);

        foreach ($taxes as $tax) {
            if (!$relationships = $this->getRelationships($tax)) {
                $tax->enabled = 0;
                $tax->save();

                $message = trans('messages.success.disabled', ['type' => $tax->name]);

                return $this->itemResponse($tax->fresh(), new Transformer(), $message);
            } else {
                $message = trans('messages.warning.disabled', ['name' => $tax->name, 'text' => implode(', ', $relationships)]);

                $this->response->errorUnauthorized($message);
            }
        }
    }

    public function delete($request)
    {
        $this->destroy($request);
    }

    public function destroy($request)
    {
        $selected = $request->get('selected', []);

        $taxes = $this->model::find($selected);

        foreach ($taxes as $tax) {
            if (!$relationships = $this->getRelationships($tax)) {
                $tax->delete();

                $message = trans('messages.success.deleted', ['type' => $tax->name]);

                return new Response($message);
            } else {
                $message = trans('messages.warning.deleted', ['name' => $tax->name, 'text' => implode(', ', $relationships)]);

                $this->response->errorUnauthorized($message);
            }
        }
    }

    protected function getRelationships($tax)
    {
        $relationships = $this->countRelationships($tax, [
            'items' => 'items',
            'invoice_items' => 'invoices',
            'bill_items' => 'bills',
        ]);

        return $relationships;
    }
}
