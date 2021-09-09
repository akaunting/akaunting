<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CoreV2125 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->string('created_from', 100)->change();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->string('created_from', 100)->change();
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->string('created_from', 100)->change();
        });

        Schema::table('contacts', function (Blueprint $table) {
            $table->string('created_from', 100)->change();
        });

        Schema::table('currencies', function (Blueprint $table) {
            $table->string('created_from', 100)->change();
        });

        Schema::table('dashboards', function (Blueprint $table) {
            $table->string('created_from', 100)->change();
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->string('created_from', 100)->change();
        });

        Schema::table('document_histories', function (Blueprint $table) {
            $table->string('created_from', 100)->change();
        });

        Schema::table('document_items', function (Blueprint $table) {
            $table->string('created_from', 100)->change();
        });

        Schema::table('document_item_taxes', function (Blueprint $table) {
            $table->string('created_from', 100)->change();
        });

        Schema::table('document_totals', function (Blueprint $table) {
            $table->string('created_from', 100)->change();
        });

        Schema::table('email_templates', function (Blueprint $table) {
            $table->string('created_from', 100)->change();
        });

        Schema::table('items', function (Blueprint $table) {
            $table->string('created_from', 100)->change();
        });

        Schema::table('item_taxes', function (Blueprint $table) {
            $table->string('created_from', 100)->change();
        });

        Schema::table('media', function (Blueprint $table) {
            $table->string('created_from', 100)->change();
        });

        Schema::table('mediables', function (Blueprint $table) {
            $table->string('created_from', 100)->change();
        });

        Schema::table('modules', function (Blueprint $table) {
            $table->string('created_from', 100)->change();
        });

        Schema::table('module_histories', function (Blueprint $table) {
            $table->string('created_from', 100)->change();
        });

        Schema::table('reconciliations', function (Blueprint $table) {
            $table->string('created_from', 100)->change();
        });

        Schema::table('recurring', function (Blueprint $table) {
            $table->string('created_from', 100)->change();
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->string('created_from', 100)->change();
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->string('created_from', 100)->change();
        });

        Schema::table('taxes', function (Blueprint $table) {
            $table->string('created_from', 100)->change();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->string('created_from', 100)->change();
        });

        Schema::table('transfers', function (Blueprint $table) {
            $table->string('created_from', 100)->change();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('created_from', 100)->change();
        });

        Schema::table('widgets', function (Blueprint $table) {
            $table->string('created_from', 100)->change();
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
