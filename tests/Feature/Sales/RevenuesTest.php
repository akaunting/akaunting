<?php

namespace Tests\Feature\Sales;

use App\Exports\Sales\Revenues as Export;
use App\Jobs\Banking\CreateTransaction;
use App\Models\Banking\Transaction;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\Feature\FeatureTestCase;

class RevenuesTest extends FeatureTestCase
{
    public function testItShouldSeeRevenueListPage()
    {
        $this->loginAs()
            ->get(route('revenues.index'))
            ->assertStatus(200)
            ->assertSeeText(trans_choice('general.revenues', 2));
    }

    public function testItShouldSeeRevenueCreatePage()
    {
        $this->loginAs()
            ->get(route('revenues.create'))
            ->assertStatus(200)
            ->assertSeeText(trans('general.title.new', ['type' => trans_choice('general.revenues', 1)]));
    }

   public function testItShouldCreateRevenue()
    {
        $request = $this->getRequest();

        $this->loginAs()
            ->post(route('revenues.store'), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertDatabaseHas('transactions', $request);
    }

	public function testItShouldSeeRevenueUpdatePage()
	{
        $request = $this->getRequest();

        $revenue = $this->dispatch(new CreateTransaction($request));

		$this->loginAs()
			->get(route('revenues.edit', $revenue->id))
			->assertStatus(200)
			->assertSee($revenue->amount);
	}

    public function testItShouldUpdateRevenue()
    {
        $request = $this->getRequest();

        $revenue = $this->dispatch(new CreateTransaction($request));

        $request['amount'] = $this->faker->randomFloat(2, 1, 1000);

        $this->loginAs()
            ->patch(route('revenues.update', $revenue->id), $request)
            ->assertStatus(200)
			->assertSee($request['amount']);

        $this->assertFlashLevel('success');

        $this->assertDatabaseHas('transactions', $request);
    }

    public function testItShouldDeleteRevenue()
    {
        $request = $this->getRequest();

        $revenue = $this->dispatch(new CreateTransaction($request));

        $this->loginAs()
            ->delete(route('revenues.destroy', $revenue->id))
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertSoftDeleted('transactions', $request);
    }

    public function testItShouldExportRevenues()
    {
        $count = 5;
        Transaction::factory()->income()->count($count)->create();

        \Excel::fake();

        $this->loginAs()
            ->get(route('revenues.export'))
            ->assertStatus(200);

        \Excel::assertDownloaded(
            \Str::filename(trans_choice('general.revenues', 2)) . '.xlsx',
            function (Export $export) use ($count) {
                // Assert that the correct export is downloaded.
                return $export->collection()->count() === $count;
            }
        );
    }

    public function testItShouldExportSelectedRevenues()
    {
        $count = 5;
        $revenues = Transaction::factory()->income()->count($count)->create();

        \Excel::fake();

        $this->loginAs()
            ->post(
                route('bulk-actions.action', ['group' => 'sales', 'type' => 'revenues']),
                ['handle' => 'export', 'selected' => [$revenues->random()->id]]
            )
            ->assertStatus(200);

        \Excel::assertDownloaded(
            \Str::filename(trans_choice('general.revenues', 2)) . '.xlsx',
            function (Export $export) {
                return $export->collection()->count() === 1;
            }
        );
    }

    public function testItShouldImportRevenues()
    {
        \Excel::fake();

        $this->loginAs()
            ->post(
                route('revenues.import'),
                [
                    'import' => UploadedFile::fake()->createWithContent(
                        'revenues.xlsx',
                        File::get(public_path('files/import/revenues.xlsx'))
                    ),
                ]
            )
            ->assertStatus(200);

        \Excel::assertImported('revenues.xlsx');

        $this->assertFlashLevel('success');
    }

    public function getRequest()
    {
        return Transaction::factory()->income()->raw();
    }
}
