<?php

namespace App\Builders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Paginator;

class Category extends Builder
{
    /**
     * Execute the query as a "select" statement.
     *
     * @param  array|string  $columns
     * @return \Illuminate\Support\Collection
     */
    public function get($columns = ['*'])
    {
        $collection = parent::get($columns);

        return $collection->withChildren('sub_categories', function ($list, $parent, $relation, $level, $addChildren) {
            $parent->load($relation);
            $parent->level = $level;

            $list->push($parent);

            if ($parent->$relation->count() == 0) {
                return;
            }

            foreach ($parent->$relation as $item) {
                $addChildren($list, $item, $relation, $level + 1, $addChildren);
            }
        });
    }

    /**
     * Get the categories excluding their children.
     *
     * @param  array|string  $columns
     * @return \Illuminate\Support\Collection
     */
    public function getWithoutChildren($columns = ['*'])
    {
        return parent::get($columns);
    }

    /**
     * Paginate the given query.
     *
     * @param  int|null  $perPage
     * @param  array  $columns
     * @param  string  $pageName
     * @param  int|null  $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     *
     * @throws \InvalidArgumentException
     */
    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $page = $page ?: Paginator::resolveCurrentPage($pageName);

        $perPage = $perPage ?: $this->model->getPerPage();

        $results = ($total = $this->toBase()->getCountForPagination())
        ? $this->forPage($page, $perPage)->getWithoutChildren($columns)
        : $this->model->newCollection();

        return $this->paginator($results, $total, $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => $pageName,
        ]);
    }
}
