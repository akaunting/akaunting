<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CoreV2114 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->unsignedInteger('company_id')->default(0)->after('id');

            $table->index('company_id');
        });

        Schema::table('mediables', function (Blueprint $table) {
            $table->unsignedInteger('company_id')->default(0)->after('media_id');

            $table->index('company_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn('company_id');
        });

        Schema::table('mediables', function (Blueprint $table) {
            $table->dropColumn('company_id');
        });
    }
}
