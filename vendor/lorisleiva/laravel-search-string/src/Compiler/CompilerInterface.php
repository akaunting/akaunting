<?php

namespace Lorisleiva\LaravelSearchString\Compiler;

use Illuminate\Support\Enumerable;
use Lorisleiva\LaravelSearchString\AST\Symbol;

interface CompilerInterface
{
    public function lex(?string $input): Enumerable;
    public function parse(?string $input): Symbol;
}
