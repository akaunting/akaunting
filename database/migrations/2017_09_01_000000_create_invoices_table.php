<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('invoice_number');
            $table->string('order_number')->nullable();
            $table->string('invoice_status_code');
            $table->date('invoiced_at');
            $table->date('due_at');
            $table->double('amount', 15, 4);
            $table->string('currency_code');
            $table->double('currency_rate', 15, 8);
            $table->integer('customer_id');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_tax_number')->nullable();
            $table->string('customer_phone')->nullable();
            $table->text('customer_address')->nullable();
            $table->text('notes')->nullable();
            $table->string('attachment')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->unique(['company_id', 'invoice_number', 'deleted_at']);
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('invoice_id');
            $table->integer('item_id')->nullable();
            $table->string('name');
            $table->string('sku')->nullable();
            $table->double('quantity', 7, 2);
            $table->double('price', 15, 4);
            $table->double('total', 15, 4);
            $table->double('tax', 15, 4)->default('0.0000');
            $table->integer('tax_id');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::create('invoice_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('name');
            $table->string('code');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::create('invoice_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('invoice_id');
            $table->integer('account_id');
            $table->date('paid_at');
            $table->double('amount', 15, 4);
            $table->string('currency_code');
            $table->double('currency_rate', 15, 8);
            $table->text('description')->nullable();
            $table->string('payment_method');
            $table->string('reference')->nullable();
            $table->string('attachment')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::create('invoice_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('invoice_id');
            $table->string('status_code');
            $table->boolean('notify');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('invoices');
        Schema::drop('invoice_items');
        Schema::drop('invoice_statuses');
        Schema::drop('invoice_payments');
        Schema::drop('invoice_histories');
    }
}
