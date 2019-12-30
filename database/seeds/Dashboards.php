<?php

namespace Database\Seeds;

use App\Abstracts\Model;
use App\Models\Common\Widget;
use App\Models\Common\Dashboard;
use App\Utilities\Widgets as WidgetUtility;
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

        $dashboard = Dashboard::create([
            'company_id' => $company_id,
            'user_id' => $user_id,
            'name' => trans('general.dashboard'),
            'enabled' => 1,
        ]);

        $widgets = WidgetUtility::getClasses();

        $sort = 1;

        foreach ($widgets as $class => $name) {
            Widget::create([
                'company_id' => $company_id,
                'dashboard_id' => $dashboard->id,
                'class' => $class,
                'name' => $name,
                'settings' => (new $class())->getDefaultSettings(),
                'sort' => $sort,
            ]);

            $sort++;
        }
    }
}
