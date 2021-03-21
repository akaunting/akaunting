<?php

namespace Lorisleiva\LaravelSearchString;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Lorisleiva\LaravelSearchString\Compiler\CompilerInterface;
use Lorisleiva\LaravelSearchString\Compiler\HoaCompiler;
use Lorisleiva\LaravelSearchString\Exceptions\InvalidSearchStringException;
use Lorisleiva\LaravelSearchString\Options\SearchStringOptions;

class SearchStringManager
{
    use SearchStringOptions;

    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->generateOptions($model);
    }

    public function getCompiler(): CompilerInterface
    {
        return new HoaCompiler($this);
    }

    public function getGrammarFile(): string
    {
        return __DIR__ . '/Compiler/Grammar.pp';
    }

    public function getGrammar()
    {
        return file_get_contents($this->getGrammarFile());
    }

    public function lex($input)
    {
        return $this->getCompiler()->lex($input);
    }

    public function parse($input)
    {
        return $this->getCompiler()->parse($input);
    }

    public function visit($input)
    {
        $ast = $this->parse($input);
        $visitors = $this->model->getSearchStringVisitors($this, $this->model->newQuery());

        foreach ($visitors as $visitor) {
            $ast = $ast->accept($visitor);
        }

        return $ast;
    }

    public function build(EloquentBuilder $builder, $input)
    {
        $ast = $this->parse($input);
        $visitors = $this->model->getSearchStringVisitors($this, $builder);

        foreach ($visitors as $visitor) {
            $ast = $ast->accept($visitor);
        }

        return $ast;
    }

    public function updateBuilder(EloquentBuilder $builder, $input)
    {
        try {
            $this->build($builder, $input);
        } catch (InvalidSearchStringException $e) {
            switch (config('search-string.fail')) {
                case 'exceptions':
                    throw $e;

                case 'no-results':
                    return $builder->whereRaw('1 = 0');

                default:
                    return $builder;
            }
        }
    }

    public function createBuilder($input)
    {
        $builder = $this->model->newQuery();
        $this->updateBuilder($builder, $input);
        return $builder;
    }

    public static function qualifyColumn($builder, $column)
    {
        if (strpos($column, '.') !== false) {
            return $column;
        }

        if (! $table = static::getTableFromBuilder($builder)) {
            return $column;
        }

        return $table . '.' . $column;
    }

    protected static function getTableFromBuilder($builder)
    {
        if ($builder instanceof EloquentBuilder) {
            return $builder->getQuery()->from;
        }

        if ($builder instanceof QueryBuilder) {
            return $builder->from;
        }
    }
}
