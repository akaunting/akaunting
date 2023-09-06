<?php

namespace Lorisleiva\LaravelSearchString\Console;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Lorisleiva\LaravelSearchString\Concerns\SearchString;
use Lorisleiva\LaravelSearchString\SearchStringManager;

class BaseCommand extends Command
{
    public function getModel(): ?Model
    {
        $modelClass = $this->argument('model');
        $modelClass = str_replace('/', '\\', $modelClass);
        $modelClass = Str::startsWith($modelClass, '\\') ? $modelClass : sprintf('App\\%s', $modelClass);

        if (! class_exists($modelClass) || ! is_subclass_of($modelClass, Model::class)) {
            throw new InvalidArgumentException(sprintf('Class [%s] must be a Eloquent Model.', $modelClass));
        }

        $model = new $modelClass();

        if (! method_exists($model, 'getSearchStringManager')) {
            throw new InvalidArgumentException(sprintf('Class [%s] must use the SearchString trait.', $modelClass));
        }

        return $model;
    }

    public function getManager(?Model $model = null): SearchStringManager
    {
        /** @var SearchString $model */
        $model = $model ?: $this->getModel();
        $manager = $model->getSearchStringManager();

        if (! $manager instanceof SearchStringManager) {
            throw new InvalidArgumentException('Method getSearchStringManager must return an instance of SearchStringManager.', $model);
        }

        return $manager;
    }

    public function getQuery(): string
    {
        return implode(' ', $this->argument('query'));
    }
}
