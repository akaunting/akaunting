<?php

namespace Database\Seeds;

use App\Abstracts\Model;
use App\Models\Banking\Account;
use App\Models\Common\Contact;
use App\Models\Common\Item;
use App\Models\Document\Document;
use App\Models\Setting\Category;
use App\Models\Setting\Tax;
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

        config(['mail.default' => 'array']);

        $count = (int) $this->command->option('count');
        $small_count = ($count <= 10) ? $count : 10;

        $company = (int) $this->command->option('company');

        $this->command->info('Creating sample data...');

        $bar = $this->command->getOutput()->createProgressBar(7);
        $bar->setFormat('verbose');

        $bar->start();

        Contact::factory()->company($company)->count($count)->create();
        $bar->advance();

        Category::factory()->company($company)->count($count)->create();
        $bar->advance();

        Tax::factory()->company($company)->count($small_count)->enabled()->create();
        $bar->advance();

        Item::factory()->company($company)->count($count)->create();
        $bar->advance();

        Account::factory()->company($company)->count($small_count)->create();
        $bar->advance();

        Document::factory()->company($company)->bill()->count($count)->create();
        $bar->advance();

        Document::factory()->company($company)->invoice()->count($count)->create();
        $bar->advance();

        $bar->finish();

        $this->command->info('');
        $this->command->info('Sample data created.');

        Model::unguard();
    }
}
