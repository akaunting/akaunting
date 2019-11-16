<?php

namespace Database\Seeds;

use App\Abstracts\Model;
use App\Models\Common\Widget;
use App\Models\Common\Dashboard;
use App\Models\Common\DashboardWidget;
use Illuminate\Database\Seeder;

class Dashboards extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->create();

        Model::reguard();
    }

    private function create()
    {
        $user_id = $this->command->argument('user');
        $company_id = $this->command->argument('company');

        $rows = [
            [
                'company_id' => $company_id,
                'user_id' => $user_id,
                'name' => trans('general.dashboard'),
                'enabled' => 1,
            ]
        ];

        foreach ($rows as $row) {
            $dashboard = Dashboard::create($row);

            $widgets = Widget::where('company_id', $company_id)->orderBy('created_at','desc')->get();

            $sort = 1;

            foreach ($widgets as $widget) {
                DashboardWidget::create([
                    'company_id' => $company_id,
                    'user_id' => $user_id,
                    'dashboard_id' => $dashboard->id,
                    'widget_id' => $widget->id,
                    'name' => $widget->name,
                    'settings' => $widget->settings,
                    'sort' => $sort
                ]);

                $sort++;
            }
        }
    }
}
