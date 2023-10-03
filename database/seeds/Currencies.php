<?php

namespace Database\Seeds;

use App\Abstracts\Model;
use App\Jobs\Setting\CreateCurrency;
use App\Traits\Jobs;
use Illuminate\Database\Seeder;

class Currencies extends Seeder
{
    use Jobs;

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

        $rows = [
            [
                'company_id' => $company_id,
                'name' => trans('demo.currencies.usd'),
                'code' => 'USD',
                'rate' => '1.00',
                'enabled' => '1',
                'precision' => currency('USD')->getPrecision(),
                'symbol' => currency('USD')->getSymbol(),
                'symbol_first' => currency('USD')->isSymbolFirst(),
                'decimal_mark' => currency('USD')->getDecimalMark(),
                'thousands_separator' => currency('USD')->getThousandsSeparator(),
            ],
        ];

        foreach ($rows as $row) {
            $row['created_from'] = 'core::seed';

            $this->dispatch(new CreateCurrency($row));
        }
    }
}
