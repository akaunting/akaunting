<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediableTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('media')) {
            Schema::create(
                'media',
                function (Blueprint $table) {
                    $table->increments('id');
                    $table->string('disk', 32);
                    $table->string('directory');
                    $table->string('filename');
                    $table->string('extension', 32);
                    $table->string('mime_type', 128);
                    $table->string('aggregate_type', 32);
                    $table->integer('size')->unsigned();
                    $table->timestamps();

                    $table->unique(['disk', 'directory', 'filename', 'extension']);
                    $table->index('aggregate_type');
                }
            );
        }

        if (!Schema::hasTable('mediables')) {
            Schema::create(
                'mediables',
                function (Blueprint $table) {
                    $table->integer('media_id')->unsigned();
                    $table->string('mediable_type');
                    $table->integer('mediable_id')->unsigned();
                    $table->string('tag');
                    $table->integer('order')->unsigned();

                    $table->primary(['media_id', 'mediable_type', 'mediable_id', 'tag']);
                    $table->index(['mediable_id', 'mediable_type']);
                    $table->index('tag');
                    $table->index('order');
                    $table->foreign('media_id')
                        ->references('id')->on('media')
                        ->cascadeOnDelete();
                }
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mediables');
        Schema::dropIfExists('media');
    }

    /**
     * {@inheritdoc}
     */
    public function getConnection()
    {
        return config('mediable.connection_name', parent::getConnection());
    }
}
