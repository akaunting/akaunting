<?php

namespace Database\Seeds;

use App\Models\Model;
use Artisan;
use Illuminate\Database\Seeder;

class Modules extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->create();

        Model::reguard();
    }

    private function create()
    {
        $company_id = $this->command->argument('company');

        Artisan::call('module:install', ['alias' => 'offlinepayment', 'company_id' => $company_id]);
        Artisan::call('module:install', ['alias' => 'paypalstandard', 'company_id' => $company_id]);
    }
}
