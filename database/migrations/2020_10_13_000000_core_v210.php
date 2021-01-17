<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CoreV210 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('failed_jobs', function (Blueprint $table) {
            $table->string('uuid')->after('id')->nullable()->unique();
        });

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
            $table->text('notes')->nullable();
            $table->text('footer')->nullable();
            $table->unsignedInteger('parent_id')->default(0);

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
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('type');
            $table->index('document_id');
        });

        Schema::create('item_taxes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('item_id');
            $table->integer('tax_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'item_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('failed_jobs', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });

        Schema::drop('documents');
        Schema::drop('document_histories');
        Schema::drop('document_items');
        Schema::drop('document_item_taxes');
        Schema::drop('document_totals');
        Schema::drop('item_taxes');
    }
}
