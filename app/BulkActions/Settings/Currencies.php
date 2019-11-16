<?php

namespace App\BulkActions\Settings;

use App\Abstracts\BulkAction;
use App\Models\Setting\Currency;

class Currencies extends BulkAction
{

    public $model = Currency::class;

    public $actions = [
        'enable' => [
            'name' => 'general.enable',
            'message' => 'bulk_actions.message.enable',
            'permission' => 'update-settings-currencies'
        ],
        'disable' => [
            'name' => 'general.disable',
            'message' => 'bulk_actions.message.disable',
            'permission' => 'update-settings-currencies'
        ],
        'delete' => [
            'name' => 'general.delete',
            'message' => 'bulk_actions.message.deletes',
            'permission' => 'delete-settings-currencies'
        ]
    ];

    public function disable($request)
    {
        $selected = $request->get('selected', []);

        $currencies = $this->model::find($selected);

        foreach ($currencies as $currency) {
            if (!$relationships = $this->getRelationships($currency)) {
                $currency->enabled = 0;
                $currency->save();

                $message = trans('messages.success.disabled', ['type' => $currency->name]);

                return $this->itemResponse($currency->fresh(), new Transformer(), $message);
            } else {
                $message = trans('messages.warning.disabled', ['name' => $currency->name, 'text' => implode(', ', $relationships)]);

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

        $currencies = $this->model::find($selected);

        foreach ($currencies as $currency) {
            if (!$relationships = $this->getRelationships($currency)) {
                $currency->delete();

                $message = trans('messages.success.deleted', ['type' => $currency->name]);

                return new Response($message);
            } else {
                $message = trans('messages.warning.deleted', ['name' => $currency->name, 'text' => implode(', ', $relationships)]);

                $this->response->errorUnauthorized($message);
            }
        }
    }

    protected function getRelationships($currency)
    {
        $relationships = $this->countRelationships($currency, [
            'accounts' => 'accounts',
            'customers' => 'customers',
            'invoices' => 'invoices',
            'revenues' => 'revenues',
            'bills' => 'bills',
            'payments' => 'payments',
        ]);

        if ($currency->code == setting('default.currency')) {
            $relationships[] = strtolower(trans_choice('general.companies', 1));
        }

        return $relationships;
    }
}
