<?php

namespace App\Abstracts;

use App\Traits\Jobs;
use App\Traits\Relationships;
use Artisan;

abstract class BulkAction
{
    use Jobs, Relationships;

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
}
