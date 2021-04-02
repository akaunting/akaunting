<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        // Create table for storing roles
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('display_name');
            $table->string('description')->nullable();
            $table->timestamps();

            $table->unique('name');
        });

        // Create table for associating roles to users (Many To Many Polymorphic)
        Schema::create('user_roles', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->string('user_type');

            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['user_id', 'role_id', 'user_type']);
        });

        // Create table for storing permissions
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('display_name');
            $table->string('description')->nullable();
            $table->timestamps();

            $table->unique('name');
        });

        // Create table for associating permissions to roles (Many-to-Many)
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->integer('role_id')->unsigned();
            $table->integer('permission_id')->unsigned();

            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['role_id', 'permission_id']);
        });

        // Create table for associating permissions to users (Many To Many Polymorphic)
        Schema::create('user_permissions', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('permission_id')->unsigned();
            $table->string('user_type');

            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['user_id', 'permission_id', 'user_type']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        // Cascade table first
        Schema::dropIfExists('user_permissions');
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('roles');
    }
}
