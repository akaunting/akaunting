<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CoreV2127 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->string('contact_country')->nullable()->after('contact_address');
            $table->string('contact_state')->nullable()->after('contact_address');
            $table->string('contact_zip_code')->nullable()->after('contact_address');
            $table->string('contact_city')->nullable()->after('contact_address');
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
}
