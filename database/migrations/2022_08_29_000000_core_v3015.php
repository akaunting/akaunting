<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Documents
        Schema::table('documents', function(Blueprint $table) {
            $table->index('contact_id');
        });

        // User Companies
        Schema::table('user_companies', function(Blueprint $table) {
            $table->index('user_id');
            $table->index('company_id');
        });

        // User Roles
        Schema::table('user_roles', function(Blueprint $table) {
            $table->index('user_id');
            $table->index('role_id');
        });

        // Transactions
        Schema::table('transactions', function(Blueprint $table) {
            $table->index('number');
        });

        // Roles
        Schema::table('roles', function(Blueprint $table) {
            $table->index('name');
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
};
