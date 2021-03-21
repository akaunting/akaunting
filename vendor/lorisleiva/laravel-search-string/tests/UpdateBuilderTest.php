<?php

namespace Lorisleiva\LaravelSearchString\Tests;

use Illuminate\Support\Facades\DB;
use Lorisleiva\LaravelSearchString\Tests\Concerns\DumpsSql;

class UpdateBuilderTest extends TestCase
{
    use DumpsSql;

    /** @test */
    public function it_can_override_the_builders_limit()
    {
        $builder = $this->build('limit:1')->limit(2);

        $this->assertEquals('select * from products limit 2', $this->dumpSql($builder));
    }

    /** @test */
    public function it_can_use_first_instead_of_get()
    {
        $queryLog = DB::pretend(function () {
            $this->build('')->first();
        });

        $this->assertEquals('select * from `products` limit 1', $queryLog[0]['query']);
    }
}
