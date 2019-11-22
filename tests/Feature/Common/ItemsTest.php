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
		$this->loginAs()
			->post(route('items.store'), factory(Item::class)->raw())
			->assertStatus(200);

		$this->assertFlashLevel('success');
	}

	public function testItShouldSeeItemUpdatePage()
	{
        $item = $this->dispatch(new CreateItem(factory(Item::class)->raw()));

		$this->loginAs()
			->get(route('items.edit', ['item' => $item->id]))
			->assertStatus(200)
			->assertSee($item->name);
	}

	public function testItShouldUpdateItem()
	{
		$request = factory(Item::class)->raw();

		$item = $this->dispatch(new CreateItem($request));

		$request['name'] = $this->faker->text(15);

		$this->loginAs()
			->patch(route('items.update', $item->id), $request)
			->assertStatus(200);

		$this->assertFlashLevel('success');
	}

	public function testItShouldDeleteItem()
	{
		$item = $this->dispatch(new CreateItem(factory(Item::class)->raw()));

		$this->loginAs()
			->delete(route('items.destroy', ['item' => $item]))
			->assertStatus(200);

		$this->assertFlashLevel('success');
	}
}
