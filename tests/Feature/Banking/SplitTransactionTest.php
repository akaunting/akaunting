<?php

namespace Tests\Feature\Banking;

use App\Jobs\Banking\CreateTransaction;
use App\Jobs\Banking\SplitTransaction;
use App\Jobs\Document\CreateDocument;
use App\Models\Banking\Transaction;
use App\Models\Document\Document;
use Tests\Feature\FeatureTestCase;

class SplitTransactionTest extends FeatureTestCase
{
    /**
     * Regression test for C-1.
     *
     * Original bug: `return` inside the DB::transaction closure exited on the
     * first item with no document_id, silently skipping subsequent items and
     * never updating the parent type.
     *
     * The correct behaviour: all items must have a valid document_id (enforced
     * by validateItems() before any DB write). When all items are valid, all
     * child transactions are created and the parent type changes.
     */
    public function testItShouldSplitTransactionAcrossMultipleBills(): void
    {
        [$bill1, $bill2] = $this->createTwoBills();

        $total = $bill1->amount + $bill2->amount;
        $parent = $this->createExpenseTransaction($total);

        $this->dispatch(new SplitTransaction($parent, [
            'items' => [
                ['amount' => $bill1->amount, 'document_id' => $bill1->id],
                ['amount' => $bill2->amount, 'document_id' => $bill2->id],
            ],
        ]));

        $this->assertSame(2, Transaction::where('split_id', $parent->id)->count());

        $parent->refresh();
        $this->assertSame(Transaction::EXPENSE_SPLIT_TYPE, $parent->type);
    }

    /**
     * Every split item MUST be linked to a document.
     * Direct API calls without document_id must be blocked before any DB write.
     */
    public function testItShouldThrowWhenItemHasNoDocumentId(): void
    {
        $this->expectException(\Exception::class);

        $parent = $this->createExpenseTransaction(200);

        $this->dispatch(new SplitTransaction($parent, [
            'items' => [
                ['amount' => 100],  // missing document_id
                ['amount' => 100],
            ],
        ]));
    }

    /**
     * validateItems() must throw BEFORE any DB write if document_id points
     * to a non-existent document (race condition / API direct call).
     */
    public function testItShouldThrowWhenDocumentNotFound(): void
    {
        $this->expectException(\Exception::class);

        $parent = $this->createExpenseTransaction(200);

        $this->dispatch(new SplitTransaction($parent, [
            'items' => [
                ['amount' => 100, 'document_id' => 99999999],
                ['amount' => 100, 'document_id' => 99999998],
            ],
        ]));
    }

    /**
     * checkAmount() must throw when item totals don't match the parent amount.
     */
    public function testItShouldThrowWhenItemAmountsDoNotMatchParent(): void
    {
        $this->expectException(\Exception::class);

        $parent = $this->createExpenseTransaction(300);

        $this->dispatch(new SplitTransaction($parent, [
            'items' => [
                ['amount' => 100, 'document_id' => 1],
                ['amount' => 100, 'document_id' => 2],
                // total 200 ≠ 300
            ],
        ]));
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function createTwoBills(): array
    {
        $bill1 = $this->dispatch(new CreateDocument(
            Document::factory()->bill()->items()->raw(['status' => 'received'])
        ));

        $bill2 = $this->dispatch(new CreateDocument(
            Document::factory()->bill()->items()->raw(['status' => 'received'])
        ));

        return [$bill1, $bill2];
    }

    private function createExpenseTransaction(float $amount): Transaction
    {
        return $this->dispatch(new CreateTransaction(
            Transaction::factory()->expense()->raw(['amount' => $amount])
        ));
    }
}
