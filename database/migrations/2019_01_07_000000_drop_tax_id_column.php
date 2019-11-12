<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropTaxIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bill_items', function (Blueprint $table) {
            $table->dropColumn('tax_id');
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropColumn('tax_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bill_items', function (Blueprint $table) {
            $table->integer('tax_id')->default(0);
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->integer('tax_id')->default(0);
        });
    }
}
