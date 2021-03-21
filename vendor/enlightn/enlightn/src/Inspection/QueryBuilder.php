<?php

namespace Enlightn\Enlightn\Inspection;

use Enlightn\Enlightn\Inspection\NodeVisitors\ClassInstantiationVisitor;
use Enlightn\Enlightn\Inspection\NodeVisitors\EvalExpressionVisitor;
use Enlightn\Enlightn\Inspection\NodeVisitors\ExitStatementVisitor;
use Enlightn\Enlightn\Inspection\NodeVisitors\FunctionCallVisitor;
use Enlightn\Enlightn\Inspection\NodeVisitors\GlobalStatementVisitor;
use Enlightn\Enlightn\Inspection\NodeVisitors\GlobalVariableVisitor;
use Enlightn\Enlightn\Inspection\NodeVisitors\StaticMethodCallVisitor;
use Enlightn\Enlightn\Inspection\NodeVisitors\UsesClassVisitor;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;

class QueryBuilder
{
    protected $traverser;

    protected $nodeVisitors = [];

    public function __construct()
    {
        $this->makeTraverser();
    }

    public static function create()
    {
        return new static;
    }

    public function makeTraverser()
    {
        $this->traverser = new NodeTraverser();
        $this->traverser->addVisitor(new NameResolver());
    }

    public function hasStaticCall(string $class, string $methodName, array $parameters = [])
    {
        return $this->addVisitor(
            new StaticMethodCallVisitor($class, $methodName, true, $parameters)
        );
    }

    public function doesntHaveStaticCall(string $class, string $methodName, array $parameters = [])
    {
        return $this->addVisitor(
            new StaticMethodCallVisitor($class, $methodName, false, $parameters)
        );
    }

    public function hasFunctionCall(string $functionName, array $parameters = [])
    {
        return $this->addVisitor(
            new FunctionCallVisitor($functionName, true, $parameters)
        );
    }

    public function doesntHaveFunctionCall(string $functionName, array $parameters = [])
    {
        return $this->addVisitor(
            new FunctionCallVisitor($functionName, false, $parameters)
        );
    }

    public function doesntHaveGlobalVariable(string $variableName)
    {
        return $this->addVisitor(new GlobalVariableVisitor($variableName, false));
    }

    public function doesntHaveGlobalStatement()
    {
        return $this->addVisitor(new GlobalStatementVisitor(false));
    }

    public function doesntHaveExitStatement()
    {
        return $this->addVisitor(new ExitStatementVisitor(false));
    }

    public function doesntHaveEvalExpression()
    {
        return $this->addVisitor(new EvalExpressionVisitor(false));
    }

    public function usesClass(string $class)
    {
        return $this->addVisitor(
            new UsesClassVisitor($class, true)
        );
    }

    public function doesntUseClass(string $class)
    {
        return $this->addVisitor(
            new UsesClassVisitor($class, false)
        );
    }

    public function instantiates(string $class, array $parameters = [])
    {
        return $this->addVisitor(
            new ClassInstantiationVisitor($class, true, $parameters)
        );
    }

    public function doesntInstantiate(string $class, array $parameters = [])
    {
        return $this->addVisitor(
            new ClassInstantiationVisitor($class, false, $parameters)
        );
    }

    public function getErrors($nodes)
    {
        $this->flush();

        $this->traverser->traverse($nodes);

        return $this->errorLineNumbers();
    }

    public function flush()
    {
        collect($this->nodeVisitors)->each(function ($visitor) {
            $visitor->flush();
        });
    }

    public function errorLineNumbers()
    {
        return collect($this->nodeVisitors)->map(function ($visitor) {
            return $visitor->passed() ? [] : $visitor->getLineNumbers();
        })->flatten()->unique()->toArray();
    }

    public function passed()
    {
        return collect($this->nodeVisitors)->every(function ($visitor) {
            return $visitor->passed();
        });
    }

    protected function addVisitor($visitor)
    {
        $this->nodeVisitors[] = $visitor;

        $this->traverser->addVisitor($visitor);

        return $this;
    }
}
