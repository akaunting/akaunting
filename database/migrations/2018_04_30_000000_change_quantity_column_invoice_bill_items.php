<?php

use Illuminate\Database\Migrations\Migration;

class ChangeQuantityColumnInvoiceBillItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_items', function ($table) {
            $table->double('quantity', 7, 2)->change();
        });

        Schema::table('bill_items', function ($table) {
            $table->double('quantity', 7, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_items', function ($table) {
            $table->integer('quantity')->change();
        });

        Schema::table('bill_items', function ($table) {
            $table->integer('quantity')->change();
        });
    }
}
