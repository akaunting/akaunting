<?php

namespace Enlightn\Enlightn\PHPStan;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Type\IntegerType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\UnionType;

trait AnalyzesNodes
{
    /**
     * Determine whether the Expr was called on a Request instance.
     *
     * @param \PhpParser\Node\Expr $expr
     * @param \PHPStan\Analyser\Scope $scope
     * @return bool
     */
    protected function isRequestArrayData(Expr $expr, Scope $scope)
    {
        $logic = (new RequestArrayDataType(new UnionType([new StringType, new IntegerType]), new RequestDataType))
            ->canBeSuperTypeOf($scope->getType($expr));

        return $logic->yes() || $logic->maybe();
    }

    /**
     * Determine whether the Expr was called on a Request instance.
     *
     * @param \PhpParser\Node\Expr $expr
     * @param \PHPStan\Analyser\Scope $scope
     * @return bool
     */
    protected function isRequestData(Expr $expr, Scope $scope)
    {
        $logic = (new RequestDataType)->canBeSuperTypeOf($scope->getType($expr));

        return $logic->yes() || $logic->maybe();
    }

    /**
     * Determine whether the Expr was called on a Request instance.
     *
     * @param \PhpParser\Node\Expr $expr
     * @param \PHPStan\Analyser\Scope $scope
     * @return bool
     */
    protected function isCalledOnRequest(Expr $expr, Scope $scope)
    {
        return $this->isCalledOn($expr, $scope, Request::class);
    }

    /**
     * Determine whether the Expr was called on a Builder instance.
     *
     * @param \PhpParser\Node\Expr $expr
     * @param \PHPStan\Analyser\Scope $scope
     * @return bool
     */
    protected function isCalledOnBuilder(Expr $expr, Scope $scope)
    {
        return $this->isCalledOn($expr, $scope, EloquentBuilder::class)
            || $this->isCalledOn($expr, $scope, QueryBuilder::class);
    }

    /**
     * @param \PhpParser\Node\Arg $arg
     * @param \PHPStan\Analyser\Scope $scope
     * @return bool
     */
    protected function retrievesRequestInput(Node\Arg $arg, Scope $scope)
    {
        return $this->analyzeRecursively($arg->value, $scope, [$this, 'hasRequestCall']);
    }

    /**
     * @param \PhpParser\Node $node
     * @param \PHPStan\Analyser\Scope $scope
     * @return bool
     */
    protected function hasRequestCall(Node $node, Scope $scope)
    {
        if ($node instanceof Expr) {
            return $this->isRequestData($node, $scope)
                || $this->isRequestArrayData($node, $scope);
        }

        return false;
    }

    /**
     * Determine whether the Expr was called on a class instance.
     *
     * @param \PhpParser\Node\Expr $expr
     * @param \PHPStan\Analyser\Scope $scope
     * @param string $className
     * @return bool
     */
    protected function isCalledOn(Expr $expr, Scope $scope, string $className)
    {
        $calledOnType = $scope->getType($expr);

        return (new ObjectType($className))->isSuperTypeOf($calledOnType)->yes();
    }

    /**
     * Determine whether the Expr was called on a class instance.
     *
     * @param \PhpParser\Node\Expr $expr
     * @param \PHPStan\Analyser\Scope $scope
     * @param string $className
     * @return bool
     */
    protected function isMaybeCalledOn(Expr $expr, Scope $scope, string $className)
    {
        $calledOnType = $scope->getType($expr);

        return (new ObjectType($className))->isSuperTypeOf($calledOnType)->maybe();
    }

    /**
     * Determine if a node has the search string.
     *
     * @param \PhpParser\Node $node
     * @param \PHPStan\Analyser\Scope $scope
     * @param string $search
     * @return bool|null
     */
    protected function hasString(Node $node, Scope $scope, string $search)
    {
        if ($node instanceof MethodCall
            || $node instanceof Expr\FuncCall
            || $node instanceof Expr\StaticCall
            || $node instanceof Expr\New_) {
            // If the node is a method call or function call, stop the recursion as we don't want
            // to recursively search for strings inside nodes such as func/method calls.
            return null;
        }

        return $node instanceof Node\Scalar\String_ && Str::contains($node->value, $search);
    }

    /**
     * Recursively analyze if any of the subnodes satisfy the callback condition.
     * If the callback returns true, it will return true. False will continue searching. Null will stop recursion.
     *
     * @param \PhpParser\Node $node
     * @param \PHPStan\Analyser\Scope $scope
     * @param callable $callback
     * @return bool
     */
    protected function analyzeRecursively(Node $node, Scope $scope, $callback)
    {
        if ($result = call_user_func($callback, $node, $scope)) {
            return true;
        }

        if (is_null($result)) {
            // Stop the recursion here. Do not check subnodes.
            return false;
        }

        foreach ($node->getSubNodeNames() as $subNodeName) {
            $subNodes = Arr::wrap($node->{$subNodeName});

            foreach ($subNodes as $subNode) {
                if ($subNode instanceof Node
                    && ($result = $this->analyzeRecursively($subNode, $scope, $callback))) {
                    return true;
                }
            }
        }

        return false;
    }

    protected function blacklistedRequestDataMethods()
    {
        return ['input', 'get', 'post', 'query', 'old', 'cookie', 'header'];
    }

    protected function allBlacklistedRequestMethods()
    {
        return array_merge($this->blacklistedRequestDataMethods(), ['all', 'except']);
    }
}
