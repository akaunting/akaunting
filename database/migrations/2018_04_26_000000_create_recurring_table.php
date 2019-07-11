<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRecurringTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recurring', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->morphs('recurable');
            $table->string('frequency');
            $table->integer('interval')->default(1);
            $table->date('started_at');
            $table->integer('count')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('recurring');
    }
}
