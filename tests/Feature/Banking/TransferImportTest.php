<?php

namespace Tests\Feature\Banking;

use App\Exports\Banking\Transactions as TransactionsExport;
use App\Exports\Banking\Transfers as TransfersExport;
use App\Imports\Banking\Transactions as TransactionsImport;
use App\Imports\Banking\Transfers as TransfersImport;
use App\Jobs\Banking\CreateTransfer;
use App\Models\Banking\Transaction;
use App\Models\Banking\Transfer;
use App\Models\Setting\Category;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel as ExcelType;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\Feature\FeatureTestCase;

/**
 * The transactions export writes a transfer's two transactions as well as the transfers export the transfer. The
 * transfers import opened two new transactions for every transfer, so importing both files moved each transfer's
 * money twice, and the transactions import opened an income or expense category of the transfer category's name.
 */
class TransferImportTest extends FeatureTestCase
{
    protected array $files = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->loginAs();
    }

    protected function tearDown(): void
    {
        foreach ($this->files as $file) {
            @unlink($file);
        }

        parent::tearDown();
    }

    public function testItShouldExportTheNumbersOfTheTransfersTransactions()
    {
        $transfer = $this->createTransfer();

        $export = new TransfersExport();

        $row = array_combine($export->fields, $export->map($transfer));

        $this->assertSame($transfer->expense_transaction->number, $row['expense_transaction_number']);
        $this->assertSame($transfer->income_transaction->number, $row['income_transaction_number']);
    }

    public function testItShouldLinkAnImportedTransferToItsTransactions()
    {
        $transfer = $this->createTransfer();

        $file = $this->exportFile(new TransfersExport());

        DB::table('transfers')->where('id', $transfer->id)->delete();

        $this->assertImportedOnce($transfer, $file);
    }

    public function testItShouldLinkTheTransactionsOfAFileWithoutTheirNumbers()
    {
        $transfer = $this->createTransfer();

        $file = $this->exportFile(new TransfersExport());

        $this->dropColumns($file, ['expense_transaction_number', 'income_transaction_number']);

        DB::table('transfers')->where('id', $transfer->id)->delete();

        $this->assertImportedOnce($transfer, $file);
    }

    public function testItShouldNotImportATransferTwice()
    {
        $transfer = $this->createTransfer();

        $file = $this->exportFile(new TransfersExport());

        $this->assertImportedOnce($transfer, $file);
    }

    public function testItShouldImportATransfersTransactionsWithTheirCategory()
    {
        $transfer = $this->createTransfer();

        $expense = $transfer->expense_transaction;
        $income = $transfer->income_transaction;

        $file = $this->exportFile(new TransactionsExport());

        DB::table('transfers')->where('id', $transfer->id)->delete();
        DB::table('transactions')->whereIn('id', [$expense->id, $income->id])->delete();

        $categories = Category::withSubCategory()->count();

        Excel::import(new TransactionsImport(), $file);

        $this->assertSame($expense->category_id, Transaction::where('number', $expense->number)->value('category_id'));
        $this->assertSame($income->category_id, Transaction::where('number', $income->number)->value('category_id'));
        $this->assertSame($categories, Category::withSubCategory()->count());
    }

    /**
     * Import the transfers file: the transfer goes back to its two transactions, and no transaction is created, so
     * the next transaction number is not taken either.
     */
    protected function assertImportedOnce(Transfer $transfer, string $file): void
    {
        $transactions = Transaction::count();
        $next_number = setting('transaction.number_next');

        Excel::import(new TransfersImport(), $file);

        $this->assertSame(1, Transfer::count());
        $this->assertSame($transactions, Transaction::count());
        $this->assertEquals($next_number, setting('transaction.number_next'));

        $imported = Transfer::first();

        $this->assertSame($transfer->expense_transaction_id, $imported->expense_transaction_id);
        $this->assertSame($transfer->income_transaction_id, $imported->income_transaction_id);
    }

    protected function createTransfer(): Transfer
    {
        return $this->dispatch(new CreateTransfer(Transfer::factory()->raw()));
    }

    protected function exportFile($export): string
    {
        $file = tempnam(sys_get_temp_dir(), 'transfer-import') . '.xlsx';

        file_put_contents($file, Excel::raw($export, ExcelType::XLSX));

        return $this->files[] = $file;
    }

    /**
     * Take the given columns out of the file, as one written before them.
     */
    protected function dropColumns(string $file, array $columns): void
    {
        $book = IOFactory::load($file);

        $sheet = $book->getActiveSheet();

        $headings = $sheet->toArray(null, false, false, false)[0];

        foreach (array_reverse(array_keys($headings)) as $index) {
            if (in_array($headings[$index], $columns, true)) {
                $sheet->removeColumnByIndex($index + 1);
            }
        }

        (new Xlsx($book))->save($file);
    }
}
