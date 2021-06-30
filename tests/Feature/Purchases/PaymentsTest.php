<?php

namespace Tests\Feature\Purchases;

use App\Exports\Purchases\Payments as Export;
use App\Jobs\Banking\CreateTransaction;
use App\Models\Banking\Transaction;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\Feature\FeatureTestCase;

class PaymentsTest extends FeatureTestCase
{
    public function testItShouldSeePaymentListPage()
    {
        $this->loginAs()
            ->get(route('payments.index'))
            ->assertStatus(200)
            ->assertSeeText(trans_choice('general.payments', 2));
    }
    public function testItShouldSeePaymentShowPage()
    {
        $request = $this->getRequest();

        $payment = $this->dispatch(new CreateTransaction($request));

        $this->loginAs()
            ->get(route('payments.show', $payment->id))
            ->assertStatus(200)
            ->assertSee($payment->contact->email);
    }

    public function testItShouldSeePaymentCreatePage()
    {
        $this->loginAs()
            ->get(route('payments.create'))
            ->assertStatus(200)
            ->assertSeeText(trans('general.title.new', ['type' => trans_choice('general.payments', 1)]));
    }

    public function testItShouldCreatePayment()
    {
        $request = $this->getRequest();

        $this->loginAs()
            ->post(route('payments.store'), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertDatabaseHas('transactions', $request);
    }

	public function testItShouldSeePaymentUpdatePage()
	{
        $request = $this->getRequest();

        $payment = $this->dispatch(new CreateTransaction($request));

		$this->loginAs()
			->get(route('payments.edit', $payment->id))
			->assertStatus(200)
			->assertSee($payment->amount);
	}

    public function testItShouldUpdatePayment()
    {
        $request = $this->getRequest();

        $payment = $this->dispatch(new CreateTransaction($request));

        $request['amount'] = $this->faker->randomFloat(2, 1, 1000);

        $this->loginAs()
            ->patch(route('payments.update', $payment->id), $request)
            ->assertStatus(200)
			->assertSee($request['amount']);

        $this->assertFlashLevel('success');

        $this->assertDatabaseHas('transactions', $request);
    }

    public function testItShouldDeletePayment()
    {
        $request = $this->getRequest();

        $payment = $this->dispatch(new CreateTransaction($request));

        $this->loginAs()
            ->delete(route('payments.destroy', $payment->id))
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertSoftDeleted('transactions', $request);
    }

    public function testItShouldExportPayments()
    {
        $count = 5;
        Transaction::factory()->expense()->count($count)->create();

        \Excel::fake();

        $this->loginAs()
            ->get(route('payments.export'))
            ->assertStatus(200);

        \Excel::matchByRegex();

        \Excel::assertDownloaded(
            '/' . \Str::filename(trans_choice('general.payments', 2)) . '-\d{10}\.xlsx/',
            function (Export $export) use ($count) {
                // Assert that the correct export is downloaded.
                return $export->collection()->count() === $count;
            }
        );
    }

    public function testItShouldExportSelectedPayments()
    {
        $create_count = 5;
        $select_count = 3;

        $payments = Transaction::factory()->expense()->count($create_count)->create();

        \Excel::fake();

        $this->loginAs()
            ->post(
                route('bulk-actions.action', ['group' => 'purchases', 'type' => 'payments']),
                ['handle' => 'export', 'selected' => $payments->take($select_count)->pluck('id')->toArray()]
            )
            ->assertStatus(200);

        \Excel::matchByRegex();

        \Excel::assertDownloaded(
            '/' . \Str::filename(trans_choice('general.payments', 2)) . '-\d{10}\.xlsx/',
            function (Export $export) use ($select_count) {
                return $export->collection()->count() === $select_count;
            }
        );
    }

    public function testItShouldImportPayments()
    {
        \Excel::fake();

        $this->loginAs()
            ->post(
                route('payments.import'),
                [
                    'import' => UploadedFile::fake()->createWithContent(
                        'payments.xlsx',
                        File::get(public_path('files/import/payments.xlsx'))
                    ),
                ]
            )
            ->assertStatus(200);

        \Excel::assertImported('payments.xlsx');

        $this->assertFlashLevel('success');
    }

    public function getRequest()
    {
        return Transaction::factory()->expense()->raw();
    }
}
