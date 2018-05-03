<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('bill_number');
            $table->string('order_number')->nullable();
            $table->string('bill_status_code');
            $table->date('billed_at');
            $table->date('due_at');
            $table->double('amount', 15, 4);
            $table->string('currency_code');
            $table->double('currency_rate', 15, 8);
            $table->integer('vendor_id');
            $table->string('vendor_name');
            $table->string('vendor_email');
            $table->string('vendor_tax_number')->nullable();
            $table->string('vendor_phone')->nullable();
            $table->text('vendor_address')->nullable();
            $table->text('notes')->nullable();
            $table->string('attachment')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->unique(['company_id', 'bill_number', 'deleted_at']);
        });

        Schema::create('bill_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('bill_id');
            $table->integer('item_id')->nullable();
            $table->string('name');
            $table->string('sku')->nullable();
            $table->double('quantity', 7, 2);
            $table->double('price', 15, 4);
            $table->double('total', 15, 4);
            $table->float('tax', 15, 4)->default('0.0000');
            $table->integer('tax_id');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::create('bill_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('name');
            $table->string('code');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::create('bill_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('bill_id');
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

        Schema::create('bill_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('bill_id');
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
        Schema::drop('bills');
        Schema::drop('bill_items');
        Schema::drop('bill_statuses');
        Schema::drop('bill_payments');
        Schema::drop('bill_histories');
    }
}
