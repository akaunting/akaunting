<?php

namespace App\Imports\Banking;

use App\Abstracts\Import;
use App\Http\Requests\Banking\Transaction as Request;
use App\Models\Banking\Transaction as Model;
use App\Models\Setting\Category;
use App\Traits\Categories;
use App\Traits\Transactions as TraitsTransactions;
use Illuminate\Support\Str;

class Transactions extends Import
{
    use Categories, TraitsTransactions;

    public $model = Model::class;

    public $request_class = Request::class;

    public $columns = [
        'number',
    ];

    public function model(array $row)
    {
        if (self::hasRow($row)) {
            return;
        }

        return new Model($row);
    }

    public function map($row): array
    {
        $row = parent::map($row);

        if (!isset($row['type'])) {
            return [];
        }

        $real_type = $this->getRealTypeTransaction($row['type']);
        $contact_type = config('type.transaction.' . $real_type . '.contact_type', $real_type == 'income' ? 'customer' : 'vendor');

        $row['currency_code'] = $this->getCurrencyCode($row);
        $row['account_id'] = $this->getAccountId($row);
        $row['category_id'] = Str::endsWith($row['type'], '-transfer')
            ? $this->getTransferCategoryIdOfRow($row)
            : $this->getCategoryId($row, $real_type);
        $row['contact_id'] = $this->getContactId($row, $contact_type);
        $row['document_id'] = $this->getDocumentId($row);
        $row['parent_id'] = $this->getParentId($row) ?? 0;
        $row['payment_method'] = $this->getPaymentMethod($row);

        return $row;
    }

    /**
     * A transfer transaction's category is not an income or expense one: core's transfer category ("Transfer",
     * other), or one an App gives it (Double-Entry: the bank/cash account of the bank account). Looked up among the
     * income or expense categories, as the transaction's real type, none was found, and one of that name was opened
     * for every such category, used by nothing.
     *
     * The category of the row's name among the types that are not income, expense or item, the other type first;
     * without one, the transfer category, as a transfer gives its transactions.
     */
    protected function getTransferCategoryIdOfRow(array $row): ?int
    {
        if (! empty($row['category_id'])) {
            return (int) $row['category_id'];
        }

        $name = is_scalar($row['category_name'] ?? null) ? trim((string) $row['category_name']) : '';

        $id = null;

        if ($name !== '') {
            $excluded = array_merge(
                [Category::INCOME_TYPE, Category::EXPENSE_TYPE, Category::DIRECT_COST_TYPE, Category::ITEM_TYPE],
                $this->getIncomeCategoryTypes(),
                $this->getExpenseAndDirectCostCategoryTypes(),
                $this->getItemCategoryTypes()
            );

            $id = Category::withSubCategory()
                ->where('name', $name)
                ->whereNotIn('type', array_values(array_unique(array_filter($excluded))))
                ->orderByRaw('type = ? desc', [Category::OTHER_TYPE])
                ->orderBy('id')
                ->value('id');
        }

        $id = $id ?? Category::withSubCategory()->other()->orderBy('id')->value('id');

        return $id ? (int) $id : null;
    }

    public function prepareRules($rules): array
    {
        $rules['number'] = 'required|string';
        $rules['type'] = 'required|string';
        //$rules['currency_rate'] = 'required|gt:0';

        return $rules;
    }
}
