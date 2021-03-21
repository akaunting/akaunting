<?php

namespace Lorisleiva\LaravelSearchString\Console;

use Illuminate\Support\Str;

class DumpSqlCommand extends BaseCommand
{
    protected $signature = 'search-string:sql {model} {query*}';
    protected $description = 'Parses the given search string and dumps the resulting SQL';

    public function handle()
    {
        $builder = $this->getManager()->createBuilder($this->getQuery());
        $sql = Str::replaceArray('?', $builder->getBindings(), $builder->toSql());

        $this->line($sql);
    }
}
