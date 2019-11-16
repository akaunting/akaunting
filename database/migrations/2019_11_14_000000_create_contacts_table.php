<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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

        $rename_invoices = [
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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('contacts');
    }
}
