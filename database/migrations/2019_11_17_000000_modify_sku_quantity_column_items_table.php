<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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

            $connection = Schema::getConnection();
            $d_table = $connection->getDoctrineSchemaManager()->listTableDetails($connection->getTablePrefix() . 'items');

            if ($d_table->hasIndex('items_company_id_sku_deleted_at_unique')) {
                // 1.3 update
                $table->dropUnique('items_company_id_sku_deleted_at_unique');
            } else {
                // 2.0 install
                $table->dropUnique(['company_id', 'sku', 'deleted_at']);
            }
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
