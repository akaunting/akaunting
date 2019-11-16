<?php

namespace App\BulkActions\Settings;

use App\Abstracts\BulkAction;
use App\Models\Setting\Category;

class Categories extends BulkAction
{

    public $model = Category::class;

    public $actions = [
        'enable' => [
            'name' => 'general.enable',
            'message' => 'bulk_actions.message.enable',
            'permission' => 'update-settings-categories'
        ],
        'disable' => [
            'name' => 'general.disable',
            'message' => 'bulk_actions.message.disable',
            'permission' => 'update-settings-categories'
        ],
        'delete' => [
            'name' => 'general.delete',
            'message' => 'bulk_actions.message.deletes',
            'permission' => 'delete-settings-categories'
        ]
    ];

    public function disable($request)
    {
        $selected = $request->get('selected', []);

        $categories = $this->model::find($selected);

        foreach ($categories as $category) {
            if ($relationships = $this->getRelationships($category)) {
                $category->enabled = 0;
                $category->save();

                $message = trans('messages.success.disabled', ['type' => $category->name]);

                return $this->itemResponse($category->fresh(), new Transformer(), $message);
            } else {
                $message = trans('messages.warning.disabled', ['name' => $category->name, 'text' => implode(', ', $relationships)]);

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

        $categories = $this->model::find($selected);

        foreach ($categories as $category) {
            // Can not delete the last category by type
            if (Category::where('type', $category->type)->count() == 1) {
                $message = trans('messages.error.last_category', ['type' => strtolower(trans_choice('general.' . $category->type . 's', 1))]);

                $this->response->errorUnauthorized($message);
            }

            if (!$relationships = $this->getRelationships($category)) {
                $category->delete();

                $message = trans('messages.success.deleted', ['type' => $category->name]);

                return new Response($message);
            } else {
                $message = trans('messages.warning.deleted', ['name' => $category->name, 'text' => implode(', ', $relationships)]);

                $this->response->errorUnauthorized($message);
            }
        }
    }

    protected function getRelationships($category)
    {
        $relationships = $this->countRelationships($category, [
            'items' => 'items',
            'invoices' => 'invoices',
            'revenues' => 'revenues',
            'bills' => 'bills',
            'payments' => 'payments',
        ]);

        return $relationships;
    }
}
