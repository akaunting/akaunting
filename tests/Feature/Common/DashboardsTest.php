<?php

namespace Tests\Feature\Common;

use App\Models\Common\Widget;
use App\Models\Common\Dashboard;
use App\Jobs\Common\CreateDashboard;
use App\Utilities\Widgets;
use Tests\Feature\FeatureTestCase;

class DashboardsTest extends FeatureTestCase
{
    public function testItShouldSeeDashboard()
    {
        $this->loginAs()
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSeeText(trans_choice('general.dashboards', 1));
    }

    public function testItShouldSeeDashboardListPage()
    {
        $this->loginAs()
            ->get(route('dashboards.index'))
            ->assertOk()
            ->assertSeeText(trans_choice('general.dashboards', 2));
    }

    public function testItShouldSeeDashboardCreatePage()
    {
        $this->loginAs()
            ->get(route('dashboards.create'))
            ->assertOk()
            ->assertSeeText(trans('general.title.new', ['type' => trans_choice('general.dashboards', 1)]));
    }

    public function testItShouldCreateDashboard()
    {
        $request = $this->getRequest();

        $this->loginAs()
            ->post(route('dashboards.store'), $request)
            ->assertOk();

        $this->assertFlashLevel('success');

        $this->assertDatabaseHas('dashboards', $this->getAssertRequest($request));
    }

    public function testItShouldSeeDashboardUpdatePage()
    {
        $request = $this->getRequest();

        $dashboard = $this->dispatch(new CreateDashboard($request));

        $this->loginAs()
            ->get(route('dashboards.edit', $dashboard->id))
            ->assertOk()
            ->assertSee($dashboard->name);
    }

    public function testItShouldUpdateDashboard()
    {
        $request = $this->getRequest();

        $dashboard = $this->dispatch(new CreateDashboard($request));

        $request['name'] = $this->faker->text(15);

        $this->loginAs()
            ->patch(route('dashboards.update', $dashboard->id), $request)
            ->assertOk()
            ->assertSee($request['name']);

        $this->assertFlashLevel('success');

        $this->assertDatabaseHas('dashboards', $this->getAssertRequest($request));
    }

    public function testItShouldDeleteDashboard()
    {
        $request = $this->getRequest();

        $tmp = $this->dispatch(new CreateDashboard($this->getRequest()));
        $dashboard = $this->dispatch(new CreateDashboard($request));

        $this->loginAs()
            ->delete(route('dashboards.destroy', $dashboard->id))
            ->assertOk();

        $this->assertFlashLevel('success');

        $this->assertSoftDeleted('dashboards', $this->getAssertRequest($request));
    }

    public function testItShouldSeeWidgetCreate()
    {
        $class = Widgets::$core_widgets[array_rand(Widgets::$core_widgets)];

        $this->loginAs()
            ->get(route('widgets.index'))
            ->assertOk()
            ->assertSeeText((new $class())->getDefaultName(), false);
    }

    public function testItShouldSeeWidgetEdit()
    {
        $widget = Widget::create($this->getWidget());

        $this->loginAs()
            ->get(route('widgets.edit', $widget->id))
            ->assertOk()
            ->assertSee($widget->name);
    }

    public function testItShouldCreateWidget()
    {
        $request = $this->getWidget();

        $this->loginAs()
            ->post(route('widgets.store'), $request)
            ->assertOk();

        $this->assertDatabaseHas('widgets', $this->getAssertRequest($request));
    }

    public function testItShouldUpdateWidget()
    {
        $request = $this->getWidget();

        $widget = Widget::create($request);

        $request['name'] = $this->faker->name;

        $this->loginAs()
            ->patch(route('widgets.update', $widget->id), $request)
            ->assertOk();

        $this->assertDatabaseHas('widgets', $this->getAssertRequest($request));
    }

    public function testItShouldDeleteWidget()
    {
        $request = $this->getWidget();

        $widget = Widget::create($request);

        $this->loginAs()
            ->delete(route('widgets.destroy', $widget->id))
            ->assertOk();

        $this->assertSoftDeleted('widgets', $this->getAssertRequest($request));
    }

    public function getRequest()
    {
        return Dashboard::factory()->enabled()->users()->raw();
    }

    public function getWidget()
    {
        return Widget::factory()->raw();
    }

    public function getAssertRequest($request)
    {
        unset($request['users']);

        return $request;
    }
}
