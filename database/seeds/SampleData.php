<?php

namespace Database\Seeds;

use App\Abstracts\Model;
use App\Jobs\Banking\CreateAccount;
use App\Jobs\Banking\CreateDocumentTransaction;
use App\Jobs\Common\CreateContact;
use App\Jobs\Common\CreateItem;
use App\Jobs\Purchase\CreateBill;
use App\Jobs\Sale\CreateInvoice;
use App\Models\Banking\Account;
use App\Models\Banking\Transaction;
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

        $faker = Factory::create();

        $count = $this->command->option('count');

        for ($i = 0; $i < $count; $i++) {
            $this->dispatch(new CreateContact(factory(Contact::class)->raw()));
        }

        for ($i = 0; $i < $count; $i++) {
            $this->dispatch(new CreateItem(factory(Item::class)->raw()));
        }

        for ($i = 0; $i < $count; $i++) {
            $this->dispatch(new CreateAccount(factory(Account::class)->raw()));
        }

        for ($i = 0; $i < $count; $i++) {
            $this->dispatch(new CreateBill(factory(Bill::class)->state('items')->raw()));
        }

        for ($i = 0; $i < $count; $i++) {
            $this->dispatch(new CreateInvoice(factory(Invoice::class)->state('items')->raw()));
        }

        for ($i = 0; $i < $count; $i++) {
            $amount   = $faker->randomFloat(2, 1, 1000);
            $invoices = Invoice::where('status', 'sent')->get();

            if (0 === $invoices->count()) {
                continue;
            }

            $invoice = $invoices->random(1)->first();

            $this->dispatch(
                new CreateDocumentTransaction(
                    $invoice,
                    factory(Transaction::class)->state('income')->raw(
                        [
                            'contact_id'  => $invoice->contact_id,
                            'document_id' => $invoice->id,
                            'amount'      => $amount > $invoice->amount ? $invoice->amount : $amount,
                        ]
                    )
                )
            );
        }

        for ($i = 0; $i < $count; $i++) {
            $amount  = $faker->randomFloat(2, 1, 1000);
            $bills   = Bill::where('status', 'received')->get();

            if (0 === $bills->count()) {
                continue;
            }

            $bill = $bills->random(1)->first();

            $this->dispatch(
                new CreateDocumentTransaction(
                    $bill,
                    factory(Transaction::class)->state('expense')->raw(
                        [
                            'contact_id'  => $bill->contact_id,
                            'document_id' => $bill->id,
                            'amount'      => $amount > $bill->amount ? $bill->amount : $amount,
                        ]
                    )
                )
            );
        }

        Model::unguard();
    }
}
