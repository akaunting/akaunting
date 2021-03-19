<?php

namespace App\BulkActions\Common;

use App\Abstracts\BulkAction;
use App\Jobs\Common\DeleteDashboard;
use App\Jobs\Common\UpdateDashboard;
use App\Models\Common\Dashboard;

class Dashboards extends BulkAction
{
    public $model = Dashboard::class;

    public $actions = [
        'enable' => [
            'name' => 'general.enable',
            'message' => 'bulk_actions.message.enable',
            'permission' => 'update-common-dashboards',
        ],
        'disable' => [
            'name' => 'general.disable',
            'message' => 'bulk_actions.message.disable',
            'permission' => 'update-common-dashboards',
        ],
        'delete' => [
            'name' => 'general.delete',
            'message' => 'bulk_actions.message.delete',
            'permission' => 'delete-common-dashboards',
        ],
    ];

    public function enable($request)
    {
        $dashboards = $this->getSelectedRecords($request);

        foreach ($dashboards as $dashboard) {
            try {
                $this->dispatch(new UpdateDashboard($dashboard, $request->merge(['enabled' => 1])));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }

    public function disable($request)
    {
        $dashboards = $this->getSelectedRecords($request);

        foreach ($dashboards as $dashboard) {
            try {
                $this->dispatch(new UpdateDashboard($dashboard, $request->merge(['enabled' => 0])));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }

    public function destroy($request)
    {
        $dashboards = $this->getSelectedRecords($request);

        foreach ($dashboards as $dashboard) {
            try {
                $this->dispatch(new DeleteDashboard($dashboard));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }
}
