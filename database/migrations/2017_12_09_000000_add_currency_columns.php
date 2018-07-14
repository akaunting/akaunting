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
        });
		Schema::table('currencies', function ($table) {
			$table->dropColumn('symbol');
		});
		Schema::table('currencies', function ($table) {
			$table->dropColumn('symbol_first');
		});
		Schema::table('currencies', function ($table) {
			$table->dropColumn('decimal_mark');
		});
		Schema::table('currencies', function ($table) {
			$table->dropColumn('thousands_separator');
		});
    }
}
