<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CoreV1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Accounts
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('name');
            $table->string('number');
            $table->string('currency_code');
            $table->double('opening_balance', 15, 4)->default('0.0000');
            $table->string('bank_name')->nullable();
            $table->string('bank_phone')->nullable();
            $table->text('bank_address')->nullable();
            $table->boolean('enabled')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        // Bills
        Schema::create('bills', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('bill_number');
            $table->string('order_number')->nullable();
            $table->string('bill_status_code');
            $table->dateTime('billed_at');
            $table->dateTime('due_at');
            $table->double('amount', 15, 4);
            $table->string('currency_code');
            $table->double('currency_rate', 15, 8);
            $table->integer('category_id')->default(1);
            $table->integer('vendor_id');
            $table->string('vendor_name');
            $table->string('vendor_email')->nullable();
            $table->string('vendor_tax_number')->nullable();
            $table->string('vendor_phone')->nullable();
            $table->text('vendor_address')->nullable();
            $table->text('notes')->nullable();
            $table->integer('parent_id')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->unique(['company_id', 'bill_number', 'deleted_at']);
        });

        Schema::create('bill_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('bill_id');
            $table->string('status_code');
            $table->boolean('notify');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::create('bill_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('bill_id');
            $table->integer('item_id')->nullable();
            $table->string('name');
            $table->string('sku')->nullable();
            $table->double('quantity', 7, 2);
            $table->double('price', 15, 4);
            $table->double('total', 15, 4);
            $table->float('tax', 15, 4)->default('0.0000');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::create('bill_item_taxes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('bill_id');
            $table->integer('bill_item_id');
            $table->integer('tax_id');
            $table->string('name');
            $table->double('amount', 15, 4)->default('0.0000');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::create('bill_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('name');
            $table->string('code');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::create('bill_totals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('bill_id');
            $table->string('code')->nullable();
            $table->string('name');
            $table->double('amount', 15, 4);
            $table->integer('sort_order');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        // Categories
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('name');
            $table->string('type');
            $table->string('color');
            $table->boolean('enabled')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        // Companies
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('domain');
            $table->boolean('enabled')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        // Currencies
        Schema::create('currencies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('name');
            $table->string('code');
            $table->double('rate', 15, 8);
            $table->string('precision')->nullable();
            $table->string('symbol')->nullable();
            $table->integer('symbol_first')->default(1);
            $table->string('decimal_mark')->nullable();
            $table->string('thousands_separator')->nullable();
            $table->tinyInteger('enabled')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->unique(['company_id', 'code', 'deleted_at']);
        });

        // Invoices
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('invoice_number');
            $table->string('order_number')->nullable();
            $table->string('invoice_status_code');
            $table->dateTime('invoiced_at');
            $table->dateTime('due_at');
            $table->double('amount', 15, 4);
            $table->string('currency_code');
            $table->double('currency_rate', 15, 8);
            $table->integer('category_id')->default(1);
            $table->integer('customer_id');
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_tax_number')->nullable();
            $table->string('customer_phone')->nullable();
            $table->text('customer_address')->nullable();
            $table->text('notes')->nullable();
            $table->integer('parent_id')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->unique(['company_id', 'invoice_number', 'deleted_at']);
        });

        Schema::create('invoice_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('invoice_id');
            $table->string('status_code');
            $table->boolean('notify');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('invoice_id');
            $table->integer('item_id')->nullable();
            $table->string('name');
            $table->string('sku')->nullable();
            $table->double('quantity', 7, 2);
            $table->double('price', 15, 4);
            $table->double('total', 15, 4);
            $table->double('tax', 15, 4)->default('0.0000');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::create('invoice_item_taxes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('invoice_id');
            $table->integer('invoice_item_id');
            $table->integer('tax_id');
            $table->string('name');
            $table->double('amount', 15, 4)->default('0.0000');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::create('invoice_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('name');
            $table->string('code');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::create('invoice_totals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('invoice_id');
            $table->string('code')->nullable();
            $table->string('name');
            $table->double('amount', 15, 4);
            $table->integer('sort_order');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        // Items
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('name');
            $table->string('sku');
            $table->text('description')->nullable();
            $table->double('sale_price', 15, 4);
            $table->double('purchase_price', 15, 4);
            $table->integer('quantity');
            $table->integer('category_id')->nullable();
            $table->integer('tax_id')->nullable();
            $table->boolean('enabled')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->unique(['company_id', 'sku', 'deleted_at']);
        });

        // Jobs
        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('queue');
            $table->longText('payload');
            $table->tinyInteger('attempts')->unsigned();
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');

            $table->index(['queue', 'reserved_at']);
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        // Media
        Schema::table('media', function (Blueprint $table) {
            $table->softDeletes();

            $table->index(['disk', 'directory']);
            $table->unique(['disk', 'directory', 'filename', 'extension', 'deleted_at']);
        });

        // Modules
        Schema::create('modules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('alias');
            $table->integer('status');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->unique(['company_id', 'alias', 'deleted_at']);
        });

        Schema::create('module_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('module_id');
            $table->string('category');
            $table->string('version');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'module_id']);
        });

        // Notifications
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        // Password resets
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email');
            $table->string('token');
            $table->timestamp('created_at')->nullable();

            $table->index('email');
        });

        // Permissions
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('display_name');
            $table->string('description')->nullable();
            $table->timestamps();

            $table->unique('name');
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('display_name');
            $table->string('description')->nullable();
            $table->timestamps();

            $table->unique('name');
        });

        Schema::create('role_permissions', function (Blueprint $table) {
            $table->integer('role_id')->unsigned();
            $table->integer('permission_id')->unsigned();

            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['role_id', 'permission_id']);
        });

        Schema::create('user_permissions', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('permission_id')->unsigned();
            $table->string('user_type');

            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['user_id', 'permission_id', 'user_type']);
        });

        Schema::create('user_roles', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->string('user_type');

            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['user_id', 'role_id', 'user_type']);
        });

        // Reconciliations
        Schema::create('reconciliations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('account_id');
            $table->dateTime('started_at');
            $table->dateTime('ended_at');
            $table->double('closing_balance', 15, 4)->default('0.0000');
            $table->boolean('reconciled');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        // Recurring
        Schema::create('recurring', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->morphs('recurable');
            $table->string('frequency');
            $table->integer('interval')->default(1);
            $table->dateTime('started_at');
            $table->integer('count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        // Sessions
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->unique();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('payload');
            $table->integer('last_activity');
        });

        // Settings
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('key');
            $table->text('value')->nullable();

            $table->index('company_id');
            $table->unique(['company_id', 'key']);
        });

        // Taxes
        Schema::create('taxes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('name');
            $table->double('rate', 15, 4);
            $table->string('type')->default('normal');
            $table->boolean('enabled')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        // Transfers
        Schema::create('transfers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('payment_id');
            $table->integer('revenue_id');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        // Users
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->rememberToken();
            $table->timestamp('last_logged_in_at')->nullable();
            $table->string('locale')->default(config('app.locale'));
            $table->boolean('enabled')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['email', 'deleted_at']);
        });

        Schema::create('user_companies', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('company_id')->unsigned();
            $table->string('user_type');

            $table->primary(['user_id', 'company_id', 'user_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('accounts');
        Schema::drop('bills');
        Schema::drop('bill_histories');
        Schema::drop('bill_items');
        Schema::drop('bill_item_taxes');
        Schema::drop('bill_totals');
        Schema::drop('categories');
        Schema::drop('companies');
        Schema::drop('currencies');
        Schema::drop('invoices');
        Schema::drop('invoice_histories');
        Schema::drop('invoice_items');
        Schema::drop('invoice_item_taxes');
        Schema::drop('invoice_totals');
        Schema::drop('items');
        Schema::drop('jobs');
        Schema::drop('failed_jobs');
        Schema::drop('mediables');
        Schema::drop('media');
        Schema::drop('modules');
        Schema::drop('module_histories');
        Schema::drop('notifications');
        Schema::drop('password_resets');

        // Cascade table first
        Schema::drop('user_permissions');
        Schema::drop('role_permissions');
        Schema::drop('permissions');
        Schema::drop('user_roles');
        Schema::drop('roles');

        Schema::drop('reconciliations');
        Schema::drop('recurring');
        Schema::drop('sessions');
        Schema::drop('settings');
        Schema::drop('taxes');
        Schema::drop('transfers');
        Schema::drop('users');
        Schema::drop('user_companies');
    }
}
