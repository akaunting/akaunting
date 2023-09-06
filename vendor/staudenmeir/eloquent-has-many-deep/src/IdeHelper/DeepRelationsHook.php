<?php

namespace Staudenmeir\EloquentHasManyDeep\IdeHelper;

use Barryvdh\LaravelIdeHelper\Console\ModelsCommand;
use Barryvdh\LaravelIdeHelper\Contracts\ModelHookInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Throwable;

class DeepRelationsHook implements ModelHookInterface
{
    public function run(ModelsCommand $command, Model $model): void
    {
        $traits = class_uses_recursive($model);

        if (!in_array(HasRelationships::class, $traits)) {
            return;
        }

        $methods = (new ReflectionClass($model))->getMethods(ReflectionMethod::IS_PUBLIC);

        foreach ($methods as $method) {
            if ($method->isStatic() || $method->getNumberOfParameters() > 0) {
                continue;
            }

            try {
                $relation = $method->invoke($model);
            } catch (Throwable) {
                continue;
            }

            if (!$relation instanceof HasManyDeep) {
                continue;
            }

            $this->addRelation($command, $method, $relation);
        }
    }

    protected function addRelation(ModelsCommand $command, ReflectionMethod $method, HasManyDeep $relation): void
    {
        $isHasOneDeep = $relation instanceof HasOneDeep;

        $type = $isHasOneDeep
            ? '\\' . $relation->getRelated()::class
            : '\\' . Collection::class . '|\\' . $relation->getRelated()::class . '[]';

        $command->setProperty(
            $method->getName(),
            $type,
            true,
            false,
            '',
            $isHasOneDeep
        );

        if (!$isHasOneDeep) {
            $command->setProperty(
                Str::snake($method->getName()) . '_count',
                'int',
                true,
                false,
                null,
                true
            );
        }
    }
}
