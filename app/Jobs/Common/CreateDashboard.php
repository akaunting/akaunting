<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\HasSource;
use App\Interfaces\Job\ShouldCreate;
use App\Jobs\Common\CreateWidget;
use App\Models\Auth\User;
use App\Models\Common\Company;
use App\Models\Common\Dashboard;
use App\Models\Common\Widget;
use App\Utilities\Widgets;
use Illuminate\Support\Arr;

class CreateDashboard extends Job implements HasOwner, HasSource, ShouldCreate
{
    public function handle(): Dashboard
    {
        $this->request['enabled'] = $this->request['enabled'] ?? 1;

        \DB::transaction(function () {
            $users = $this->getUsers();

            if (empty($users)) {
                $this->model = Dashboard::make();

                return;
            }

            $this->model = Dashboard::create($this->request->only([
                'company_id', 'name', 'enabled', 'created_from', 'created_by'
            ]));

            $this->model->users()->attach($users);

            $this->checkAndCreateWidgets();
        });

        return $this->model;
    }

    protected function getUsers(): array
    {
        $list = [];

        if ($this->request->has('all_users')) {
            Company::find($this->request->get('company_id'))->users()->each(function ($user) use (&$list) {
                if (!$this->shouldCreateDashboardFor($user)) {
                    return;
                }

                $list[] = $user->id;
            });
        } elseif ($this->request->has('users')) {
            $user_ids = Arr::wrap($this->request->get('users'));

            foreach($user_ids as $user_id) {
                $user = User::find($user_id);

                if (!$this->shouldCreateDashboardFor($user)) {
                    continue;
                }

                $list[] = $user->id;
            }
        } else {
            $user = user();

            if ($this->shouldCreateDashboardFor($user)) {
                $list[] = $user->id;
            }
        }

        return $list;
    }

    protected function shouldCreateDashboardFor($user): bool
    {
        if (empty($user)) {
            return false;
        }

        // Don't create dashboard if user can't access admin panel (i.e. customer with login)
        if ($user->cannot('read-admin-panel')) {
            return false;
        }

        return true;
    }

    protected function checkAndCreateWidgets(): void
    {
        $sort = 1;

        if ($this->request->has('default_widgets')) {
            $default_widgets = $this->request->get('default_widgets');

            if (! is_array($default_widgets) && ($default_widgets == 'core')) {
                Widgets::optimizeCoreWidgets();
            }

            $widgets = Widgets::getClasses($default_widgets, false);

            $this->createWidgets($widgets, $sort);
        }

        if ($this->request->has('custom_widgets')) {
            $widgets = $this->request->get('custom_widgets');

            $this->createWidgets($widgets, $sort);
        }
    }

    protected function createWidgets($widgets, &$sort): void
    {
        foreach ($widgets as $class => $name) {
            // It's just an array of classes
            if (is_numeric($class)) {
                $class = $name;
                $name = (new $class())->getDefaultName();
            }

            $widget = Widget::companyId($this->model->company_id)
                        ->where('dashboard_id', $this->model->id)
                        ->where('class', $class)
                        ->first();

            if (! $widget) {
                $this->dispatch(new CreateWidget([
                    'company_id' => $this->model->company_id,
                    'dashboard_id' => $this->model->id,
                    'class' => $class,
                    'name' => $name,
                    'sort' => $sort,
                    'settings' => (new $class())->getDefaultSettings(),
                    'created_from' => $this->model->created_from,
                    'created_by' => $this->model->created_by,
                ]));
            }

            $sort++;
        }
    }
}
