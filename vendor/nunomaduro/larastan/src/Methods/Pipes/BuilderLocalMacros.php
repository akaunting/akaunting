<?php

declare(strict_types=1);

namespace NunoMaduro\Larastan\Methods\Pipes;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use function in_array;
use NunoMaduro\Larastan\Concerns;
use NunoMaduro\Larastan\Contracts\Methods\PassableContract;
use NunoMaduro\Larastan\Contracts\Methods\Pipes\PipeContract;
use NunoMaduro\Larastan\Methods\Macro;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\Generic\TemplateTypeMap;
use PHPStan\Type\ObjectType;
use PHPStan\Type\TypehintHelper;
use ReflectionFunction;
use ReflectionParameter;

/**
 * @internal
 */
final class BuilderLocalMacros implements PipeContract
{
    use Concerns\HasContainer;

    /**
     * {@inheritdoc}
     */
    public function handle(PassableContract $passable, Closure $next): void
    {
        $classReflection = $passable->getClassReflection();

        /** @var class-string $className */
        $className = $classReflection->getName();
        $found = false;

        if (($classReflection->isSubclassOf(Builder::class) || $classReflection->getName() === Builder::class) && $classReflection->getActiveTemplateTypeMap()->getType('TModelClass') !== null) {
            /** @var ObjectType $modelType */
            $modelType = $classReflection->getActiveTemplateTypeMap()->getType('TModelClass');

            if ($modelType instanceof ObjectType) {
                $classReflection = $passable->getBroker()->getClass($modelType->getClassName());
            }
        }

        if ($classReflection->isSubclassOf(Model::class) && in_array(SoftDeletes::class,
                trait_uses_recursive($classReflection->getName()), true)) {
            $methods = [
                'restore' => function (Builder $builder) {
                },
                'withTrashed' => function (Builder $builder, bool $withTrashed = true) {
                },
                'withoutTrashed' => function (Builder $builder) {
                },
                'onlyTrashed' => function (Builder $builder) {
                },
            ];

            if (array_key_exists($passable->getMethodName(), $methods)) {
                $reflectionFunction = new ReflectionFunction($methods[$passable->getMethodName()]);
                $parameters = $reflectionFunction->getParameters();
                unset($parameters[0]); // The query argument.
                $parameters = array_values($parameters);

                $macro = new Macro($className, $passable->getMethodName(), $reflectionFunction);

                $macro->setParameters($parameters);
                $macro->setIsStatic(true);

                $passable->setMethodReflection($passable->getMethodReflectionFactory()->create(
                    $classReflection, null,
                    $macro, TemplateTypeMap::createEmpty(),
                    array_map(function (ReflectionParameter $parameter) {
                        return TypehintHelper::decideTypeFromReflection($parameter->getType());
                    }, $parameters), new GenericObjectType(Builder::class, [new ObjectType($classReflection->getName())]),
                    null, null,
                    false, false,
                    false, null));
                $found = true;
            }
        }

        if (! $found) {
            $next($passable);
        }
    }
}
