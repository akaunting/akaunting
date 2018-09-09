<?php

namespace Tests\Feature\Common;

use App\Models\Common\Item;
use Illuminate\Http\UploadedFile;
use Tests\Feature\FeatureTestCase;

class ItemsTest extends FeatureTestCase
{
	public function testItShouldBeShowTheItemsPage()
	{
		$this
			->loginAs()
			->get(route('items.index'))
			->assertStatus(200)
			->assertSee('Items');
	}

	public function testItShouldBeShowCreateItemPage()
	{
		$this
			->loginAs()
			->get(route('items.create'))
			->assertStatus(200)
			->assertSee('New Item');
	}

	public function testItShouldStoreAnItem()
	{
		$picture = UploadedFile::fake()->create('image.jpg');

		$item = [
			'name' => $this->faker->title,
			'sku' => $this->faker->languageCode,
			'picture' => $picture,
			'description' => $this->faker->text(100),
			'purchase_price' => $this->faker->randomFloat(2,10,20),
			'sale_price' => $this->faker->randomFloat(2,10,20),
			'quantity' => $this->faker->randomNumber(2),
			'category_id' => $this->company->categories()->first()->id,
			'tax_id' => $this->company->taxes()->first()->id,
			'enabled' => $this->faker->boolean ? 1 : 0
		];

		$this
			->loginAs()
			->post(route('items.store'), $item)
			->assertStatus(302)
			->assertRedirect(route('items.index'));
		$this->assertFlashLevel('success');
	}

	public function testItShouldEditItem()
	{
		$item = factory(Item::class)->create();

		$this
			->loginAs()
			->get(route('items.edit', ['item' => $item]))
			->assertStatus(200)
			->assertSee($item->name);
	}

	public function testItShouldDeleteItem()
	{
		$item = factory(Item::class)->create();

		$this
			->loginAs()
			->delete(route('items.destroy', ['item' => $item]))
			->assertStatus(302)
			->assertRedirect(route('items.index'));

		$this->assertFlashLevel('success');
	}
}