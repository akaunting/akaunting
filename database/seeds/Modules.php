<?php

namespace Database\Seeds;

use App\Abstracts\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

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

        Artisan::call('module:install', [
            'alias'     => 'offline-payments',
            'company'   => $company_id,
            'locale'    => session('locale', company($company_id)->locale),
        ]);

        Artisan::call('module:install', [
            'alias'     => 'paypal-standard',
            'company'   => $company_id,
            'locale'    => session('locale', company($company_id)->locale),
        ]);
    }
}
