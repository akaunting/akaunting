<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('user_companies', function (Blueprint $table) {
            $table->dropPrimary(['user_id', 'company_id', 'user_type']);
            $table->primary(['user_id', 'company_id']);
        });

        Schema::table('user_companies', function (Blueprint $table) {
            $table->dropColumn('user_type');
        });

        Schema::table('user_dashboards', function (Blueprint $table) {
            $table->dropPrimary(['user_id', 'company_id', 'user_type']);
            $table->primary(['user_id', 'company_id']);
        });

        Schema::table('user_dashboards', function (Blueprint $table) {
            $table->dropColumn('user_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
