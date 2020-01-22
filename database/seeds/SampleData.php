<?php

namespace Database\Seeds;

use App\Abstracts\Model;
use App\Models\Banking\Account;
use App\Models\Common\Contact;
use App\Models\Common\Item;
use App\Models\Purchase\Bill;
use App\Models\Sale\Invoice;
use Illuminate\Database\Seeder;

class SampleData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::reguard();

        $count = (int) $this->command->option('count');

        factory(Contact::class, $count)->create();
        factory(Item::class, $count)->create();
        factory(Account::class, $count)->create();
        factory(Bill::class, $count)->create();
        factory(Invoice::class, $count)->create();

        Model::unguard();
    }
}
