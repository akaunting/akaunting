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
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('code')->nullable()->after('company_id');
            $table->text('description')->nullable()->after('color');
        });

        Schema::table('document_items', function (Blueprint $table) {
            $table->unsignedInteger('category_id')->nullable()->after('item_id');

            $table->index('category_id');
        });

        Schema::table('contacts', function (Blueprint $table) {
            $table->unsignedInteger('category_id')->nullable()->after('user_id');

            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('code');
            $table->dropColumn('description');
        });

        Schema::table('document_items', function (Blueprint $table) {
            $table->dropIndex(['category_id']);
            $table->dropColumn('category_id');
        });

        Schema::table('contacts', function (Blueprint $table) {
            $table->dropIndex(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};
