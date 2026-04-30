<?php

namespace Tests\Feature\Banking;

use App\Jobs\Banking\CreateTransactionTaxes;
use App\Models\Banking\Transaction;
use App\Models\Setting\Tax;
use Illuminate\Support\Facades\Log;
use Tests\Feature\FeatureTestCase;

class TransactionTaxesTest extends FeatureTestCase
{
    /**
     * A known tax type must be applied and returned.
     */
    public function testItShouldApplyKnownTaxTypes(): void
    {
        $transaction = Transaction::factory()->income()->create();

        $tax = Tax::factory()->create(['type' => 'normal', 'rate' => 10, 'enabled' => 1]);

        $request = $this->buildRequest($transaction, [$tax->id]);

        $result = $this->dispatch(new CreateTransactionTaxes($transaction, $request));

        $this->assertNotFalse($result);
        $this->assertCount(1, $result);
        $this->assertEquals($tax->id, $result->first()->tax_id);
    }

    /**
     * Regression test for M-5: an unknown tax type must not silently disappear.
     * Instead it must be logged via report() and skipped, while other
     * valid taxes in the same request are still processed.
     */
    public function testItShouldReportUnknownTaxTypeAndSkipIt(): void
    {
        $transaction = Transaction::factory()->income()->create();

        // Unknown type — not in [inclusive, fixed, normal, withholding, compound]
        $unknown = Tax::factory()->create(['type' => 'custom_unknown', 'rate' => 10, 'enabled' => 1]);

        // Valid tax that should still be processed
        $normal  = Tax::factory()->create(['type' => 'normal', 'rate' => 5, 'enabled' => 1]);

        $reported = false;
        $originalHandler = set_exception_handler(null);

        // Capture report() calls without actually throwing
        app()->singleton(\Illuminate\Contracts\Debug\ExceptionHandler::class, function () use (&$reported) {
            return new class($reported) extends \Illuminate\Foundation\Exceptions\Handler {
                public function __construct(private bool &$reported) {
                    parent::__construct(app(\Illuminate\Contracts\Container\Container::class));
                }
                public function report(\Throwable $e): void { $this->reported = true; }
                public function render($request, \Throwable $e) { return response('', 500); }
            };
        });

        $request = $this->buildRequest($transaction, [$unknown->id, $normal->id]);

        $result = $this->dispatch(new CreateTransactionTaxes($transaction, $request));

        // Unknown tax skipped → only the normal tax is in the result
        $this->assertCount(1, $result);
        $this->assertEquals($normal->id, $result->first()->tax_id);
    }

    /**
     * A non-existent tax_id must be silently skipped — result is an empty collection.
     */
    public function testItShouldSkipNonExistentTaxId(): void
    {
        $transaction = Transaction::factory()->income()->create();

        $request = $this->buildRequest($transaction, [999999]);

        $result = $this->dispatch(new CreateTransactionTaxes($transaction, $request));

        // tax_ids was provided but no tax found → DB::transaction runs, returns empty collection
        $this->assertCount(0, $result);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function buildRequest(Transaction $transaction, array $tax_ids): array
    {
        return [
            'amount'       => $transaction->amount,
            'tax_ids'      => $tax_ids,
            'created_from' => 'core::test',
            'created_by'   => $transaction->created_by ?? 1,
        ];
    }
}
