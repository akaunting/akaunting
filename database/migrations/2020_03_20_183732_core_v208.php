<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CoreV208 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->double('discount_rate', 15, 4)->default('0.0000')->after('tax');
            $table->string('discount_type')->default('normal')->after('discount_rate');
        });

        Schema::table('bill_items', function (Blueprint $table) {
            $table->double('discount_rate', 15, 4)->default('0.0000')->after('tax');
            $table->string('discount_type')->default('normal')->after('discount_rate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropColumn(['discount_rate', 'discount_type']);
        });

        Schema::table('bill_items', function (Blueprint $table) {
            $table->dropColumn(['discount_rate', 'discount_type']);
        });
    }
}
