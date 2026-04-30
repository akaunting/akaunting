<?php

namespace Tests\Feature\Banking;

use App\Exports\Banking\Transactions as Export;
use App\Jobs\Banking\CreateTransaction;
use App\Jobs\Document\CreateDocument;
use App\Notifications\Banking\Transaction as Notification;
use App\Models\Banking\Transaction;
use App\Models\Document\Document;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use Maatwebsite\Excel\Facades\Excel;
use Tests\Feature\FeatureTestCase;

class TransactionsTest extends FeatureTestCase
{
    public function testItShouldSeeTransactionListPage()
    {
        $this->loginAs()
            ->get(route('transactions.index'))
            ->assertStatus(200)
            ->assertSeeText(trans_choice('general.transactions', 2));
    }

    public function testItShouldSeeTransactionListPageWithDocumentTransactions()
    {
        $invoice = $this->createInvoice();

        $request = Transaction::factory()->income()->raw([
            'contact_id' => $invoice->contact_id,
            'document_id' => $invoice->id,
            'amount' => $invoice->amount,
            'currency_code' => $invoice->currency_code,
            'currency_rate' => $invoice->currency_rate,
        ]);

        $this->dispatch(new CreateTransaction($request));

        $this->loginAs()
            ->get(route('transactions.index'))
            ->assertStatus(200)
            ->assertSeeText($invoice->document_number);
    }

    public function testItShouldSeeTransactionShowPage()
    {
        $request = $this->getRequest();

        $transaction = $this->dispatch(new CreateTransaction($request));

        $this->loginAs()
            ->get(route('transactions.show', $transaction->id))
            ->assertStatus(200)
            ->assertSee($transaction->contact->email);
    }

    public function testItShouldSeeTransactionCreatePage()
    {
        $this->loginAs()
            ->get(route('transactions.create', ['type' => 'income']))
            ->assertStatus(200)
            ->assertSeeText(trans('general.title.new', ['type' => trans_choice('general.incomes', 1)]));
    }

