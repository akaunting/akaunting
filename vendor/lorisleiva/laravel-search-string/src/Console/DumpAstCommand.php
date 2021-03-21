<?php

namespace Lorisleiva\LaravelSearchString\Console;

use Lorisleiva\LaravelSearchString\Visitors\DumpVisitor;

class DumpAstCommand extends BaseCommand
{
    protected $signature = 'search-string:ast {model} {query*}';
    protected $description = 'Parses the given search string and dumps the resulting AST';

    public function handle()
    {
        $ast = $this->getManager()->visit($this->getQuery());
        $dump = $ast->accept(new DumpVisitor());

        $this->getOutput()->write($dump);
    }
}
