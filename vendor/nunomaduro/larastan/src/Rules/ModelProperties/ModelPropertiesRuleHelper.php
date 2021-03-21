<?php

declare(strict_types=1);

namespace NunoMaduro\Larastan\Rules\ModelProperties;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use NunoMaduro\Larastan\Types\ModelProperty\GenericModelPropertyType;
use NunoMaduro\Larastan\Types\ModelProperty\ModelPropertyType;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParameterReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Type\ArrayType;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeUtils;
use PHPStan\Type\UnionType;

class ModelPropertiesRuleHelper
{
    /**
     * @param MethodReflection $methodReflection
     * @param Scope            $scope
     * @param Node\Arg[]       $args
     * @param ClassReflection|null  $modelReflection
     *
     * @return string[]
     */
    public function check(MethodReflection $methodReflection, Scope $scope, array $args, ?ClassReflection $modelReflection = null): array
    {
        $modelPropertyParameter = $this->hasModelPropertyParameter($methodReflection, $scope, $args, $modelReflection);

        if (count($modelPropertyParameter) !== 2) {
            return [];
        }

        /** @var int $parameterIndex */
        /** @var ObjectType $modelType */
        [$parameterIndex, $modelType] = $modelPropertyParameter;

        $modelReflection = $modelType->getClassReflection();

        if ($modelReflection === null) {
            return [];
        }

        if ($modelReflection->isAbstract()) {
            return [];
        }

        if ($modelReflection->getName() === Model::class || ! $modelReflection->isSubclassOf(Model::class)) {
            return [];
        }

        if (! array_key_exists($parameterIndex, $args)) {
            return [];
        }

        $argValue = $args[$parameterIndex]->value;

        if (! $argValue instanceof Node\Expr) {
            return [];
        }

        $argType = $scope->getType($argValue);

        if ($argType instanceof ConstantArrayType) {
            $errors = [];

            $keyType = TypeUtils::generalizeType($argType->getKeyType());

            if ($keyType instanceof IntegerType) {
                $valueTypes = $argType->getValuesArray()->getValueTypes();
            } elseif ($keyType instanceof StringType) {
                $valueTypes = $argType->getKeysArray()->getValueTypes();
            } else {
                $valueTypes = [];
            }

            foreach ($valueTypes as $valueType) {
                // It could be something like `DB::raw`
                // We only want to analyze strings
                if (! $valueType instanceof ConstantStringType) {
                    continue;
                }

                // TODO: maybe check table names and columns here. And for JSON access maybe just the column name
                if (mb_strpos($valueType->getValue(), '.') !== false || mb_strpos($valueType->getValue(), '->') !== false) {
                    continue;
                }

                if (! $modelReflection->hasProperty($valueType->getValue())) {
                    $error = sprintf('Property \'%s\' does not exist in %s model.', $valueType->getValue(), $modelReflection->getName());

                    if ($methodReflection->getDeclaringClass()->getName() === BelongsToMany::class) {
                        $error .= sprintf(" If '%s' exists as a column on the pivot table, consider using 'wherePivot' or prefix the column with table name instead.", $valueType->getValue());
                    }

                    $errors[] = $error;
                }
            }

            return $errors;
        }

        if (! $argType instanceof ConstantStringType) {
            return [];
        }

        // TODO: maybe check table names and columns here. And for JSON access maybe just the column name
        if (mb_strpos($argType->getValue(), '.') !== false || mb_strpos($argType->getValue(), '->') !== false) {
            return [];
        }

        if (! $modelReflection->hasProperty($argType->getValue())) {
            $error = sprintf('Property \'%s\' does not exist in %s model.', $argType->getValue(), $modelReflection->getName());

            if ($methodReflection->getDeclaringClass()->getName() === BelongsToMany::class) {
                $error .= sprintf(" If '%s' exists as a column on the pivot table, consider using 'wherePivot' or prefix the column with table name instead.", $argType->getValue());
            }

            return [$error];
        }

        return [];
    }

    /**
     * @param MethodReflection $methodReflection
     * @param Scope            $scope
     * @param Node\Arg[]       $args
     * @param ClassReflection|null  $modelReflection
     *
     * @return array<int, int|Type>
     */
    public function hasModelPropertyParameter(
        MethodReflection $methodReflection,
        Scope $scope,
        array $args,
        ?ClassReflection $modelReflection = null
    ): array {
        /** @var ParameterReflection[] $parameters */
        $parameters = ParametersAcceptorSelector::selectFromArgs($scope, $args, $methodReflection->getVariants())->getParameters();

        foreach ($parameters as $index => $parameter) {
            $type = $parameter->getType();

            if ($type instanceof UnionType) {
                foreach ($type->getTypes() as $innerType) {
                    if ($innerType instanceof GenericModelPropertyType) {
                        return [$index, $innerType->getGenericType()];
                    }

                    if ($innerType instanceof ModelPropertyType && $modelReflection !== null) {
                        return [$index, new ObjectType($modelReflection->getName())];
                    }
                }
            } elseif ($type instanceof ArrayType) {
                $keyType = $type->getKeyType();
                $itemType = $type->getItemType();

                if ($keyType instanceof GenericModelPropertyType) {
                    return [$index, $keyType->getGenericType()];
                }

                if ($itemType instanceof GenericModelPropertyType) {
                    return [$index, $itemType->getGenericType()];
                }

                if ($modelReflection !== null && (($keyType instanceof ModelPropertyType) || ($itemType instanceof ModelPropertyType))) {
                    return [$index, new ObjectType($modelReflection->getName())];
                }
            } else {
                if ($type instanceof GenericModelPropertyType) {
                    return [$index, $type->getGenericType()];
                }

                if ($modelReflection !== null && $type instanceof ModelPropertyType) {
                    return [$index, new ObjectType($modelReflection->getName())];
                }
            }
        }

        return [];
    }
}
