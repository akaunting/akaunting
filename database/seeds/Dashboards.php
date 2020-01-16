<?php

namespace Database\Seeds;

use App\Abstracts\Model;
use App\Models\Auth\User;
use App\Models\Common\Widget;
use App\Models\Common\Dashboard;
use App\Utilities\Widgets;
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
            'name' => trans_choice('general.dashboards', 1),
            'enabled' => 1,
        ]);

        $widgets = Widgets::getClasses(false);

        $sort = 1;

        foreach ($widgets as $class => $name) {
            Widget::create([
                'company_id' => $company_id,
                'dashboard_id' => $dashboard->id,
                'class' => $class,
                'name' => $name,
                'sort' => $sort,
                'settings' => (new $class())->getDefaultSettings(),
            ]);

            $sort++;
        }

        User::find($user_id)->dashboards()->attach($dashboard->id);
    }
}
