<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParentColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->integer('parent_id')->default(0);
        });

        Schema::table('revenues', function (Blueprint $table) {
            $table->integer('parent_id')->default(0);
        });

        Schema::table('bills', function (Blueprint $table) {
            $table->integer('parent_id')->default(0);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->integer('parent_id')->default(0);
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
            $table->dropColumn('parent_id');
        });

        Schema::table('revenues', function (Blueprint $table) {
            $table->dropColumn('parent_id');
        });

        Schema::table('bills', function (Blueprint $table) {
            $table->dropColumn('parent_id');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('parent_id');
        });
    }
}
