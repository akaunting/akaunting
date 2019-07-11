<?php

use Illuminate\Database\Migrations\Migration;

class AddReferenceColumnCustomers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function ($table) {
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
		Schema::table('customers', function ($table) {
			$table->dropColumn('reference');
		});
	}
}
