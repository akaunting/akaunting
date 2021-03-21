<?php

namespace Lorisleiva\LaravelSearchString\Tests;

use Lorisleiva\LaravelSearchString\Tests\Stubs\Product;

abstract class VisitorTest extends TestCase
{
    public function visitors($manager, $builder, $model)
    {
        return [];
    }

    public function visit($input, $visitors = null, $model = null)
    {
        return parent::visit($input, $visitors ?? $this->getVisitors($model));
    }

    public function getVisitors($model = null)
    {
        $arguments = $this->getManagerBuilderAndModel($model);

        return $this->visitors(...$arguments);
    }

    public function getBuilder($input, $model = null)
    {
        list($manager, $builder, $model) = $this->getManagerBuilderAndModel($model);
        $this->visit($this->parse($input), $this->visitors($manager, $builder, $model));

        return $builder;
    }

    public function getManagerBuilderAndModel($model = null)
    {
        $manager = $this->getSearchStringManager($model = $model ?? new Product);

        return [$manager, $model->newQuery(), $model];
    }

    public function assertAstEquals($input, $expectedAst, $model = null)
    {
        $this->assertEquals($expectedAst, $this->visit($input, null, $model));
    }
}
