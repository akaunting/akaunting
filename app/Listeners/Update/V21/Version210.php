<?php

namespace App\Listeners\Update\V21;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use App\Models\Common\Company;
use App\Models\Document\Document;
use App\Models\Setting\Category;
use App\Utilities\Overrider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class Version210 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '2.1.0';

    private $tables = [
        Document::INVOICE_TYPE => [
            'estimate_invoice',
            'foriba_earchive_one_steps',
            'foriba_earchive_three_steps',
            'foriba_earchive_two_steps',
            'foriba_incoming_invoice_histories',
            'foriba_invoices',
            'inventory_invoice_items',
            'iyzico_invoice_refunds',
            'iyzico_invoices',
            'iyzico_order',
            'nesbilgi_bill_histories',
            'nesbilgi_earchive_one_steps',
            'nesbilgi_earchive_three_steps',
            'nesbilgi_earchive_two_steps',
            'nesbilgi_invoice_histories',
            'nesbilgi_invoices',
            'project_invoices',
        ],
        Document::BILL_TYPE => [
            'foriba_incoming_invoice_histories',
            'inventory_bill_items',
            'nesbilgi_bill_histories',
            'project_bills',
        ],
        self::ESTIMATE_TYPE => [
            'proposals',
            'estimate_invoice',
        ],
        self::CREDIT_NOTE_TYPE => [],
        self::DEBIT_NOTE_TYPE => []
    ];

    private const ESTIMATE_TYPE = 'estimate';
    private const CREDIT_NOTE_TYPE = 'credit-note';
    private const DEBIT_NOTE_TYPE = 'debit-note';

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        if ($this->skipThisUpdate($event)) {
            return;
        }

        $this->updateCompanies();

        Artisan::call('migrate', ['--force' => true]);

        $this->copyDocuments();

        #todo remove tax_id column
        $this->copyItemTax();
    }

    private function copyDocuments()
    {
        try {
            $this->addForeignKeys();

            DB::transaction(function () {
                $totals = collect($this->getTotals(['invoices', 'bills', 'estimates', 'credit_notes', 'debit_notes']));

                // Sort table's count by ascending to improve performance.
                foreach ($totals->sort() as $table => $count) {
                    $method = 'copy' . Str::studly($table);
                    $this->$method();
                }

                $this->updateCreditNoteTransactionsTable();
            });

        } catch (\Exception $e) {
            $this->revertTableRenaming();

            Log::error($e);
        } finally {
            $this->removeForeignKeys();
            foreach (['estimate', 'bill', 'invoice'] as $item) {
                $this->removeDocumentIdForeignKeys($item);
            }
        }
    }

    public function updateCreditNoteTransactionsTable(): void
    {
        if (!Schema::hasTable('credits_transactions')) {
            return;
        }

        $invoices = DB::table('credits_transactions')
                      ->join('invoices_v20', 'credits_transactions.document_id', '=', 'invoices_v20.id')
                      ->where('credits_transactions.type', 'expense')
                      ->get(
                          [
                              'credits_transactions.id as credits_transactions_id',
                              'invoices_v20.company_id',
                              'invoice_number',
                              'invoices_v20.deleted_at',
                          ]
                      );

        foreach ($invoices as $invoice) {
            $documentId = DB::table('documents')
                            ->where('document_number', $invoice->invoice_number)
                            ->where('deleted_at', $invoice->deleted_at)
                            ->where('company_id', $invoice->company_id)
                            ->where('type', Document::INVOICE_TYPE)
                            ->pluck('id')
                            ->first();

            DB::table('credits_transactions')
              ->where('id', $invoice->credits_transactions_id)->update(['document_id' => $documentId]);
        }

        $credit_notes = DB::table('credits_transactions')
                          ->join('credit_notes_v20', 'credits_transactions.document_id', '=', 'credit_notes_v20.id')
                          ->where('credits_transactions.type', 'income')
                          ->get(
                              [
                                  'credits_transactions.id as credits_transactions_id',
                                  'credit_notes_v20.company_id',
                                  'credit_note_number',
                                  'credit_notes_v20.deleted_at',
                              ]
                          );

        foreach ($credit_notes as $credit_note) {
            $documentId = DB::table('documents')
                            ->where('document_number', $credit_note->credit_note_number)
                            ->where('deleted_at', $credit_note->deleted_at)
                            ->where('company_id', $credit_note->company_id)
                            ->where('type', self::CREDIT_NOTE_TYPE)
                            ->pluck('id')
                            ->first();

            DB::table('credits_transactions')
              ->where('id', $credit_note->credits_transactions_id)->update(['document_id' => $documentId]);
        }
    }

    private function revertTableRenaming(): void
    {
        $tables = [
            'bill_histories',
            'bill_item_taxes',
            'bill_items',
            'bill_totals',
            'bills',
            'credit_note_histories',
            'credit_note_item_taxes',
            'credit_note_items',
            'credit_note_totals',
            'credit_notes',
            'debit_note_histories',
            'debit_note_item_taxes',
            'debit_note_items',
            'debit_note_totals',
            'debit_notes',
            'estimate_histories',
            'estimate_item_taxes',
            'estimate_items',
            'estimate_totals',
            'estimates',
            'invoice_histories',
            'invoice_item_taxes',
            'invoice_items',
            'invoice_totals',
            'invoices',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable("{$table}_v20")) {
                Schema::rename("{$table}_v20", $table);
            }
        }
    }

    private function getTotals(array $tables): array
    {
        $counts = [];
        foreach ($tables as $table) {
            if (!Schema::hasTable($table)) {
                continue;
            }

            $counts[$table] = DB::table($table)->count();
        }

        return $counts;
    }

    private function batchCopyRelations(string $table, string $type): void
    {
        $offset = 0;
        $limit = 500000;
        $new_table = Str::replaceFirst(Str::replaceFirst('-', '_', $type), 'document', $table);

        // To be able to update relation ids
        if (in_array($new_table, ['document_items', 'documents']) && DB::table($new_table)->count() > 0) {
            // Delete document's items which are not found in documents table by document_id
            $builder = DB::table('document_items')
                         ->join('documents', 'documents.id', '=', 'document_items.document_id', 'left')
                         ->whereNull('documents.id');

            if ($builder->count()) {
                $builder->delete();
            }

            $documentType = DB::table($new_table)->orderBy('id')->pluck('type')->first();

            // To be able to update TYPE_id relations
            $this->addDocumentIdForeignKeys($documentType);

            // Update relation ids
            DB::table($new_table)
              ->orderByDesc('id')
              ->increment('id', DB::table($table)->orderByDesc('id')->pluck('id')->first());
        }

        $insertColumns = collect(Schema::getColumnListing($new_table));

        $insertColumns = $insertColumns->reject(function ($value) use ($new_table, $table) {
            // Remove only primary keys
            if ($value === 'id' && !in_array($new_table, ['document_items', 'documents'])) {
                return true;
            }

            if ($value === 'description' && $new_table === 'document_items') {
                return true;
            }

            if ($value === 'footer' && in_array($table, ['bills', 'debit_notes'])) {
                return true;
            }

            if ($value === 'order_number' && in_array($table, ['estimates', 'credit_notes', 'debit_notes'])) {
                return true;
            }

            if ($value === 'parent_id' && in_array($table, ['estimates', 'credit_notes', 'debit_notes'])) {
                return true;
            }

            if ($table === 'estimate_items' && in_array($value, ['discount_type', 'discount_rate'])) {
                return true;
            }

            return false;
        });

        $selectColumns = $insertColumns->map(function ($column) use($type) {
            if ($column === 'type') {
                return "'$type'";
            }

            if (Str::contains($column, 'document')) {
                return Str::replaceFirst('document', Str::replaceFirst('-', '_', $type), $column) . ' as ' . $column;
            }

            if ($column === 'issued_at') {
                switch ($type) {
                    case Document::INVOICE_TYPE:
                        return "invoiced_at as $column";
                    case Document::BILL_TYPE:
                        return "billed_at as $column";
                    case self::ESTIMATE_TYPE:
                        return "estimated_at as $column";
                    case self::DEBIT_NOTE_TYPE:
                    case self::CREDIT_NOTE_TYPE:
                        return "issued_at as $column";
                    default:
                        return $column;
                }
            }

            // due_at column should not be null so we need fill it for the modules that don't have due_at column.
            if ($column === 'due_at') {
                switch ($type) {
                    case self::ESTIMATE_TYPE:
                        return 'estimated_at';
                    case self::DEBIT_NOTE_TYPE:
                    case self::CREDIT_NOTE_TYPE:
                        return 'issued_at';
                    default:
                        return $column;
                }
            }

            return $column;
        });

        $builder = DB::table($table)->selectRaw($selectColumns->implode(','))->limit($limit)->offset($offset);

        while ($builder->cursor()->count()) {
            Schema::disableForeignKeyConstraints();
            DB::table($new_table)->insertUsing($insertColumns->toArray(), $builder);
            Schema::enableForeignKeyConstraints();

            $offset += $limit;
            $builder->limit($limit)->offset($offset);
        }

        Schema::rename($table, "{$table}_v20");
    }

    private function copyInvoices(): void
    {
        $this->batchCopyRelations('invoices', Document::INVOICE_TYPE);
        $this->batchCopyRelations('invoice_items', Document::INVOICE_TYPE);
        $this->batchCopyRelations('invoice_item_taxes', Document::INVOICE_TYPE);
        $this->batchCopyRelations('invoice_histories', Document::INVOICE_TYPE);
        $this->batchCopyRelations('invoice_totals', Document::INVOICE_TYPE);
    }

    private function copyBills(): void
    {
        $this->batchCopyRelations('bills', Document::BILL_TYPE);
        $this->batchCopyRelations('bill_items', Document::BILL_TYPE);
        $this->batchCopyRelations('bill_item_taxes', Document::BILL_TYPE);
        $this->batchCopyRelations('bill_histories', Document::BILL_TYPE);
        $this->batchCopyRelations('bill_totals', Document::BILL_TYPE);
    }

    private function copyEstimates(): void
    {
        $has_estimates = Schema::hasTable('estimates');

        if ($has_estimates === false) {
            return;
        }

        $this->batchCopyRelations('estimates', self::ESTIMATE_TYPE);
        $this->batchCopyRelations('estimate_items', self::ESTIMATE_TYPE);
        $this->batchCopyRelations('estimate_item_taxes', self::ESTIMATE_TYPE);
        $this->batchCopyRelations('estimate_histories', self::ESTIMATE_TYPE);
        $this->batchCopyRelations('estimate_totals', self::ESTIMATE_TYPE);
    }

    private function copyCreditNotes(): void
    {
        $has_credit_notes = Schema::hasTable('credit_notes');

        if ($has_credit_notes === false) {
            return;
        }

        $this->batchCopyRelations('credit_notes', self::CREDIT_NOTE_TYPE);
        $this->batchCopyRelations('credit_note_items', self::CREDIT_NOTE_TYPE);
        $this->batchCopyRelations('credit_note_item_taxes', self::CREDIT_NOTE_TYPE);
        $this->batchCopyRelations('credit_note_histories', self::CREDIT_NOTE_TYPE);
        $this->batchCopyRelations('credit_note_totals', self::CREDIT_NOTE_TYPE);
    }

    private function copyDebitNotes(): void
    {
        $has_debit_notes = Schema::hasTable('debit_notes');

        if ($has_debit_notes === false) {
            return;
        }

        $this->batchCopyRelations('debit_notes', self::DEBIT_NOTE_TYPE);
        $this->batchCopyRelations('debit_note_items', self::DEBIT_NOTE_TYPE);
        $this->batchCopyRelations('debit_note_item_taxes', self::DEBIT_NOTE_TYPE);
        $this->batchCopyRelations('debit_note_histories', self::DEBIT_NOTE_TYPE);
        $this->batchCopyRelations('debit_note_totals', self::DEBIT_NOTE_TYPE);
    }

    private function addForeignKeys(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::table(
            'document_items',
            function (Blueprint $table) {
                $table->unsignedInteger('id')->change();
            }
        );

        Schema::table(
            'documents',
            function (Blueprint $table) {
                $table->unsignedInteger('id')->change();
            }
        );

        Schema::table(
            'document_histories',
            function (Blueprint $table) {
                $table->foreign('document_id')
                      ->references('id')
                      ->on('documents')
                      ->cascadeOnUpdate();
            }
        );

        Schema::table(
            'document_items',
            function (Blueprint $table) {
                $table->foreign('document_id')
                      ->references('id')
                      ->on('documents')
                      ->cascadeOnUpdate();
            }
        );

        Schema::table(
            'document_item_taxes',
            function (Blueprint $table) {
                $table->foreign('document_id')
                      ->references('id')
                      ->on('documents')
                      ->cascadeOnUpdate();

                $table->foreign('document_item_id')
                      ->references('id')
                      ->on('document_items')
                      ->cascadeOnUpdate()
                      ->cascadeOnDelete();
            }
        );

        Schema::table(
            'document_totals',
            function (Blueprint $table) {
                $table->foreign('document_id')
                      ->references('id')
                      ->on('documents')
                      ->cascadeOnUpdate();
            }
        );

        Schema::enableForeignKeyConstraints();
    }

    private function removeForeignKeys(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::table(
            'document_histories',
            function (Blueprint $table) {
                $table->dropForeign(['document_id']);
            }
        );

        Schema::table(
            'document_items',
            function (Blueprint $table) {
                $table->dropForeign(['document_id']);
            }
        );

        Schema::table(
            'document_item_taxes',
            function (Blueprint $table) {
                $table->dropForeign(['document_id']);
                $table->dropForeign(['document_item_id']);
            }
        );

        Schema::table(
            'document_totals',
            function (Blueprint $table) {
                $table->dropForeign(['document_id']);
            }
        );

        Schema::table(
            'documents',
            function (Blueprint $table) {
                $table->increments('id')->change();
            }
        );

        Schema::table(
            'document_items',
            function (Blueprint $table) {
                $table->increments('id')->change();
            }
        );

        Schema::enableForeignKeyConstraints();
    }

    private function addDocumentIdForeignKeys(string $type): void
    {
        Schema::disableForeignKeyConstraints();

        foreach ($this->tables[$type] as $table) {
            if (!Schema::hasTable($table)) {
                continue;
            }

            $column = "{$type}_id";
            if ($table === 'proposals') {
                $column = 'estimates_id';
            }

            Schema::table(
                $table,
                function (Blueprint $table) use ($column) {
                    $table->unsignedInteger($column)->change();
                }
            );

            Schema::table(
                $table,
                function (Blueprint $table) use ($column) {
                    $connection = Schema::getConnection();
                    $d_table = $connection->getDoctrineSchemaManager()->listTableDetails(
                        $connection->getTablePrefix() . $table->getTable()
                    );

                    if (!$d_table->hasForeignKey("{$connection->getTablePrefix()}{$table->getTable()}_{$column}_foreign")) {
                        $table->foreign($column)
                              ->references('id')
                              ->on('documents')
                              ->cascadeOnUpdate();
                    }
                }
            );
        }

        Schema::enableForeignKeyConstraints();
    }

    private function removeDocumentIdForeignKeys(string $type): void
    {
        Schema::disableForeignKeyConstraints();

        foreach ($this->tables[$type] as $table) {
            if (!Schema::hasTable($table)) {
                continue;
            }

            $column = "{$type}_id";
            if ($table === 'proposals') {
                $column = 'estimates_id';
            }

            Schema::table(
                $table,
                function (Blueprint $table) use ($column) {
                    $connection = Schema::getConnection();
                    $d_table = $connection->getDoctrineSchemaManager()->listTableDetails(
                        $connection->getTablePrefix() . $table->getTable()
                    );

                    if ($d_table->hasForeignKey("{$connection->getTablePrefix()}{$table->getTable()}_{$column}_foreign")) {
                        $table->dropForeign([$column]);
                    }
                }
            );

            Schema::table(
                $table,
                function (Blueprint $table) use ($column) {
                    $table->integer($column)->change();
                }
            );
        }

        Schema::enableForeignKeyConstraints();
    }

    protected function updateCompanies()
    {
        $company_id = session('company_id');

        $companies = Company::cursor();

        foreach ($companies as $company) {
            session(['company_id' => $company->id]);

            $this->updateSettings($company);
        }

        setting()->forgetAll();

        session(['company_id' => $company_id]);

        Overrider::load('settings');
    }

    public function updateSettings($company)
    {
        $income_category = Category::income()->enabled()->first();
        $expense_category = Category::expense()->enabled()->first();

        // Set the active company settings
        setting()->setExtraColumns(['company_id' => $company->id]);
        setting()->forgetAll();
        setting()->load(true);

        setting()->set(['default.income_category' => setting('default.income_category', $income_category->id)]);
        setting()->set(['default.expense_category' => setting('default.expense_category', $expense_category->id)]);

        setting()->save();
    }

    public function copyItemTax()
    {
        $items = DB::table('items')->cursor();

        DB::transaction(function () use ($items) {
            foreach ($items as $item) {
                DB::table('item_taxes')->insert(
                    [
                        'company_id' => $item->company_id,
                        'item_id'    => $item->id,
                        'tax_id'     => $item->tax_id,
                        'created_at' => $item->created_at,
                        'updated_at' => $item->updated_at,
                        'deleted_at' => $item->deleted_at,
                    ]
                );
            }
        });
    }
}
