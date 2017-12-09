<?php

use Illuminate\Database\Migrations\Migration;

class AddCurrencyColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('currencies', function ($table) {
            $table->string('precision')->nullable();
            $table->string('symbol')->nullable();
            $table->integer('symbol_first')->default(1);
            $table->string('decimal_mark')->nullable();
            $table->string('thousands_separator')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('currencies', function ($table) {
            $table->dropColumn('precision');
            $table->dropColumn('symbol');
            $table->dropColumn('symbol_first');
            $table->dropColumn('decimal_mark');
            $table->dropColumn('thousands_separator');
        });
    }
}
