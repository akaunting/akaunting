<?php

namespace Tests\Feature\Common;

use App\Jobs\Common\CreateDashboard;
use App\Models\Common\Dashboard;
use Tests\Feature\FeatureTestCase;

class DashboardsTest extends FeatureTestCase
{
    public function testItShouldSeeDashboard()
    {
        $this->loginAs()
            ->get(route('dashboard'))
            ->assertStatus(200)
            ->assertSeeText(trans_choice('general.dashboards', 1));
    }

	public function testItShouldSeeDashboardListPage()
	{
		$this->loginAs()
			->get(route('dashboards.index'))
			->assertStatus(200)
			->assertSeeText(trans_choice('general.dashboards', 2));
	}

	public function testItShouldSeeDashboardCreatePage()
	{
		$this->loginAs()
			->get(route('dashboards.create'))
			->assertStatus(200)
			->assertSeeText(trans('general.title.new', ['type' => trans_choice('general.dashboards', 1)]));
	}

	public function testItShouldCreateDashboard()
	{
		$request = $this->getRequest();

		$this->loginAs()
			->post(route('dashboards.store'), $request)
			->assertStatus(200);

		$this->assertFlashLevel('success');

		$this->assertDatabaseHas('dashboards', $this->getAssertRequest($request));
	}

	public function testItShouldSeeDashboardUpdatePage()
	{
		$request = $this->getRequest();

        $dashboard = $this->dispatch(new CreateDashboard($request));

		$this->loginAs()
			->get(route('dashboards.edit', $dashboard->id))
			->assertStatus(200)
			->assertSee($dashboard->name);
	}

	public function testItShouldUpdateDashboard()
	{
		$request = $this->getRequest();

		$dashboard = $this->dispatch(new CreateDashboard($request));

		$request['name'] = $this->faker->text(15);

		$this->loginAs()
			->patch(route('dashboards.update', $dashboard->id), $request)
			->assertStatus(200)
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
			->assertStatus(200);

		$this->assertFlashLevel('success');

		$this->assertSoftDeleted('dashboards', $this->getAssertRequest($request));
	}

    public function getRequest()
    {
        return Dashboard::factory()->enabled()->users()->raw();
    }

    public function getAssertRequest($request)
    {
        unset($request['users']);

        return $request;
    }
}
