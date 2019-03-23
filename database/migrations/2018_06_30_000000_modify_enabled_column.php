<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ModifyEnabledColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        if ( DB::connection()->getPDO()->getAttribute(PDO::ATTR_DRIVER_NAME) == 'pgsql' ){ 
            $currencyTableName = DB::connection()->getTablePrefix().(with(new App\Models\Setting\Currency)->getTable());
            DB::statement("ALTER TABLE $currencyTableName ALTER COLUMN enabled DROP DEFAULT");
            DB::statement("ALTER TABLE $currencyTableName ALTER enabled TYPE bool USING CASE WHEN enabled=1 THEN TRUE ELSE FALSE END");
            DB::statement("ALTER TABLE $currencyTableName ALTER COLUMN enabled SET DEFAULT FALSE");
            return;
        }
        Schema::table('accounts', function (Blueprint $table) {
            $table->boolean('enabled')->default(1)->change();
        });
        
        Schema::table('categories', function (Blueprint $table) {
            $table->boolean('enabled')->default(1)->change();
        });
        
        Schema::table('currencies', function (Blueprint $table) {
            $table->boolean('enabled')->default(1)->change();
        });
        
        Schema::table('items', function (Blueprint $table) {
            $table->boolean('enabled')->default(1)->change();
        });
        
        Schema::table('customers', function (Blueprint $table) {
            $table->boolean('enabled')->default(1)->change();
        });
        
        Schema::table('vendors', function (Blueprint $table) {
            $table->boolean('enabled')->default(1)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
