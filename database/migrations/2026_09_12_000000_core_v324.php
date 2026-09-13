<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('document_item_taxes', function (Blueprint $table) {
            $table->double('rate', 15, 4)->default('0.0000')->after('name');

            // The backfill below and the rate lookup in DocumentTotal both filter
            // on tax_id, which had no index of its own.
            $table->index('tax_id');
        });

        // Existing rows never stored the rate they were charged at, so seed them
        // with the tax's current rate. That is the closest approximation
        // available, and the same value the previous code would have
        // recalculated on the next save anyway.
        DB::table('taxes')->select('id', 'rate')->orderBy('id')->chunk(200, function ($taxes) {
            foreach ($taxes as $tax) {
                DB::table('document_item_taxes')
                    ->where('tax_id', $tax->id)
                    ->update(['rate' => $tax->rate]);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('document_item_taxes', function (Blueprint $table) {
            $table->dropIndex(['tax_id']);
            $table->dropColumn('rate');
        });
    }
};
