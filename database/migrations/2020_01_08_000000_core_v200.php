<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CoreV200 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Footer column
        Schema::table('invoices', function (Blueprint $table) {
            $table->text('footer')->nullable()->after('notes');
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
            $table->string('website')->nullable();
            $table->string('currency_code', 3);
            $table->boolean('enabled');
            $table->string('reference')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'type']);
            $table->unique(['company_id', 'type', 'email', 'deleted_at']);
        });

        $rename_bills = [
            'bill_status_code' => 'status',
            'vendor_id' => 'contact_id',
            'vendor_name' => 'contact_name',
            'vendor_email' => 'contact_email',
            'vendor_tax_number' => 'contact_tax_number',
            'vendor_phone' => 'contact_phone',
            'vendor_address' => 'contact_address',
        ];

        foreach ($rename_bills as $from => $to) {
            Schema::table('bills', function (Blueprint $table) use ($from, $to) {
                $table->renameColumn($from, $to);
            });
        }

        Schema::table('bill_histories', function (Blueprint $table) {
            $table->renameColumn('status_code', 'status');
        });

        Schema::drop('bill_statuses');

        $rename_invoices = [
            'invoice_status_code' => 'status',
            'customer_id' => 'contact_id',
            'customer_name' => 'contact_name',
            'customer_email' => 'contact_email',
            'customer_tax_number' => 'contact_tax_number',
            'customer_phone' => 'contact_phone',
            'customer_address' => 'contact_address',
        ];

        foreach ($rename_invoices as $from => $to) {
            Schema::table('invoices', function (Blueprint $table) use ($from, $to) {
                $table->renameColumn($from, $to);
            });
        }

        Schema::table('invoice_histories', function (Blueprint $table) {
            $table->renameColumn('status_code', 'status');
        });

        Schema::drop('invoice_statuses');

        // Dashboards
        Schema::create('dashboards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('name');
            $table->boolean('enabled')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id']);
        });

        Schema::create('user_dashboards', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('dashboard_id')->unsigned();
            $table->string('user_type', 20);

            $table->primary(['user_id', 'dashboard_id', 'user_type']);
        });

        Schema::create('widgets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('dashboard_id');
            $table->string('class');
            $table->string('name');
            $table->integer('sort')->default(0);
            $table->text('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'dashboard_id']);
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
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->unique(['company_id', 'alias', 'deleted_at']);
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

        // Reports
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('class');
            $table->string('name');
            $table->text('description');
            $table->text('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        // Transactions
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('type');
            $table->dateTime('paid_at');
            $table->double('amount', 15, 4);
            $table->string('currency_code', 3);
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
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'type']);
        });

        Schema::table('transfers', function (Blueprint $table) {
            $table->renameColumn('payment_id', 'expense_transaction_id');
        });

        Schema::table('transfers', function (Blueprint $table) {
            $table->renameColumn('revenue_id', 'income_transaction_id');
        });

        // Domain column
        Schema::table('companies', function (Blueprint $table) {
            $table->string('domain')->nullable()->change();
        });

        // Status column
        Schema::table('modules', function (Blueprint $table) {
            $table->renameColumn('status', 'enabled');
        });

        // Sku and quantity columns
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

        // Landing page column
        Schema::table('users', function (Blueprint $table) {
            $table->string('landing_page', 70)->nullable()->default('dashboard')->after('locale');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('footer');
        });

        Schema::drop('contacts');
        Schema::drop('dashboards');
        Schema::drop('user_dashboards');
        Schema::drop('widgets');
        Schema::drop('email_templates');
        Schema::drop('firewall_ips');
        Schema::drop('firewall_logs');
        Schema::drop('reports');
        Schema::drop('transactions');

        Schema::table('companies', function (Blueprint $table) {
            $table->string('domain')->change();
        });

        Schema::table('modules', function (Blueprint $table) {
            $table->renameColumn('enabled', 'status');
        });

        Schema::table('items', function (Blueprint $table) {
            $table->string('sku')->change();
            $table->integer('quantity')->change();
            $table->unique(['company_id', 'sku', 'deleted_at']);
        });
    }
}
