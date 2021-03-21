<?php

declare(strict_types=1);

namespace NunoMaduro\Larastan\Rules\ModelProperties;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleLevelHelper;
use PHPStan\Type\ErrorType;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;

/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Expr\MethodCall>
 */
class ModelPropertyRule implements Rule
{
    /** @var ModelPropertiesRuleHelper */
    private $modelPropertiesRuleHelper;
    /**
     * @var RuleLevelHelper
     */
    private $ruleLevelHelper;

    public function __construct(ModelPropertiesRuleHelper $ruleHelper, RuleLevelHelper $ruleLevelHelper)
    {
        $this->modelPropertiesRuleHelper = $ruleHelper;
        $this->ruleLevelHelper = $ruleLevelHelper;
    }

    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    /**
     * @param MethodCall $node
     * @param Scope $scope
     *
     * @return string[]
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (! $node->name instanceof Node\Identifier) {
            return [];
        }

        if (count($node->args) === 0) {
            return [];
        }

        $name = $node->name->name;
        $typeResult = $this->ruleLevelHelper->findTypeToCheck(
            $scope,
            $node->var,
            '',
            static function (Type $type) use ($name): bool {
                return $type->canCallMethods()->yes() && $type->hasMethod($name)->yes();
            }
        );

        $type = $typeResult->getType();

        if ($type instanceof ErrorType) {
            return [];
        }

        if (! $type->hasMethod($name)->yes()) {
            return [];
        }

        $modelReflection = $this->findModelReflectionFromType($type);

        $methodReflection = $type->getMethod($name, $scope);

        return $this->modelPropertiesRuleHelper->check($methodReflection, $scope, $node->args, $modelReflection);
    }

    private function findModelReflectionFromType(Type $type): ?ClassReflection
    {
        if ((new ObjectType(Builder::class))->isSuperTypeOf($type)->no() &&
            (new ObjectType(EloquentBuilder::class))->isSuperTypeOf($type)->no() &&
            (new ObjectType(Relation::class))->isSuperTypeOf($type)->no() &&
            (new ObjectType(Model::class))->isSuperTypeOf($type)->no()
        ) {
            return null;
        }

        // We expect it to be generic builder or relation class with model type inside
        if ((! $type instanceof GenericObjectType) && (new ObjectType(Model::class))->isSuperTypeOf($type)->no()) {
            return null;
        }

        if ($type instanceof GenericObjectType) {
            $modelType = $type->getTypes()[0];
        } else {
            $modelType = $type;
        }

        $modelType = TypeCombinator::removeNull($modelType);

        if (! $modelType instanceof ObjectType) {
            return null;
        }

        if ($modelType->getClassName() === Model::class) {
            return null;
        }

        $modelReflection = $modelType->getClassReflection();

        if ($modelReflection === null) {
            return null;
        }

        if ($modelReflection->isAbstract()) {
            return null;
        }

        return $modelReflection;
    }
}
