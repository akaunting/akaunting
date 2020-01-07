<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDashboardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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
            $table->text('settings')->nullable();
            $table->integer('sort')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'dashboard_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('dashboards');
        Schema::drop('user_dashboards');
        Schema::drop('widgets');
    }
}
