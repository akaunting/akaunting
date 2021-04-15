<?php

namespace App\Listeners\Update\V21;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use App\Models\Common\Company;
use App\Models\Document\Document;
use App\Models\Setting\Category;
use App\Utilities\Overrider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class Version210 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '2.1.0';

    private $tableRelations = [
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
     * @var Collection
     */
    private $totals;

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

        $this->migrateDocuments();

        #todo remove tax_id column
        $this->copyItemTax();

        $this->deleteOldFiles();
    }

    private function migrateDocuments()
    {
        try {
            $this->removeAutoIncrements();
            $this->addForeignKeys();

            DB::transaction(function () {
            $this->totals = $this->getTotals(['invoice', 'bill', 'estimate', 'credit_note', 'debit_note']);

                // Sort table's count by ascending to improve performance.
            foreach ($this->totals->sortBy('count') as $total) {
                $method = 'copy' . Str::plural(Str::studly($total->type));
                    $this->$method();
                }

                $this->updateDocumentIds();
            });

            $this->renameTables();
        } catch (\Exception $e) {
            $this->revertTableRenaming();

            Log::error($e);
        } finally {
            $this->addAutoIncrements();
            $this->removeForeignKeys();
            foreach (['estimate', 'bill', 'invoice'] as $item) {
                $this->removeDocumentIdForeignKeys($item);
            }
        }
    }

    private function updateInvoiceIds(): void
    {
        // Invoice ids did not changed
        if ('invoice' === $this->totals->sortByDesc('count')->pluck('type')->first()) {
            return;
        }

        $incrementAmount = $this->getIncrementAmount('invoice', 's');

        if (0 === $incrementAmount) {
            return;
        }

        DB::table('documents')
          ->where('type', 'invoice')
          ->whereNotNull('parent_id')
          ->where('parent_id', '<>', 0)
          ->increment('parent_id', $incrementAmount);

        DB::table('transactions')
          ->where('type', 'income')
          ->whereNotNull('document_id')
          ->where('document_id', '<>', 0)
          ->increment('document_id', $incrementAmount);
    }

    private function updateBillIds(): void
    {
        // Bill ids did not changed
        if ('bill' === $this->totals->sortByDesc('count')->pluck('type')->first()) {
            return;
        }

        $incrementAmount = $this->getIncrementAmount('bill', 's');

        if (0 === $incrementAmount) {
            return;
        }

        DB::table('documents')
          ->where('type', 'bill')
          ->whereNotNull('parent_id')
          ->where('parent_id', '<>', 0)
          ->increment('parent_id', $incrementAmount);

        DB::table('transactions')
          ->where('type', 'expense')
          ->whereNotNull('document_id')
          ->where('document_id', '<>', 0)
          ->increment('document_id', $incrementAmount);
    }

    private function updateDocumentIds()
    {
        $this->totals = $this->getTotals();
        $this->updateInvoiceIds();
        $this->updateBillIds();
        $this->updateCreditNoteTransactionsTable();

        $tables = [
            'recurring'                  => 'recurable',
            'mediables'                  => 'mediable',
            'project_activities'         => 'activity',
            'custom_fields_field_values' => 'model',
            'double_entry_ledger'        => 'ledgerable',
            'inventory_histories'        => 'type',
            'magento_integrations'       => 'item',
            'opencart_integrations'      => 'item',
            'prestashop_integrations'    => 'item',
            'woocommerce_integrations'   => 'item',
        ];

        foreach ($tables as $table => $column) {
            if (Schema::hasTable($table) === false || DB::table($table)->count() === 0) {
                continue;
            }

            $classes = [
                'invoices'               => [
                    'sort_key'     => 'invoice',
                    'table_suffix' => 's',
                    'search'       => [
                        'App\Models\Sale\Invoice',
                        'App\Models\Income\Invoice',
                    ],
                    'replacement'  => 'App\Models\Document\Document',
                ],
                'invoice_items'          => [
                    'sort_key'     => 'invoice',
                    'table_suffix' => '_items',
                    'search'       => [
                        'App\Models\Sale\InvoiceItem',
                        'App\Models\Income\InvoiceItem',
                    ],
                    'replacement'  => 'App\Models\Document\DocumentItem',
                ],
                'invoice_item_taxes'     => [
                    'sort_key'     => 'invoice',
                    'table_suffix' => '_item_taxes',
                    'search'       => [
                        'App\Models\Sale\InvoiceItemTax',
                        'App\Models\Income\InvoiceItemTax',
                    ],
                    'replacement'  => 'App\Models\Document\DocumentItemTax',
                ],
                'invoice_totals'         => [
                    'sort_key'     => 'invoice',
                    'table_suffix' => '_totals',
                    'search'       => [
                        'App\Models\Sale\InvoiceTotal',
                        'App\Models\Income\InvoiceTotal',
                    ],
                    'replacement'  => 'App\Models\Document\DocumentTotal',
                ],
                'invoice_histories'      => [
                    'sort_key'     => 'invoice',
                    'table_suffix' => '_histories',
                    'search'       => [
                        'App\Models\Sale\InvoiceHistory',
                        'App\Models\Income\InvoiceHistory',
                    ],
                    'replacement'  => 'App\Models\Document\DocumentHistory',
                ],
                'bills'                  => [
                    'sort_key'     => 'bill',
                    'table_suffix' => 's',
                    'search'       => [
                        'App\Models\Purchase\Bill',
                        'App\Models\Expense\Bill',
                    ],
                    'replacement'  => 'App\Models\Document\Document',
                ],
                'bill_items'             => [
                    'sort_key'     => 'bill',
                    'table_suffix' => '_items',
                    'search'       => [
                        'App\Models\Purchase\BillItem',
                        'App\Models\Expense\BillItem',
                    ],
                    'replacement'  => 'App\Models\Document\DocumentItem',
                ],
                'bill_item_taxes'        => [
                    'sort_key'     => 'bill',
                    'table_suffix' => '_item_taxes',
                    'search'       => [
                        'App\Models\Purchase\BillItemTax',
                        'App\Models\Expense\BillItemTax',
                    ],
                    'replacement'  => 'App\Models\Document\DocumentItemTax',
                ],
                'bill_totals'            => [
                    'sort_key'     => 'bill',
                    'table_suffix' => '_totals',
                    'search'       => [
                        'App\Models\Purchase\BillTotal',
                        'App\Models\Expense\BillTotal',
                    ],
                    'replacement'  => 'App\Models\Document\DocumentTotal',
                ],
                'bill_histories'         => [
                    'sort_key'     => 'bill',
                    'table_suffix' => '_histories',
                    'search'       => [
                        'App\Models\Purchase\BillHistory',
                        'App\Models\Expense\BillHistory',
                    ],
                    'replacement'  => 'App\Models\Document\DocumentHistory',
                ],
                'estimates'              => [
                    'sort_key'     => 'estimate',
                    'table_suffix' => 's',
                    'search'       => [
                        'Modules\Estimates\Models\Estimate',
                    ],
                ],
                'estimate_items'         => [
                    'sort_key'     => 'estimate',
                    'table_suffix' => '_items',
                    'search'       => [
                        'Modules\Estimates\Models\EstimateItem',
                    ],
                ],
                'estimate_item_taxes'    => [
                    'sort_key'     => 'estimate',
                    'table_suffix' => '_item_taxes',
                    'search'       => [
                        'Modules\Estimates\Models\EstimateItemTax',
                    ],
                ],
                'estimate_totals'        => [
                    'sort_key'     => 'estimate',
                    'table_suffix' => '_totals',
                    'search'       => [
                        'Modules\Estimates\Models\EstimateTotal',
                    ],
                ],
                'estimate_histories'     => [
                    'sort_key'     => 'estimate',
                    'table_suffix' => '_histories',
                    'search'       => [
                        'Modules\Estimates\Models\EstimateHistory',
                    ],
                ],
                'credit_notes'           => [
                    'sort_key'     => 'credit_note',
                    'table_suffix' => 's',
                    'search'       => [
                        'Modules\CreditDebitNotes\Models\CreditNote',
                    ],
                ],
                'credit_note_items'      => [
                    'sort_key'     => 'credit_note',
                    'table_suffix' => '_items',
                    'search'       => [
                        'Modules\CreditDebitNotes\Models\CreditNoteItem',
                    ],
                ],
                'credit_note_item_taxes' => [
                    'sort_key'     => 'credit_note',
                    'table_suffix' => '_item_taxes',
                    'search'       => [
                        'Modules\CreditDebitNotes\Models\CreditNoteItemTax',
                    ],
                ],
                'credit_note_totals'     => [
                    'sort_key'     => 'credit_note',
                    'table_suffix' => '_totals',
                    'search'       => [
                        'Modules\CreditDebitNotes\Models\CreditNoteTotal',
                    ],
                ],
                'credit_note_histories'  => [
                    'sort_key'     => 'credit_note',
                    'table_suffix' => '_histories',
                    'search'       => [
                        'Modules\CreditDebitNotes\Models\CreditNoteHistory',
                    ],
                ],
                'debit_notes'            => [
                    'sort_key'     => 'debit_note',
                    'table_suffix' => 's',
                    'search'       => [
                        'Modules\CreditDebitNotes\Models\DebitNote',
                    ],
                ],
                'debit_note_items'       => [
                    'sort_key'     => 'debit_note',
                    'table_suffix' => '_items',
                    'search'       => [
                        'Modules\CreditDebitNotes\Models\DebitNoteItem',
                    ],
                ],
                'debit_note_item_taxes'  => [
                    'sort_key'     => 'debit_note',
                    'table_suffix' => '_item_taxes',
                    'search'       => [
                        'Modules\CreditDebitNotes\Models\DebitNoteItemTax',
                    ],
                ],
                'debit_note_totals'      => [
                    'sort_key'     => 'debit_note',
                    'table_suffix' => '_totals',
                    'search'       => [
                        'Modules\CreditDebitNotes\Models\DebitNoteTotal',
                    ],
                ],
                'debit_note_histories'   => [
                    'sort_key'     => 'debit_note',
                    'table_suffix' => '_histories',
                    'search'       => [
                        'Modules\CreditDebitNotes\Models\DebitNoteHistory',
                    ],
                ],
            ];

            foreach ($classes as $class) {
                $incrementAmount = $this->getIncrementAmount($class['sort_key'], $class['table_suffix']);

                $builder = DB::table($table)->where("{$column}_type", $class['search'][0]);

                if (isset($class['search'][1])) {
                    $builder->orWhere("{$column}_type", $class['search'][1]);
                }

                if ($incrementAmount !== 0) {
                    $builder->increment("{$column}_id", $incrementAmount);
                }

                if (isset($class['replacement'])) {
                    $builder->update(["{$column}_type" => $class['replacement']]);
                }
            }
        }
    }

    private function getIncrementAmount(string $type, string $suffix): int
    {
        $incrementAmount = 0;

        foreach ($this->totals->sortByDesc('count')->pluck('type')->takeUntil($type) as $table) {
            $incrementAmount += optional(
                DB::table($table . $suffix)->orderByDesc('id')->first('id'),
                function ($document) {
                    return $document->id;
                }
            );
        }
        return $incrementAmount;
    }

    private function updateCreditNoteTransactionsTable(): void
    {
        if (!Schema::hasTable('credits_transactions')) {
            return;
        }

        // Invoice ids did not changed
        if ('invoice' !== $this->totals->sortByDesc('count')->pluck('type')->first()) {
            $incrementAmount = $this->getIncrementAmount('invoice', 's');

            if ($incrementAmount > 0) {
                DB::table('credits_transactions')
                  ->where('type', 'expense')
                  ->whereNotNull('document_id')
                  ->where('document_id', '<>', 0)
                  ->increment('document_id', $incrementAmount);
            }
        }

        // Credit Note ids did not changed
        if ('credit_note' !== $this->totals->sortByDesc('count')->pluck('type')->first()) {
            $incrementAmount = $this->getIncrementAmount('credit_note', 's');

            if ($incrementAmount > 0) {
                DB::table('credits_transactions')
                  ->where('type', 'income')
                  ->whereNotNull('document_id')
                  ->where('document_id', '<>', 0)
                  ->increment('document_id', $incrementAmount);
            }

        }
    }

    private function renameTables(): void
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
            if (Schema::hasTable($table)) {
                Schema::rename($table, "{$table}_v20");
            }
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

    private function getTotals(array $types = []): Collection
    {
        if (DB::table('documents')->count() > 0) {
            $counts = DB::table('documents')
                        ->select('type', DB::raw('COUNT(id) count'))
                        ->groupBy('type')
                        ->orderBy('id')
                        ->get()
                        ->transform(
                            function ($item, $key) {
                                $item->type = Str::replaceFirst('-', '_', $item->type);
                                return $item;
                            }
                        );

            return $counts;
        }

        $counts = [];
        foreach ($types as $type) {
            if (!Schema::hasTable(Str::plural($type))) {
                continue;
            }

            $count = DB::table(Str::plural($type))->count();

            if ($count === 0) {
                continue;
            }

            $values = new \stdClass();
            $values->type = $type;
            $values->count = $count;
            $counts[] = $values;
        }

        return collect($counts);
    }

    private function batchCopyRelations(string $table, string $type): void
    {
        $offset = 0;
        $limit = 500000;
        $new_table = Str::replaceFirst(Str::replaceFirst('-', '_', $type), 'document', $table);

        // To be able to update relation ids
        if (DB::table($new_table)->count() > 0) {
            // Delete document's items which are not found in documents table by document_id
            $this->deleteOrphanedRecords();

            $document = DB::table($new_table)->orderBy('id')->first('type');
            if ($document) {
                $this->addForeignKeysToRelationTables($document->type);
            }

            // Update relation ids
            $document = DB::table($table)->orderByDesc('id')->first('id');
            if ($document) {
                DB::table($new_table)->orderByDesc('id')->increment('id', $document->id);
            }
        }

        $insertColumns = collect(Schema::getColumnListing($new_table));

        $insertColumns = $insertColumns->reject(function ($value) use ($new_table, $table) {
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
    }

    private function deleteOrphanedRecords(): void
    {
        $builder = DB::table('document_items')
                     ->leftJoin(
                         'documents',
                         function ($join) {
                             $join->on('documents.id', '=', 'document_items.document_id')
                                  ->on('documents.type', '=', 'document_items.type');
                         }
                     )
                     ->whereNull('documents.id');

        if ($builder->count()) {
            $builder->delete();
        }

        $builder = DB::table('document_item_taxes')
                     ->leftJoin(
                         'document_items',
                         function ($join) {
                             $join->on('document_items.id', '=', 'document_item_taxes.document_item_id')
                                  ->on('document_items.type', '=', 'document_item_taxes.type');
                         }
                     )
                     ->whereNull('document_items.id');

        if ($builder->count()) {
            $builder->delete();
        }
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

    // To keep original ids
    private function removeAutoIncrements()
    {
        Schema::disableForeignKeyConstraints();

        Schema::table(
            'document_histories',
            function (Blueprint $table) {
                $table->unsignedInteger('id')->change();
            }
        );
        Schema::table(
            'document_totals',
            function (Blueprint $table) {
                $table->unsignedInteger('id')->change();
            }
        );

        Schema::table(
            'document_item_taxes',
            function (Blueprint $table) {
                $table->unsignedInteger('id')->change();
            }
        );

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

        Schema::enableForeignKeyConstraints();
    }

    private function addForeignKeys(): void
    {
        Schema::disableForeignKeyConstraints();

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

    private function addAutoIncrements()
    {
        Schema::disableForeignKeyConstraints();

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

        Schema::table(
            'document_item_taxes',
            function (Blueprint $table) {
                $table->increments('id')->change();
            }
        );

        Schema::table(
            'document_totals',
            function (Blueprint $table) {
                $table->increments('id')->change();
            }
        );

        Schema::table(
            'document_histories',
            function (Blueprint $table) {
                $table->increments('id')->change();
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

        Schema::enableForeignKeyConstraints();
    }

    private function addForeignKeysToRelationTables(string $type): void
    {
        Schema::disableForeignKeyConstraints();

        foreach ($this->tableRelations[$type] as $table) {
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

        foreach ($this->tableRelations[$type] as $table) {
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
        $company_id = company_id();

        $companies = Company::cursor();

        foreach ($companies as $company) {
            $company->makeCurrent();

            $this->updateSettings();
        }

        company($company_id)->makeCurrent();
    }

    public function updateSettings()
    {
        $income_category = Category::income()->enabled()->first();
        $expense_category = Category::expense()->enabled()->first();

        if (empty($income_category) || empty($expense_category)) {
            return;
        }

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

    public function deleteOldFiles()
    {
        $files = [
            'app/Abstracts/DocumentModel.php',
            'app/Events/Purchase/BillCancelled.php',
            'app/Events/Purchase/BillCreated.php',
            'app/Events/Purchase/BillCreating.php',
            'app/Events/Purchase/BillReceived.php',
            'app/Events/Purchase/BillRecurring.php',
            'app/Events/Purchase/BillReminded.php',
            'app/Events/Purchase/BillUpdated.php',
            'app/Events/Purchase/BillUpdating.php',
            'app/Events/Sale/InvoiceCancelled.php',
            'app/Events/Sale/InvoiceCreated.php',
            'app/Events/Sale/InvoiceCreating.php',
            'app/Events/Sale/InvoicePrinting.php',
            'app/Events/Sale/InvoiceRecurring.php',
            'app/Events/Sale/InvoiceReminded.php',
            'app/Events/Sale/InvoiceSent.php',
            'app/Events/Sale/InvoiceUpdated.php',
            'app/Events/Sale/InvoiceUpdating.php',
            'app/Events/Sale/InvoiceViewed.php',
            'app/Events/Sale/PaymentReceived.php',
            'app/Http/Controllers/Api/Purchases/Bills.php',
            'app/Http/Controllers/Api/Sales/InvoiceTransactions.php',
            'app/Http/Controllers/Api/Sales/Invoices.php',
            'app/Http/Controllers/Modals/BillTransactions.php',
            'app/Http/Controllers/Modals/InvoiceTransactions.php',
            'app/Http/Requests/Purchase/Bill.php',
            'app/Http/Requests/Purchase/BillAddItem.php',
            'app/Http/Requests/Purchase/BillHistory.php',
            'app/Http/Requests/Purchase/BillItem.php',
            'app/Http/Requests/Purchase/BillItemTax.php',
            'app/Http/Requests/Purchase/BillTotal.php',
            'app/Http/Requests/Sale/Invoice.php',
            'app/Http/Requests/Sale/InvoiceAddItem.php',
            'app/Http/Requests/Sale/InvoiceHistory.php',
            'app/Http/Requests/Sale/InvoiceItem.php',
            'app/Http/Requests/Sale/InvoiceItemTax.php',
            'app/Http/Requests/Sale/InvoiceTotal.php',
            'app/Jobs/Banking/CreateDocumentTransaction.php',
            'app/Jobs/Purchase/CancelBill.php',
            'app/Jobs/Purchase/CreateBill.php',
            'app/Jobs/Purchase/CreateBillHistory.php',
            'app/Jobs/Purchase/CreateBillItem.php',
            'app/Jobs/Purchase/CreateBillItemsAndTotals.php',
            'app/Jobs/Purchase/DeleteBill.php',
            'app/Jobs/Purchase/DuplicateBill.php',
            'app/Jobs/Purchase/UpdateBill.php',
            'app/Jobs/Sale/CancelInvoice.php',
            'app/Jobs/Sale/CreateInvoice.php',
            'app/Jobs/Sale/CreateInvoiceHistory.php',
            'app/Jobs/Sale/CreateInvoiceItem.php',
            'app/Jobs/Sale/CreateInvoiceItemsAndTotals.php',
            'app/Jobs/Sale/DeleteInvoice.php',
            'app/Jobs/Sale/DuplicateInvoice.php',
            'app/Jobs/Sale/UpdateInvoice.php',
            'app/Listeners/Purchase/CreateBillCreatedHistory.php',
            'app/Listeners/Purchase/IncreaseNextBillNumber.php',
            'app/Listeners/Purchase/MarkBillCancelled.php',
            'app/Listeners/Purchase/MarkBillReceived.php',
            'app/Listeners/Purchase/SendBillRecurringNotification.php',
            'app/Listeners/Purchase/SendBillReminderNotification.php',
            'app/Listeners/Sale/CreateInvoiceCreatedHistory.php',
            'app/Listeners/Sale/CreateInvoiceTransaction.php',
            'app/Listeners/Sale/IncreaseNextInvoiceNumber.php',
            'app/Listeners/Sale/MarkInvoiceCancelled.php',
            'app/Listeners/Sale/MarkInvoiceSent.php',
            'app/Listeners/Sale/MarkInvoiceViewed.php',
            'app/Listeners/Sale/SendInvoicePaymentNotification.php',
            'app/Listeners/Sale/SendInvoiceRecurringNotification.php',
            'app/Listeners/Sale/SendInvoiceReminderNotification.php',
            'app/Models/Purchase/Bill.php',
            'app/Models/Purchase/BillHistory.php',
            'app/Models/Purchase/BillItem.php',
            'app/Models/Purchase/BillItemTax.php',
            'app/Models/Purchase/BillTotal.php',
            'app/Models/Sale/Invoice.php',
            'app/Models/Sale/InvoiceHistory.php',
            'app/Models/Sale/InvoiceItem.php',
            'app/Models/Sale/InvoiceItemTax.php',
            'app/Models/Sale/InvoiceTotal.php',
            'app/Traits/Purchases.php',
            'app/Traits/Sales.php',
            'app/Transformers/Purchase/Bill.php',
            'app/Transformers/Purchase/BillHistories.php',
            'app/Transformers/Purchase/BillItems.php',
            'app/Transformers/Purchase/BillTotals.php',
            'app/Transformers/Sale/Invoice.php',
            'app/Transformers/Sale/InvoiceHistories.php',
            'app/Transformers/Sale/InvoiceItems.php',
            'app/Transformers/Sale/InvoiceTotals.php',
            'app/Utilities/Updater.php',
            'config/maintenancemode.php',
            'database/factories/Bill.php',
            'database/factories/Invoice.php',
            'public/0.js',
            'public/38.js',
            'public/js/purchases/bills.js',
            'public/js/sales/invoices.js',
            'resources/views/modals/bills/payment.blade.php',
            'resources/views/modals/invoices/payment.blade.php',
            'resources/views/partials/documents/item/print.blade.php',
            'resources/views/partials/documents/item/show.blade.php',
            'resources/views/purchases/bills/item.blade.php',
            'resources/views/sales/invoices/item.blade.php',
        ];

        $directories = [
            'app/Events/Purchase',
            'app/Events/Sale',
            'app/Http/Controllers/Api/Purchases',
            'app/Http/Controllers/Api/Sales',
            'app/Http/Requests/Purchase',
            'app/Http/Requests/Sale',
            'app/Jobs/Purchase',
            'app/Jobs/Sale',
            'app/Listeners/Purchase',
            'app/Listeners/Sale',
            'app/Models/Purchase',
            'app/Models/Sale',
            'app/Transformers/Purchase',
            'app/Transformers/Sale',
            'resources/views/modals/bills',
            'resources/views/modals/invoices',
            'resources/views/partials/documents/item',
            'resources/views/partials/documents',
        ];

        foreach ($files as $file) {
            File::delete(base_path($file));
        }

        foreach ($directories as $directory) {
            File::deleteDirectory(base_path($directory));
        }
    }
}
