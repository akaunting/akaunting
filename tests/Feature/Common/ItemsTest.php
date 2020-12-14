<?php

namespace Tests\Feature\Common;

use App\Jobs\Common\CreateItem;
use App\Models\Common\Item;
use Tests\Feature\FeatureTestCase;

class ItemsTest extends FeatureTestCase
{
	public function testItShouldSeeItemListPage()
	{
		$this->loginAs()
			->get(route('items.index'))
			->assertStatus(200)
			->assertSeeText(trans_choice('general.items', 2));
	}

	public function testItShouldSeeItemCreatePage()
	{
		$this->loginAs()
			->get(route('items.create'))
			->assertStatus(200)
			->assertSeeText(trans('general.title.new', ['type' => trans_choice('general.items', 1)]));
	}

	public function testItShouldCreateItem()
	{
		$request = $this->getRequest();

		$this->loginAs()
			->post(route('items.store'), $request)
			->assertStatus(200);

		$this->assertFlashLevel('success');

		$this->assertDatabaseHas('items', $request);
	}

	public function testItShouldSeeItemUpdatePage()
	{
		$request = $this->getRequest();

        $item = $this->dispatch(new CreateItem($request));

		$this->loginAs()
			->get(route('items.edit', $item->id))
			->assertStatus(200)
			->assertSee($item->name);
	}

	public function testItShouldUpdateItem()
	{
		$request = $this->getRequest();

		$item = $this->dispatch(new CreateItem($request));

		$request['name'] = $this->faker->text(15);

		$this->loginAs()
			->patch(route('items.update', $item->id), $request)
			->assertStatus(200)
			->assertSee($request['name']);

		$this->assertFlashLevel('success');

		$this->assertDatabaseHas('items', $request);
	}

	public function testItShouldDeleteItem()
	{
		$request = $this->getRequest();

		$item = $this->dispatch(new CreateItem($request));

		$this->loginAs()
			->delete(route('items.destroy', $item->id))
			->assertStatus(200);

		$this->assertFlashLevel('success');

		$this->assertSoftDeleted('items', $request);
	}

    public function getRequest()
    {
        return Item::factory()->enabled()->raw();
    }
}
