<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryColumnInvoicesBills extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->integer('category_id')->default();
        });

        Schema::table('bills', function (Blueprint $table) {
            $table->integer('category_id')->default();
        });
    }

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('invoices', function (Blueprint $table) {
			$table->dropColumn('category_id');
		});

		Schema::table('bills', function (Blueprint $table) {
			$table->dropColumn('category_id');
		});
	}
}
