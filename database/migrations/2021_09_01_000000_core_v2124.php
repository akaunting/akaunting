<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CoreV2124 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->string('country')->nullable()->after('address');
            $table->string('state')->nullable()->after('address');
            $table->string('zip_code')->nullable()->after('address');
            $table->string('city')->nullable()->after('address');
        });

        Schema::table('accounts', function (Blueprint $table) {
            $table->string('created_from', 30)->nullable()->after('enabled');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->string('created_from', 30)->nullable()->after('enabled');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->string('created_from', 30)->nullable()->after('enabled');
        });

        Schema::table('contacts', function (Blueprint $table) {
            $table->string('created_from', 30)->nullable()->after('reference');
        });

        Schema::table('currencies', function (Blueprint $table) {
            $table->string('created_from', 30)->nullable()->after('enabled');
        });

        Schema::table('dashboards', function (Blueprint $table) {
            $table->string('created_from', 30)->nullable()->after('enabled');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->string('created_from', 30)->nullable()->after('parent_id');
        });

        Schema::table('document_histories', function (Blueprint $table) {
            $table->string('created_by', 30)->nullable()->after('description');
            $table->string('created_from', 30)->nullable()->after('description');
        });

        Schema::table('document_items', function (Blueprint $table) {
            $table->string('created_by', 30)->nullable()->after('total');
            $table->string('created_from', 30)->nullable()->after('total');
        });

        Schema::table('document_item_taxes', function (Blueprint $table) {
            $table->string('created_by', 30)->nullable()->after('amount');
            $table->string('created_from', 30)->nullable()->after('amount');
        });

        Schema::table('document_totals', function (Blueprint $table) {
            $table->string('created_by', 30)->nullable()->after('sort_order');
            $table->string('created_from', 30)->nullable()->after('sort_order');
        });

        Schema::table('email_templates', function (Blueprint $table) {
            $table->string('created_by', 30)->nullable()->after('params');
            $table->string('created_from', 30)->nullable()->after('params');
        });

        Schema::table('items', function (Blueprint $table) {
            $table->string('created_from', 30)->nullable()->after('enabled');
        });

        Schema::table('item_taxes', function (Blueprint $table) {
            $table->string('created_by', 30)->nullable()->after('tax_id');
            $table->string('created_from', 30)->nullable()->after('tax_id');
        });

        Schema::table('media', function (Blueprint $table) {
            $table->string('created_by', 30)->nullable()->after('original_media_id');
            $table->string('created_from', 30)->nullable()->after('original_media_id');
        });

        Schema::table('mediables', function (Blueprint $table) {
            $table->string('created_by', 30)->nullable()->after('order');
            $table->string('created_from', 30)->nullable()->after('order');
        });

        Schema::table('modules', function (Blueprint $table) {
            $table->string('created_by', 30)->nullable()->after('enabled');
            $table->string('created_from', 30)->nullable()->after('enabled');
        });

        Schema::table('module_histories', function (Blueprint $table) {
            $table->string('created_by', 30)->nullable()->after('description');
            $table->string('created_from', 30)->nullable()->after('description');
        });

        Schema::table('reconciliations', function (Blueprint $table) {
            $table->string('created_from', 30)->nullable()->after('reconciled');
        });

        Schema::table('recurring', function (Blueprint $table) {
            $table->string('created_by', 30)->nullable()->after('count');
            $table->string('created_from', 30)->nullable()->after('count');
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->string('created_from', 30)->nullable()->after('settings');
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->string('created_by', 30)->nullable()->after('description');
            $table->string('created_from', 30)->nullable()->after('description');
        });

        Schema::table('taxes', function (Blueprint $table) {
            $table->string('created_from', 30)->nullable()->after('enabled');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->string('created_from', 30)->nullable()->after('parent_id');
        });

        Schema::table('transfers', function (Blueprint $table) {
            $table->string('created_from', 30)->nullable()->after('income_transaction_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('created_by', 30)->nullable()->after('enabled');
            $table->string('created_from', 30)->nullable()->after('enabled');
        });

        Schema::table('widgets', function (Blueprint $table) {
            $table->string('created_from', 30)->nullable()->after('settings');
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
