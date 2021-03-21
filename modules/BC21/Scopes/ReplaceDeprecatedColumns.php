<?php

namespace App\Scopes;


use App\Models\Purchase\Bill;
use App\Models\Sale\Invoice;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Collection;

class ReplaceDeprecatedColumns implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param Builder $builder
     * @param Model   $model
     *
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        switch (get_class($model)) {
            case Invoice::class:
                $replacements = [
                    'invoiced_at'    => 'issued_at',
                    'invoice_number' => 'document_number',
                ];
                break;
            case Bill::class:
                $replacements = [
                    'billed_at'   => 'issued_at',
                    'bill_number' => 'document_number',
                ];
                break;
        }

        if (false === isset($replacements)) {
            return;
        }


        $query = $builder->getQuery();

        foreach ($replacements as $column => $replace) {
            if ($query->orders !== null) {
                $query->orders = $this->replaceColumn($query->orders, $column, $replace);
            }

            if ($query->wheres !== null) {
                $query->wheres = $this->replaceColumn($query->wheres, $column, $replace);
            }

            if ($query->havings !== null) {
                $query->havings = $this->replaceColumn($query->havings, $column, $replace);
            }

            if ($query->unionOrders !== null) {
                $query->unionOrders = $this->replaceColumn($query->unionOrders, $column, $replace);
            }
        }
    }

    private function replaceColumn(array $columns, string $column, string $replace): array
    {
        return Collection::make($columns)
                         ->transform(
                             function ($item) use ($column, $replace) {
                                 if (isset($item['column']) && $item['column'] === $column) {
                                     $item['column'] = $replace;
                                 }

                                 return $item;
                             }
                         )->values()->all();
    }
}
