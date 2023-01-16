<?php

use App\Traits\Database;
use Doctrine\DBAL\Types\FloatType;
use Doctrine\DBAL\Types\Type;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    use Database;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Type::hasType('double')) {
            Type::addType('double', FloatType::class);
        }

        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedInteger('split_id')->nullable()->after('parent_id');

            $table->foreign('split_id')->references('id')->on('transactions');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedInteger('parent_id')->nullable()->after('enabled');

            $table->foreign('parent_id')->references('id')->on('categories');
        });

        Schema::table('items', function(Blueprint $table) {
            $table->dropColumn('tax_id');
        });

        Schema::table('items', function(Blueprint $table) {
            $table->dropColumn('quantity');
        });

        Schema::table('items', function(Blueprint $table) {
            $table->string('type')->default('product')->after('company_id');
            $table->double('sale_price', 15, 4)->nullable()->change();
            $table->double('purchase_price', 15, 4)->nullable()->change();
        });

        Schema::table('recurring', function(Blueprint $table) {
            $table->renameColumn('count', 'limit_count')->nullable();
        });

        Schema::table('recurring', function (Blueprint $table) {
            $table->string('status')->default('active')->after('started_at');
            $table->string('limit_by')->default('count')->after('status');
            $table->dateTime('limit_date')->nullable()->after('limit_count');
            $table->boolean('auto_send')->default(1)->after('limit_date');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $number = $table->string('number')->after('type');

            if ($this->databaseDriverIs('sqlite')) {
                $number->nullable();
            }
        });

        Schema::table('accounts', function(Blueprint $table) {
            $table->string('type')->default('bank')->after('company_id');
        });

        if (! Schema::hasTable('personal_access_tokens')) {
            Schema::create('personal_access_tokens', function (Blueprint $table) {
                $table->id();
                $table->morphs('tokenable');
                $table->string('name');
                $table->string('token', 64)->unique();
                $table->text('abilities')->nullable();
                $table->timestamp('last_used_at')->nullable();
                $table->timestamps();
            });
        }

        Schema::create('user_invitations', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->integer('company_id')->unsigned();
            $table->string('token');
            $table->timestamps();
            $table->softDeletes();
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
