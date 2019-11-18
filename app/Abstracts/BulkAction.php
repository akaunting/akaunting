<?php

namespace App\Abstracts;

use Artisan;
use Illuminate\Database\Eloquent\Collection;

abstract class BulkAction
{
    public $model = false;

    public $actions = [
        'enable' => [
            'name' => 'general.enable',
            'message' => 'bulk_actions.message.enable',
            'permission' => 'update-common-items'
        ],
        'disable' => [
            'name' => 'general.disable',
            'message' => 'bulk_actions.message.disable',
            'permission' => 'update-common-items'
        ],
        'export' => [
            'name' => 'general.export',
            'message' => 'bulk_actions.message.exports',
        ],
        'delete' => [
            'name' => 'general.delete',
            'message' => 'bulk_actions.message.deletes',
            'permission' => 'delete-common-items'
        ]
    ];

    /**
     * Duplicate the specified resource.
     *
     * @param  $request
     *
     * @return Response
     */
    public function duplicate($request)
    {
        $selected = $request->get('selected', []);

        $items = $this->model::find($selected);

        foreach ($items as $item) {
            $item->duplicate();
        }
    }

    /**
     * Enable the specified resource.
     *
     * @param  $request
     *
     * @return Response
     */
    public function enable($request)
    {
        $selected = $request->get('selected', []);

        $items = $this->model::find($selected);

        foreach ($items as $item) {
            $item->enabled = 1;
            $item->save();
        }
    }

    /**
     * Disable the specified resource.
     *
     * @param  $request
     *
     * @return Response
     */
    public function disable($request)
    {
        $selected = $request->get('selected', []);

        $items = $this->model::find($selected);

        foreach ($items as $item) {
            $item->enabled = 0;
            $item->save();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $request
     *
     * @return Response
     */
    public function delete($request)
    {
        $this->destroy($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $request
     *
     * @return Response
     */
    public function destroy($request)
    {
        $selected = $request->get('selected', []);

        $items = $this->model::find($selected);

        foreach ($items as $item) {
            $item->delete();
        }

        Artisan::call('cache:clear');
    }

    public function countRelationships($model, $relationships)
    {
        $counter = [];

        foreach ($relationships as $relationship => $text) {
            if ($c = $model->$relationship()->count()) {
                $counter[] = $c . ' ' . strtolower(trans_choice('general.' . $text, ($c > 1) ? 2 : 1));
            }
        }

        return $counter;
    }

    /**
     * Mass delete relationships with events being fired.
     *
     * @param  $model
     * @param  $relationships
     *
     * @return void
     */
    public function deleteRelationships($model, $relationships)
    {
        foreach ((array) $relationships as $relationship) {
            if (empty($model->$relationship)) {
                continue;
            }

            $items = $model->$relationship->all();

            if ($items instanceof Collection) {
                $items = $items->all();
            }

            foreach ((array) $items as $item) {
                $item->delete();
            }
        }
    }
}
