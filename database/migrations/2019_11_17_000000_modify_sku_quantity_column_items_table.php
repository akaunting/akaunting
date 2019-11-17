<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ModifySkuQuantityColumnItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('sku')->nullable()->change();
            $table->integer('quantity')->default(1)->change();
            $table->dropUnique(['company_id', 'sku', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('sku')->change();
            $table->integer('quantity')->change();
            $table->unique(['company_id', 'sku', 'deleted_at']);
        });
    }
}
