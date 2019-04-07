<?php

namespace Tests\Feature\Common;

use App\Models\Common\Item;
use Illuminate\Http\UploadedFile;
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
			->post(route('items.store'), $this->getItemRequest())
			->assertStatus(302)
			->assertRedirect(route('items.index'));

		$this->assertFlashLevel('success');
	}

	public function testItShouldSeeItemUpdatePage()
	{
        $item = Item::create($this->getItemRequest());

		$this->loginAs()
			->get(route('items.edit', ['item' => $item->id]))
			->assertStatus(200)
			->assertSee($item->name);
	}

	public function testItShouldUpdateItem()
	{
		$request = $this->getItemRequest();

		$item = Item::create($request);

        $request['name'] = $this->faker->text(15);

		$this->loginAs()
			->patch(route('items.update', $item->id), $request)
			->assertStatus(302)
			->assertRedirect(route('items.index'));

		$this->assertFlashLevel('success');
	}

	public function testItShouldDeleteItem()
	{
		$item = Item::create($this->getItemRequest());

		$this->loginAs()
			->delete(route('items.destroy', ['item' => $item]))
			->assertStatus(302)
			->assertRedirect(route('items.index'));

		$this->assertFlashLevel('success');
	}

    private function getItemRequest()
    {
        $picture = UploadedFile::fake()->create('image.jpg');

        return [
            'company_id' => $this->company->id,
            'name' => $this->faker->text(15),
            'sku' => $this->faker->unique()->ean8,
            'picture' => $picture,
            'description' => $this->faker->text(100),
            'purchase_price' => $this->faker->randomFloat(2, 10, 20),
            'sale_price' => $this->faker->randomFloat(2, 10, 20),
            'quantity' => $this->faker->randomNumber(2),
            'category_id' => $this->company->categories()->type('item')->first()->id,
            'tax_id' => '',
            'enabled' => $this->faker->boolean ? 1 : 0
        ];
    }
}
