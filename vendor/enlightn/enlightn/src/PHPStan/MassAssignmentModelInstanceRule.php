<?php

namespace Enlightn\Enlightn\PHPStan;

use Illuminate\Database\Eloquent\Model;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;

class MassAssignmentModelInstanceRule implements Rule
{
    use AnalyzesNodes;

    /**
     * @return string
     */
    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    /**
     * @param Node $node
     * @param Scope $scope
     * @return string[]
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (! $node->name instanceof Node\Identifier
            || ! in_array($methodName = $node->name->toString(), ['forceFill', 'fill', 'update'])) {
            // We are only looking for fill(...) or forceFill(...) method calls
            return [];
        }

        if (! $this->isCalledOn($node->var, $scope, Model::class)) {
            // Method was not called on a Model, so no errors.
            return [];
        }

        if (isset($node->args[0]) && $this->retrievesRequestInput($node->args[0], $scope)) {
            return [
                sprintf(
                    "Call to %s method on a Model instance with request data may result in a "
                    ."mass assignment vulnerability.",
                    $methodName
                ),
            ];
        }

        return [];
    }

    /**
     * Determine whether the Arg was a request->all() method call.
     *
     * @param \PhpParser\Node\Arg $arg
     * @param \PHPStan\Analyser\Scope $scope
     * @return bool
     */
    protected function retrievesRequestInput(Node\Arg $arg, Scope $scope)
    {
        return $arg->value instanceof Node\Expr && $this->isRequestArrayData($arg->value, $scope);
    }
}
