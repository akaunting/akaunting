<?php

use Doctrine\DBAL\Types\FloatType;
use Doctrine\DBAL\Types\Type;
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
        if (! Type::hasType('double')) {
            Type::addType('double', FloatType::class);
        }

        // Document Items
        Schema::table('document_items', function(Blueprint $table) {
            $table->double('quantity', 12, 2)->change();
        });

        // Reconciliations
        Schema::table('reconciliations', function (Blueprint $table) {
            $table->text('transactions')->nullable()->after('closing_balance');
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
