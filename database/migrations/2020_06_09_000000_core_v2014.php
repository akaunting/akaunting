<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CoreV2014 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $doc_types = ['invoice', 'bill'];
        $doc_tables = ['histories', 'items', 'item_taxes', 'totals'];

        foreach ($doc_types as $doc_type) {
            foreach ($doc_tables as $doc_table) {
                Schema::table($doc_type . '_' . $doc_table, function (Blueprint $table) use ($doc_type) {
                    $table->index($doc_type . '_id');
                });
            }
        }

        Schema::table('transactions', function (Blueprint $table) {
            $table->index('account_id');
            $table->index('category_id');
            $table->index('contact_id');
            $table->index('document_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
