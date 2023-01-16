<?php

namespace Database\Seeds;

use Illuminate\Database\Seeder;

class User extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(Dashboards::class);
    }
}
