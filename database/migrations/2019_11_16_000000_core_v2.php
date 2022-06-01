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
        // Accounts
        Schema::table('accounts', function (Blueprint $table) {
            $table->unsignedInteger('created_by')->nullable()->after('enabled');
            $table->string('created_from', 100)->nullable()->after('enabled');
        });

        // Categories
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedInteger('created_by')->nullable()->after('enabled');
            $table->string('created_from', 100)->nullable()->after('enabled');
        });

        // Companies
        Schema::table('companies', function (Blueprint $table) {
            $table->string('domain')->nullable()->change();
            $table->unsignedInteger('created_by')->nullable()->after('enabled');
            $table->string('created_from', 100)->nullable()->after('enabled');
        });

        // Contacts
        Schema::create('contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('type');
            $table->string('name');
            $table->string('email')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('website')->nullable();
            $table->string('currency_code');
            $table->boolean('enabled')->default(1);
            $table->string('reference')->nullable();
            $table->string('created_from', 100)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'type']);
            $table->unique(['company_id', 'type', 'email', 'deleted_at']);
        });

        // Currencies
        Schema::table('currencies', function (Blueprint $table) {
            $table->unsignedInteger('created_by')->nullable()->after('enabled');
            $table->string('created_from', 100)->nullable()->after('enabled');
        });

        // Dashboards & Widgets
        Schema::create('dashboards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('name');
            $table->boolean('enabled')->default(1);
            $table->string('created_from', 100)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id']);
        });

        Schema::create('user_dashboards', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('dashboard_id')->unsigned();

            $table->primary(['user_id', 'dashboard_id']);
        });

        Schema::create('widgets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('dashboard_id');
            $table->string('class');
            $table->string('name');
            $table->integer('sort')->default(0);
            $table->text('settings')->nullable();
            $table->string('created_from', 100)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'dashboard_id']);
            $table->index('class');
        });

        // Documents
        Schema::create('documents', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->string('type');
            $table->string('document_number');
            $table->string('order_number')->nullable();
            $table->string('status');
            $table->dateTime('issued_at');
            $table->dateTime('due_at');
            $table->double('amount', 15, 4);
            $table->string('currency_code');
            $table->double('currency_rate', 15, 8);
            $table->unsignedInteger('category_id')->default(1);
            $table->unsignedInteger('contact_id');
            $table->string('contact_name');
            $table->string('contact_email')->nullable();
            $table->string('contact_tax_number')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('contact_address')->nullable();
            $table->string('contact_city')->nullable();
            $table->string('contact_zip_code')->nullable();
            $table->string('contact_state')->nullable();
            $table->string('contact_country')->nullable();
            $table->text('notes')->nullable();
            $table->text('footer')->nullable();
            $table->unsignedInteger('parent_id')->default(0);
            $table->string('created_from', 100)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('type');
            $table->unique(['document_number', 'deleted_at', 'company_id', 'type']);
        });

        Schema::create('document_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('type');
            $table->unsignedInteger('document_id');
            $table->string('status');
            $table->boolean('notify');
            $table->text('description')->nullable();
            $table->string('created_from', 100)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('type');
            $table->index('document_id');
        });

        Schema::create('document_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->string('type');
            $table->unsignedInteger('document_id');
            $table->unsignedInteger('item_id')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('sku')->nullable();
            $table->double('quantity', 7, 2);
            $table->double('price', 15, 4);
            $table->float('tax', 15, 4)->default('0.0000');
            $table->string('discount_type')->default('normal');
            $table->double('discount_rate', 15, 4)->default('0.0000');
            $table->double('total', 15, 4);
            $table->string('created_from', 100)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('type');
            $table->index('document_id');
        });

        Schema::create('document_item_taxes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->string('type');
            $table->unsignedInteger('document_id');
            $table->unsignedInteger('document_item_id');
            $table->unsignedInteger('tax_id');
            $table->string('name');
            $table->double('amount', 15, 4)->default('0.0000');
            $table->string('created_from', 100)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('type');
            $table->index('document_id');
        });

        Schema::create('document_totals', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->string('type');
            $table->unsignedInteger('document_id');
            $table->string('code')->nullable();
            $table->string('name');
            $table->double('amount', 15, 4);
            $table->integer('sort_order');
            $table->string('created_from', 100)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('type');
            $table->index('document_id');
        });

        // Email templates
        Schema::create('email_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('alias');
            $table->string('class');
            $table->string('name');
            $table->string('subject');
            $table->text('body');
            $table->text('params')->nullable();
            $table->string('created_from', 100)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->unique(['company_id', 'alias', 'deleted_at']);
        });

        // Jobs
        Schema::table('failed_jobs', function (Blueprint $table) {
            $table->string('uuid')->after('id')->nullable()->unique();
        });

        // Firewall
        Schema::create('firewall_ips', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ip');
            $table->integer('log_id')->nullable();
            $table->boolean('blocked')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->index('ip');
            $table->unique(['ip', 'deleted_at']);
        });

        Schema::create('firewall_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ip');
            $table->string('level')->default('medium');
            $table->string('middleware');
            $table->integer('user_id')->nullable();
            $table->string('url')->nullable();
            $table->string('referrer')->nullable();
            $table->text('request')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('ip');
        });

        // Items
        Schema::table('items', function (Blueprint $table) {
            $table->string('sku')->nullable()->change();
            $table->integer('quantity')->default(1)->change();
            $table->unsignedInteger('created_by')->nullable()->after('enabled');
            $table->string('created_from', 100)->nullable()->after('enabled');

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

        Schema::create('item_taxes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('item_id');
            $table->integer('tax_id')->nullable();
            $table->string('created_from', 100)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'item_id']);
        });

        // Media
        Schema::table('media', function (Blueprint $table) {
            $table->unsignedInteger('company_id')->default(0)->after('id');
            $table->unsignedInteger('created_by')->nullable()->after('size');
            $table->string('created_from', 100)->nullable()->after('size');

            $table->index('company_id');
        });

        Schema::table('mediables', function (Blueprint $table) {
            $table->unsignedInteger('company_id')->default(0)->after('media_id');
            $table->unsignedInteger('created_by')->nullable()->after('order');
            $table->string('created_from', 100)->nullable()->after('order');

            $table->index('company_id');
        });

        // Modules
        Schema::table('modules', function (Blueprint $table) {
            $table->renameColumn('status', 'enabled');
        });

        Schema::table('modules', function (Blueprint $table) {
            $table->unsignedInteger('created_by')->nullable()->after('enabled');
            $table->string('created_from', 100)->nullable()->after('enabled');
        });

        Schema::table('module_histories', function (Blueprint $table) {
            $table->dropColumn('category');
        });

        Schema::table('module_histories', function (Blueprint $table) {
            $table->unsignedInteger('created_by')->nullable()->after('description');
            $table->string('created_from', 100)->nullable()->after('description');
        });

        // Reconciliations
        Schema::table('reconciliations', function (Blueprint $table) {
            $table->unsignedInteger('created_by')->nullable()->after('reconciled');
            $table->string('created_from', 100)->nullable()->after('reconciled');
        });

        // Recurring
        Schema::table('recurring', function (Blueprint $table) {
            $table->unsignedInteger('created_by')->nullable()->after('count');
            $table->string('created_from', 100)->nullable()->after('count');
        });

        // Reports
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('class');
            $table->string('name');
            $table->text('description');
            $table->text('settings')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->string('created_from', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('class');
        });

        // Roles
        Schema::table('roles', function (Blueprint $table) {
            $table->unsignedInteger('created_by')->nullable()->after('description');
            $table->string('created_from', 100)->nullable()->after('description');
        });

        // Taxes
        Schema::table('taxes', function (Blueprint $table) {
            $table->unsignedInteger('created_by')->nullable()->after('enabled');
            $table->string('created_from', 100)->nullable()->after('enabled');
        });

        // Transactions
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('type');
            $table->dateTime('paid_at');
            $table->double('amount', 15, 4);
            $table->string('currency_code');
            $table->double('currency_rate', 15, 8);
            $table->integer('account_id');
            $table->integer('document_id')->nullable();
            $table->integer('contact_id')->nullable();
            $table->integer('category_id')->default(1);
            $table->text('description')->nullable();
            $table->string('payment_method');
            $table->string('reference')->nullable();
            $table->integer('parent_id')->default(0);
            $table->boolean('reconciled')->default(0);
            $table->string('created_from', 100)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'type']);
            $table->index('account_id');
            $table->index('category_id');
            $table->index('contact_id');
            $table->index('document_id');
        });

        // Transfers
        Schema::table('transfers', function (Blueprint $table) {
            $table->renameColumn('payment_id', 'expense_transaction_id');
        });

        Schema::table('transfers', function (Blueprint $table) {
            $table->renameColumn('revenue_id', 'income_transaction_id');
        });

        Schema::table('transfers', function (Blueprint $table) {
            $table->unsignedInteger('created_by')->nullable()->after('income_transaction_id');
            $table->string('created_from', 100)->nullable()->after('income_transaction_id');
        });

        // Users
        Schema::table('users', function (Blueprint $table) {
            $table->string('landing_page', 70)->nullable()->default('dashboard')->after('locale');
            $table->unsignedInteger('created_by')->nullable()->after('enabled');
            $table->string('created_from', 100)->nullable()->after('enabled');
        });

        Schema::dropIfExists('invoices');
        Schema::dropIfExists('invoice_histories');
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoice_item_taxes');
        Schema::dropIfExists('invoice_statuses');
        Schema::dropIfExists('invoice_totals');
        Schema::dropIfExists('bills');
        Schema::dropIfExists('bill_histories');
        Schema::dropIfExists('bill_items');
        Schema::dropIfExists('bill_item_taxes');
        Schema::dropIfExists('bill_statuses');
        Schema::dropIfExists('bill_totals');
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
