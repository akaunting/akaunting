<?php

use Illuminate\Database\Migrations\Migration;

class AddTaxColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('taxes', function ($table) {
            $table->boolean('calculate')->default(0);
            $table->boolean('compound')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('taxes', function ($table) {
            $table->dropColumn([
                'calculate',
                'compound',
            ]);
        });
    }
}
