<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Create table for mock mediable model
 */
class CreateMediableTestTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sample_mediables', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('media', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::create('prefixed_mediables', function (Blueprint $table) {
            $table->integer('media_id')->unsigned();
            $table->string('mediable_type');
            $table->integer('mediable_id')->unsigned();
            $table->string('tag');
            $table->integer('order')->unsigned();

            $table->primary(['media_id', 'mediable_type', 'mediable_id', 'tag']);
            $table->index(['mediable_id', 'mediable_type']);
            $table->index('tag');
            $table->index('order');
            $table->foreign('media_id')->references('id')->on('media')
                ->onDelete('cascade');
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
            $table->dropColumn('deleted_at');
        });
        Schema::drop('sample_mediables');
        Schema::drop('prefixed_mediables');
    }
}
