<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('type');
            $table->integer('account_id');
            $table->dateTime('paid_at');
            $table->double('amount', 15, 4);
            $table->string('currency_code', 3);
            $table->double('currency_rate', 15, 8);
            $table->integer('document_id')->nullable();
            $table->integer('contact_id')->nullable();
            $table->text('description')->nullable();
            $table->integer('category_id');
            $table->string('payment_method');
            $table->string('reference')->nullable();
            $table->integer('parent_id')->default(0);
            $table->boolean('reconciled')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'type']);
        });

        Schema::table('transfers', function (Blueprint $table) {
            $table->renameColumn('payment_id', 'expense_transaction_id');
        });

        Schema::table('transfers', function (Blueprint $table) {
            $table->renameColumn('revenue_id', 'income_transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('transactions');
    }
}
