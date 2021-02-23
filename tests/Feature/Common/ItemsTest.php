<?php

namespace Tests\Feature\Common;

use App\Exports\Common\Items as Export;
use App\Jobs\Common\CreateItem;
use App\Models\Common\Item;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
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

    public function testItShouldExportItems()
    {
        $count = 5;
        Item::factory()->count($count)->create();

        \Excel::fake();

        $this->loginAs()
            ->get(route('items.export'))
            ->assertStatus(200);

        \Excel::assertDownloaded(
            \Str::filename(trans_choice('general.items', 2)) . '.xlsx',
            function (Export $export) use ($count) {
                // Assert that the correct export is downloaded.
                return $export->sheets()['items']->collection()->count() === $count;
            }
        );
    }

    public function testItShouldExportSelectedItems()
    {
        $count = 5;
        $items = Item::factory()->count($count)->create();

        \Excel::fake();

        $this->loginAs()
            ->post(
                route('bulk-actions.action', ['group' => 'common', 'type' => 'items']),
                ['handle' => 'export', 'selected' => [$items->random()->id]]
            )
            ->assertStatus(200);

        \Excel::assertDownloaded(
            \Str::filename(trans_choice('general.items', 2)) . '.xlsx',
            function (Export $export) {
                return $export->sheets()['items']->collection()->count() === 1;
            }
        );
    }

    public function testItShouldImportItems()
    {
        \Excel::fake();

        $this->loginAs()
            ->post(
                route('items.import'),
                [
                    'import' => UploadedFile::fake()->createWithContent(
                        'items.xlsx',
                        File::get(public_path('files/import/items.xlsx'))
                    ),
                ]
            )
            ->assertStatus(200);

        \Excel::assertImported('items.xlsx');

        $this->assertFlashLevel('success');
    }

    public function getRequest()
    {
        return Item::factory()->enabled()->raw();
    }
}
