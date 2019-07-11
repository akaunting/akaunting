<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ModifyDeletedAtColumnMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropUnique(['disk', 'directory', 'filename', 'extension']);

            $table->unique(['disk', 'directory', 'filename', 'extension', 'deleted_at']);
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
            $table->dropUnique(['disk', 'directory', 'filename', 'extension', 'deleted_at']);

            $table->unique(['disk', 'directory', 'filename', 'extension']);
        });
    }
}