   public function testItShouldCreateTransaction()
    {
        $request = $this->getRequest();

        $this->loginAs()
            ->post(route('transactions.store'), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertDatabaseHas('transactions', $request);
    }

    public function testItShouldCreateTransactionWithIsoDateThroughApi()
    {
        $request = $this->getRequest();
        $request['paid_at'] = '2025-12-22 00:00:00';

        $this->withHeaders([
                'Authorization' => 'Basic ' . base64_encode('test@company.com:123456'),
            ])
            ->postJson(route('api.transactions.store'), $request)
            ->assertStatus(201);

        $transaction = Transaction::where('number', $request['number'])->firstOrFail();

        $this->assertSame('2025-12-22', $transaction->paid_at->format('Y-m-d'));
    }

    public function testItShouldCreateTransactionWithRecurring()
    {
        $request = $this->getRequest(true);

        $this->loginAs()
            ->post(route('recurring-transactions.store'), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertDatabaseHas('transactions', [
            'type' => 'income-recurring',
            'paid_at' => $request['recurring_started_at'],
            'amount' => $request['amount'],
        ]);
    }

    public function testItShouldSendTransactionEmail()
    {
        NotificationFacade::fake();

        $transaction = $this->dispatch(new CreateTransaction($this->getRequest()));

        $this->loginAs()
            ->post(route('modals.transactions.emails.store', $transaction->id), $this->getEmailRequest($transaction))
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        NotificationFacade::assertSentTo($transaction->contact, Notification::class);
    }

    public function testItShouldHitRateLimitForSendTransactionEmail()
    {
        NotificationFacade::fake();

        $limit_per_minute = (int) config('app.throttles.email.minute');

        $transaction = $this->dispatch(new CreateTransaction($this->getRequest()));

        for ($i = 0; $i < $limit_per_minute; $i++) {
            $this->loginAs()
                ->post(route('modals.transactions.emails.store', $transaction->id), $this->getEmailRequest($transaction));
        }

        $this->loginAs()
            ->post(route('modals.transactions.emails.store', $transaction->id), $this->getEmailRequest($transaction))
            ->assertJson([
                'success' => false,
            ]);

        NotificationFacade::assertSentTimes(Notification::class, $limit_per_minute);
    }

    public function testItShouldSeeTransactionUpdatePage()
    {
        $request = $this->getRequest();

        $transaction = $this->dispatch(new CreateTransaction($request));

        $this->loginAs()
            ->get(route('transactions.edit', $transaction->id))
            ->assertStatus(200)
            ->assertSee($transaction->amount);
    }

    public function testItShouldUpdateTransaction()
    {
        $request = $this->getRequest();

        $transaction = $this->dispatch(new CreateTransaction($request));

        $request['amount'] = $this->faker->randomFloat(2, 1, 1000);

        $this->loginAs()
            ->patch(route('transactions.update', $transaction->id), $request)
            ->assertStatus(200)
            ->assertSee($request['amount']);

        $this->assertFlashLevel('success');

        $this->assertDatabaseHas('transactions', $request);
    }

    public function testItShouldDeleteTransaction()
    {
        $request = $this->getRequest();

        $transaction = $this->dispatch(new CreateTransaction($request));

        $this->loginAs()
            ->delete(route('transactions.destroy', $transaction->id))
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertSoftDeleted('transactions', $request);
    }

    public function testItShouldExportTransactions()
    {
        $count = 5;
        Transaction::factory()->income()->count($count)->create();

        Excel::fake();

        $this->loginAs()
            ->get(route('transactions.export'))
            ->assertStatus(200);

        Excel::matchByRegex();

        Excel::assertDownloaded(
            '/' . str()->filename(trans_choice('general.transactions', 2)) . '-\d{10}\.xlsx/',
            function (Export $export) use ($count) {
                // Assert that the correct export is downloaded.
                return $export->collection()->count() === $count;
            }
        );
    }

    public function testItShouldExportSelectedTransactions()
    {
        $create_count = 5;
        $select_count = 3;

        $transactions = Transaction::factory()->income()->count($create_count)->create();

        Excel::fake();

        $this->loginAs()
            ->post(
                route('bulk-actions.action', ['group' => 'banking', 'type' => 'transactions']),
                ['handle' => 'export', 'selected' => $transactions->take($select_count)->pluck('id')->toArray()]
            )
            ->assertStatus(200);

        Excel::matchByRegex();

        Excel::assertDownloaded(
            '/' . str()->filename(trans_choice('general.transactions', 2)) . '-\d{10}\.xlsx/',
            function (Export $export) use ($select_count) {
                return $export->collection()->count() === $select_count;
            }
        );
    }

    public function testItShouldImportTransactions()
    {
        Excel::fake();

        $this->loginAs()
            ->post(
                route('transactions.import'),
                [
                    'import' => UploadedFile::fake()->createWithContent(
                        'transactions.xlsx',
                        File::get(public_path('files/import/transactions.xlsx'))
                    ),
                ]
            )
            ->assertStatus(200);

        Excel::assertImported('transactions.xlsx');

        $this->assertFlashLevel('success');
    }

    public function getRequest($recurring = false)
    {
        $factory = Transaction::factory();

        $factory = $recurring ? $factory->income()->recurring() : $factory->income();

        return $factory->raw();
    }

    public function getEmailRequest($transaction)
    {
        $email_template = config('type.transaction.' . $transaction->type . '.email_template');

        $notification = new Notification($transaction, $email_template, true);

        return [
            'transaction_id'    => $transaction->id,
            'to'                => [$transaction->contact->email],
            'subject'           => $notification->getSubject(),
            'body'              => $notification->getBody(),
        ];
    }

    private function createInvoice(): Document
    {
        $request = Document::factory()->invoice()->items()->raw([
            'status' => 'sent',
        ]);

        return $this->dispatch(new CreateDocument($request));
    }
}
