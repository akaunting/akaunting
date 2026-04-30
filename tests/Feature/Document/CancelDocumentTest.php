<?php

namespace Tests\Feature\Document;

use App\Jobs\Document\CancelDocument;
use App\Jobs\Document\CreateDocument;
use App\Models\Banking\Transaction;
use App\Models\Document\Document;
use Tests\Feature\FeatureTestCase;

class CancelDocumentTest extends FeatureTestCase
{
    /**
     * Regression test for C-2: CancelDocument must refuse to cancel a document
     * that has reconciled transactions, just like DeleteDocument does.
     *
     * Before the fix, CancelDocument had no authorize() guard — it silently
     * deleted reconciled bank transactions when cancelling the document.
     */
    public function testItShouldThrowWhenCancellingDocumentWithReconciledTransaction(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/reconciled/i');

        $bill = $this->createBill();

        // Attach a reconciled expense transaction to the bill.
        Transaction::factory()->expense()->create([
            'document_id' => $bill->id,
            'reconciled'  => 1,
        ]);

        $this->dispatch(new CancelDocument($bill));
    }

    /**
     * A bill with NO reconciled transactions must cancel without error.
     */
    public function testItShouldCancelDocumentWithoutReconciledTransactions(): void
    {
        $bill = $this->createBill();

        // Non-reconciled transaction — cancellation must be allowed.
        Transaction::factory()->expense()->create([
            'document_id' => $bill->id,
            'reconciled'  => 0,
        ]);

        $cancelled = $this->dispatch(new CancelDocument($bill));

        $this->assertSame('cancelled', $cancelled->status);
        $this->assertDatabaseHas('documents', [
            'id'     => $bill->id,
            'status' => 'cancelled',
        ]);
    }

    /**
     * After a successful cancellation all linked (non-reconciled) transactions
     * and the recurring record must be removed.
     */
    public function testItShouldDeleteTransactionsOnCancelSuccess(): void
    {
        $bill = $this->createBill();

        $transaction = Transaction::factory()->expense()->create([
            'document_id' => $bill->id,
            'reconciled'  => 0,
        ]);

        $this->dispatch(new CancelDocument($bill));

        $this->assertSoftDeleted('transactions', ['id' => $transaction->id]);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function createBill(): Document
    {
        $request = Document::factory()->bill()->items()->raw([
            'status' => 'received',
        ]);

        return $this->dispatch(new CreateDocument($request));
    }
}
