<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBillItemTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_item_taxes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('bill_id');
            $table->integer('bill_item_id');
            $table->integer('tax_id');
            $table->string('name');
            $table->double('amount', 15, 4)->default('0.0000');
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
        Schema::drop('bill_item_taxes');
    }
}
