<?php

namespace App\Traits;

use App\Models\Setting\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

trait Categories
{
    public function isIncomeCategory(): bool
    {
        $type = $this->type ?? $this->category->type ?? $this->model->type ?? Category::INCOME_TYPE;

        return in_array($type, $this->getIncomeCategoryTypes());
    }

    public function isExpenseCategory(): bool
    {
        $type = $this->type ?? $this->category->type ?? $this->model->type ?? Category::EXPENSE_TYPE;

        return in_array($type, $this->getExpenseCategoryTypes());
    }

    public function isItemCategory(): bool
    {
        $type = $this->type ?? $this->category->type ?? $this->model->type ?? Category::ITEM_TYPE;

        return in_array($type, $this->getItemCategoryTypes());
    }

    public function isOtherCategory(): bool
    {
        $type = $this->type ?? $this->category->type ?? $this->model->type ?? Category::OTHER_TYPE;

        return in_array($type, $this->getOtherCategoryTypes());
    }

    public function getTypeCategoryTypes(string $type, string $return = 'array'): string|array
    {
        switch ($type) {
            case Category::INCOME_TYPE:
                $types = $this->getIncomeCategoryTypes($return);
                break;
            case Category::EXPENSE_TYPE:
                $types = $this->getExpenseCategoryTypes($return);
                break;
            case Category::ITEM_TYPE:
                $types = $this->getItemCategoryTypes($return);
                break;
            case Category::OTHER_TYPE:
                $types = $this->getOtherCategoryTypes($return);
                break;
            default:
                $types = ($return == 'array') ? [$type] : $type;
        }

        return $types;
    }

    public function getIncomeCategoryTypes(string $return = 'array'): string|array
    {
        return $this->getCategoryTypesByIndex(Category::INCOME_TYPE, $return);
    }

    public function getExpenseCategoryTypes(string $return = 'array'): string|array
    {
        return $this->getCategoryTypesByIndex(Category::EXPENSE_TYPE, $return);
    }

    public function getItemCategoryTypes(string $return = 'array'): string|array
    {
        return $this->getCategoryTypesByIndex(Category::ITEM_TYPE, $return);
    }

    public function getOtherCategoryTypes(string $return = 'array'): string|array
    {
        return $this->getCategoryTypesByIndex(Category::OTHER_TYPE, $return);
    }

    public function getCategoryTypesByIndex(string $index, string $return = 'array'): string|array
    {
        $types = (string) setting('category.type.' . $index);

        return ($return == 'array') ? explode(',', $types) : $types;
    }

    public function addIncomeCategoryType(string $new_type): void
    {
        $this->addCategoryType($new_type, Category::INCOME_TYPE);
    }

    public function addExpenseCategoryType(string $new_type): void
    {
        $this->addCategoryType($new_type, Category::EXPENSE_TYPE);
    }

    public function addItemCategoryType(string $new_type): void
    {
        $this->addCategoryType($new_type, Category::ITEM_TYPE);
    }

    public function addOtherCategoryType(string $new_type): void
    {
        $this->addCategoryType($new_type, Category::OTHER_TYPE);
    }

    public function addCategoryType(string $new_type, string $index): void
    {
        $types = !empty(setting('category.type.' . $index)) ? explode(',', setting('category.type.' . $index)) : [];

        if (in_array($new_type, $types)) {
            return;
        }

        $types[] = $new_type;

        setting([
            'category.type.' . $index => implode(',', $types),
        ])->save();
    }

    public function isGroupCategoryType(): bool
    {
        $setting_category_types = setting('category.type');

        foreach ($setting_category_types as $type => $category) {
            $categories = explode(',', $category);

            if (count($categories) > 1) {
                return true;
            }
        }

        return false;
    }

    public function hideCodeCategoryType(string $type, bool $default = true): bool
    {
        return $this->hideCodeCategoryTypes($type)[$type] ?? $default;
    }

    public function hideCodeCategoryTypes(string|array $types): array
    {
        $types = is_string($types) ? explode(',', $types) : $types;

        $type_codes = [];

        foreach ($types as $type) {
            $config_type = config('type.category.' . $type, []);

            $type_codes[$type] = ! empty($config_type['hide']) && in_array('code', $config_type['hide']) ? true : false;
        }

        return $type_codes;
    }

    public function getCategoryTypes(bool $translate = true, bool $group = false, array $types = []): array
    {
        $category_types = [];

        $configs = empty($types) ? config('type.category') : array_intersect_key(config('type.category'), array_flip($types));

        foreach ($configs as $type => $attr) {
            $plural_type = Str::plural($type);

            $name = $attr['translation']['prefix'] . '.' . $plural_type;

            if (!empty($attr['alias'])) {
                $name = $attr['alias'] . '::' . $name;
            }

            if ($group) {
                $group_key = $attr['group'] ?? $type;

                $category_types[$group_key][$type] = $translate ? trans_choice($name, 1) : $name;
            } else {
                $category_types[$type] = $translate ? trans_choice($name, 1) : $name;
            }
        }

        return $category_types;
    }

    public function getCategoryTabs(): array
    {
        $tabs = [];
        $configs = config('type.category');

        foreach ($configs as $type => $attr) {
            $tab_key = 'categories-' . ($attr['group'] ?? $type);

            if (isset($tabs[$tab_key])) {
                $tabs[$tab_key]['key'] .= ',' . $type;
                continue;
            }

            $plural_type = Str::plural($attr['group'] ?? $type);

            $name = $attr['translation']['prefix'] . '.' . $plural_type;

            if (!empty($attr['alias'])) {
                $name = $attr['alias'] . '::' . $name;
            }

            $tabs[$tab_key] = [
                'key' => $type,
                'name' => trans_choice($name, 2),
                'show_code' => $attr['show_code'] ?? false,
            ];
        }

        return $tabs;
    }

    public function getCategoryWithoutChildren(int $id): mixed
    {
        return Category::getWithoutChildren()->find($id);
    }

    public function getTransferCategoryId(): mixed
    {
        // 1 hour set cache for same query
        return Cache::remember('transferCategoryId.' . company_id(), 60, function () {
            return Category::other()->pluck('id')->first();
        });
    }

    public function isTransferCategory(): bool
    {
        $id = $this->id ?? $this->category->id ?? $this->model->id ?? 0;

        return $id == $this->getTransferCategoryId();
    }

    public function getChildrenCategoryIds($category)
    {
        $ids = [];

        foreach ($category->sub_categories as $sub_category) {
            $ids[] = $sub_category->id;

            if ($sub_category->sub_categories) {
                $ids = array_merge($ids, $this->getChildrenCategoryIds($sub_category));
            }
        }

        return $ids;
    }

    /**
     * Finds existing maximum code and increase it
     *
     * @return mixed
     */
    public function getNextCategoryCode()
    {
        return Category::isNotSubCategory()->get(['code'])->reject(function ($category) {
            return !preg_match('/^[0-9]*$/', $category->code);
        })->max('code') + 1;
    }
}
