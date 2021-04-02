<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->rememberToken();
            $table->string('picture')->nullable();
            $table->timestamp('last_logged_in_at')->nullable();
            $table->boolean('enabled')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['email', 'deleted_at']);
        });

        // Create table for associating companies to users (Many To Many Polymorphic)
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
        // Cascade table first
        Schema::dropIfExists('user_companies');
        Schema::dropIfExists('users');
    }
}
