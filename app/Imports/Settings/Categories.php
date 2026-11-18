<?php

namespace App\Imports\Settings;

use App\Abstracts\Import;
use App\Http\Requests\Setting\Category as Request;
use App\Models\Setting\Category as Model;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class Categories extends Import implements WithEvents
{
    public $request_class = Request::class;

    public $model = Model::class;

    public $columns = [
        'name',
        'type',
        'code',
        'description',
        'parent_id',
    ];

    /**
     * Rows whose parent category was not there yet when they were read, as a sub-category may come before its parent
     * in the file (files written before the export put parents first, or sorted by hand). They are linked to their
     * parent when the sheet, or the chunk of it being read, ends.
     */
    protected array $rows_waiting_for_parent = [];

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => fn () => $this->linkRowsWaitingForParent(),
        ];
    }

    public function model(array $row)
    {
        if ($this->isWaitingForParent($row)) {
            return $this->waitForParent($row);
        }

        if (self::hasRow($row)) {
            return;
        }

        return new Model($row);
    }

    public function map($row): array
    {
        $row = parent::map($row);

        // Uploaded file may not include a type column; getCategoryType() falls
        // back to the default type when the value is null/unknown.
        $row['type'] = $this->getCategoryType($row['type'] ?? null);
        $row['parent_id'] = $this->getParentId($row) ?? null;

        return $row;
    }

    public function prepareRules(array $rules): array
    {
        $rules['code'] = 'nullable|string';

        return $rules;
    }

    //This function is used in import classes. If the data in the row exists in the database, it is returned.
    public function hasRow($row)
    {
        // Sub-categories too, and their parent: a sub-category matches only the same one under the same parent.
        $has_row = $this->model::withSubCategory()->getWithoutChildren($this->columns)->each(function ($data) {
            $data->setAppends([]);
            $data->unsetRelations();
        });

        $search_value = [];

        //In the model, the fields to be searched for the row are determined.
        foreach ($this->columns as $key) {
            $search_value[$key] = isset($row[$key]) ? $row[$key] : null;
        }

        return in_array($search_value, $has_row->toArray());
    }

    protected function isWaitingForParent(array $row): bool
    {
        return ! empty($row['parent_name']) && empty($row['parent_id']);
    }

    /**
     * Its parent is not there yet, so no category can be this row under that parent: open it, unless an earlier row of
     * the sheet is the same one under the same parent name, and remember it to link it to its parent later.
     */
    protected function waitForParent(array $row): ?Model
    {
        $key = json_encode([$row['name'] ?? null, $row['type'], $row['code'] ?? null, $row['description'] ?? null, $row['parent_name']]);

        if (isset($this->rows_waiting_for_parent[$key])) {
            return null;
        }

        $category = new Model($row);

        $this->rows_waiting_for_parent[$key] = [
            'category' => $category,
            'parent_name' => $row['parent_name'],
        ];

        return $category;
    }

    protected function linkRowsWaitingForParent(): void
    {
        foreach ($this->rows_waiting_for_parent as $waiting) {
            $category = $waiting['category'];

            // Saving the row failed.
            if (! $category->exists) {
                continue;
            }

            $parent_id = $this->model::withSubCategory()
                ->type($category->type)
                ->where('name', $waiting['parent_name'])
                ->whereKeyNot($category->id)
                ->value('id');

            if (empty($parent_id)) {
                continue;
            }

            $category->parent_id = $parent_id;

            // Linking it is part of opening it, not a later change.
            Model::withoutTimestamps(fn () => $category->save());
        }

        $this->rows_waiting_for_parent = [];
    }
}
