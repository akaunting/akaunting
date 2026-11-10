<?php

namespace Tests\Feature\Common;

use App\Imports\Sales\Customers\Sheets\Customers;
use App\Models\Common\Contact;
use Illuminate\Support\Facades\DB;
use Tests\Feature\FeatureTestCase;

class ImportHasRowTest extends FeatureTestCase
{
    /**
     * An import reads the rows it already has once, where it read the whole table again for every row of the file
     * (Sentry 1V2V on the customers sheet). A row the file has twice is still taken once, as the import saves each
     * row before it reads the next one.
     */
    public function testItShouldReadTheRowsTheImportAlreadyHasOnce()
    {
        $this->loginAs();

        Contact::factory()->customer()->create([
            'company_id' => $this->company->id,
            'name' => 'Existing customer',
            'email' => 'existing@example.com',
        ]);

        $sheet = new Customers();

        $rows = [
            ['type' => 'customer', 'name' => 'Existing customer', 'email' => 'existing@example.com'],
            ['type' => 'customer', 'name' => 'New customer', 'email' => 'new@example.com'],
            ['type' => 'customer', 'name' => 'Customer without email', 'email' => null],
            ['type' => 'customer', 'name' => 'New customer', 'email' => 'new@example.com'],
        ];

        DB::flushQueryLog();
        DB::enableQueryLog();

        $answers = array_map(fn (array $row) => $sheet->hasRow($row), $rows);

        $queries = count(DB::getQueryLog());

        DB::disableQueryLog();

        $this->assertSame([true, false, false, true], $answers);
        $this->assertSame(1, $queries);
    }
}
