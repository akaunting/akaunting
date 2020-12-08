<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CoreV210 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('failed_jobs', function (Blueprint $table) {
            $table->string('uuid')->after('id')->nullable()->unique();
        });

        Schema::create('item_taxes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('item_id');
            $table->integer('tax_id');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'item_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('item_taxes');
        //
    }
}
