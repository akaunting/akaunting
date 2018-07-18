<?php

use Illuminate\Database\Migrations\Migration;

class AddCategoryColumnInvoicesBills extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $driver = Schema::connection($this->getConnection())->getConnection()->getDriverName();

        Schema::table('invoices', function ($table) use($driver) {
            if($driver === 'sqlite'){
                $table->integer('category_id')->default();
            }else{
                $table->integer('category_id');
            }
        });

        Schema::table('bills', function ($table) use($driver) {
            if($driver === 'sqlite'){
                $table->integer('category_id')->default();
            }else{
                $table->integer('category_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function ($table) {
            $table->dropColumn('category_id');
        });

        Schema::table('bills', function ($table) {
            $table->dropColumn('category_id');
        });
    }
}
