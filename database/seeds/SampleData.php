<?php

namespace Database\Seeds;

use App\Abstracts\Model;
use App\Models\Banking\Account;
use App\Models\Common\Contact;
use App\Models\Common\Item;
use App\Models\Purchase\Bill;
use App\Models\Sale\Invoice;
use App\Traits\Jobs;
use Faker\Factory;
use Illuminate\Database\Seeder;

class SampleData extends Seeder
{
    use Jobs;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::reguard();

        $count = $this->command->option('count');

        factory(Contact::class, (int)$count)->create();
        factory(Item::class, (int)$count)->create();
        factory(Account::class, (int)$count)->create();
        factory(Bill::class, (int)$count)->create();
        factory(Invoice::class, (int)$count)->create();

        Model::unguard();
    }
}
