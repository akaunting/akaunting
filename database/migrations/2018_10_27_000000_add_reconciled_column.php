<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReconciledColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bill_payments', function (Blueprint $table) {
            $table->boolean('reconciled')->default(0);
        });

        Schema::table('invoice_payments', function (Blueprint $table) {
            $table->boolean('reconciled')->default(0);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->boolean('reconciled')->default(0);
        });

        Schema::table('revenues', function (Blueprint $table) {
            $table->boolean('reconciled')->default(0);
        });
    }

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('bill_payments', function (Blueprint $table) {
			$table->dropColumn('reconciled');
		});

		Schema::table('invoice_payments', function (Blueprint $table) {
			$table->dropColumn('reconciled');
		});

		Schema::table('payments', function (Blueprint $table) {
			$table->dropColumn('reconciled');
		});

        Schema::table('revenues', function (Blueprint $table) {
            $table->dropColumn('reconciled');
        });
	}
}
