<?php

use Illuminate\Database\Migrations\Migration;

class AddReferenceColumnVendors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendors', function ($table) {
            $table->string('reference')->nullable();
        });
    }

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('vendors', function ($table) {
			$table->dropColumn('reference');
		});
	}
}
