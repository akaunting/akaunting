<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ModifyDateColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->dateTime('billed_at')->change();
            $table->dateTime('due_at')->change();
        });

        Schema::table('bill_payments', function (Blueprint $table) {
            $table->dateTime('paid_at')->change();
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dateTime('invoiced_at')->change();
            $table->dateTime('due_at')->change();
        });

        Schema::table('invoice_payments', function (Blueprint $table) {
            $table->dateTime('paid_at')->change();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dateTime('paid_at')->change();
        });

        Schema::table('revenues', function (Blueprint $table) {
            $table->dateTime('paid_at')->change();
        });

        Schema::table('recurring', function (Blueprint $table) {
            $table->dateTime('started_at')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
