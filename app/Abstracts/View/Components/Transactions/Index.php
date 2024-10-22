<?php

namespace App\Abstracts\View\Components\Transactions;

use App\Abstracts\View\Component;
use App\Traits\Transactions;
use App\Traits\Modules;
use App\Traits\SearchString;
use App\Traits\ViewComponents;
use Illuminate\Support\Str;

abstract class Index extends Component
{
    use SearchString, Transactions, Modules, ViewComponents;

    public const OBJECT_TYPE = 'transaction';
    public const DEFAULT_TYPE = 'transaction';
    public const DEFAULT_PLURAL_TYPE = 'transactions';

    public $type;

    public $alias;

    public $transactions;

    public $totalTransactions;

    public $permissionCreate;

    public $permissionUpdate;

    public $permissionDelete;

    public $routeTabDocument;

    public $routeParamsTabUnpaid;

    public $routeParamsTabDraft;

    public $routeTabRecurring;

    public $checkPermissionCreate;

    public $hideIncomeCreate;

    public $routeIncomeCreate;

    public $textIncomeCreate;

    public $hideExpenseCreate;

    public $routeExpenseCreate;

    public $textExpenseCreate;

    public $hideImport;

    public $routeImport;

    public $hideExport;

    public $routeExport;

    public $hideEmptyPage;

    public $hideSummary;

    public $summaryItems;

    public $hideSearchString;

    public $hideBulkAction;

    public $bulkActions;

    /** @var string */
    public $searchStringModel;

    /** @var string */
    public $bulkActionClass;

    /** @var array */
    public $bulkActionRouteParameters;

    /** @var string */
    public $searchRoute;

    /** @var string */
    public $classBulkAction;

    /** @var string */
    public $tabActive;

    /** @var string */
    public $tabSuffix;

    public $hidePaymentMethod;

    public $hidePaidAt;

    public $hideNumber;

    public $classPaidAtAndNumber;

    public $textPaidAt;

    public $textNumber;
    
    /** @var bool */
    public $hideStartedAt;

    /** @var bool */
    public $hideEndedAt;

    /** @var string */
    public $classStartedAtAndEndedAt;

    /** @var string */
    public $textStartedAt;

    /** @var string */
    public $textEndedAt;

    public $hideType;

    public $hideCategory;

    public $classTypeAndCategory;

    public $textType;

    public $textCategory;

    /** @var bool */
    public $hideStatus;

    /** @var string */
    public $classStatus;

    /** @var bool */
    public $hideFrequency;

    /** @var string */
    public $classFrequencyAndDuration;

    /** @var bool */
    public $hideDuration;

    public $hideAccount;

    public $classAccount;

    public $textAccount;

    public $hideContact;

    public $hideDocument;

    public $classContactAndDocument;

    public $textContact;

    public $textDocument;

    public $hideAmount;

    public $classAmount;

    public $textAmount;

    public function __construct(
        string $type = '', string $alias = '', $transactions = [], int $totalTransactions = null,
        string $permissionCreate = '', string $permissionUpdate = '', string $permissionDelete = '',
        bool $checkPermissionCreate = true,
        bool $hideIncomeCreate = false,  $routeIncomeCreate = '', string $textIncomeCreate = '',
        bool $hideExpenseCreate = false, $routeExpenseCreate = '', string $textExpenseCreate = '',
        bool $hideImport = false, $routeImport = '',
        bool $hideExport = false, $routeExport = '',
        bool $hideEmptyPage = false,
        bool $hideSummary = false, array $summaryItems = [],
        bool $hideSearchString = false, bool $hideBulkAction = false,
        string $searchStringModel = '', string $bulkActionClass = '', array $bulkActions = [], array $bulkActionRouteParameters = [], string $searchRoute = '', string $classBulkAction = '',
        string $tabActive = '', string $tabSuffix = '',
        bool $hidePaymentMethod = false,
        bool $hidePaidAt = false, bool $hideNumber = false, string $classPaidAtAndNumber = '', string $textPaidAt = '', string $textNumber = '',
        bool $hideStartedAt = false, bool $hideEndedAt = false, string $classStartedAtAndEndedAt = '', string $textStartedAt = '', string $textEndedAt = '',
        bool $hideType = false, bool $hideCategory = false, string $classTypeAndCategory = '', string $textType = '', string $textCategory = '',
        bool $hideStatus = false, string $classStatus = '',
        bool $hideAccount = false, string $classAccount = '', string $textAccount = '',
        bool $hideFrequency = false, bool $hideDuration = false, string $classFrequencyAndDuration = '',
        bool $hideContact = false, bool $hideDocument = false, string $classContactAndDocument = '', string $textContact = '', string $textDocument = '',
        bool $hideAmount = false, string $classAmount = '', string $textAmount = ''
    ) {
        $this->type = $type;
        $this->transactions = $transactions;
        /* -- Main Start -- */
        $this->type = $type;
        $this->alias = $this->getAlias($type, $alias);
        $this->transactions = ($transactions) ? $transactions : collect();
        $this->totalTransactions = $this->getTotalTransactions($totalTransactions);

        $this->permissionCreate = $this->getPermissionCreate($type, $permissionCreate);
        $this->permissionUpdate = $this->getPermissionUpdate($type, $permissionUpdate);
        $this->permissionDelete = $this->getPermissionDelete($type, $permissionDelete);

        /* -- Main End -- */

        /* -- Buttons Start -- */
        $this->checkPermissionCreate = $checkPermissionCreate;

        $this->hideIncomeCreate = $hideIncomeCreate;
        $this->routeIncomeCreate = $this->getRouteIncomeCreate($type, $routeIncomeCreate);
        $this->textIncomeCreate = $this->getTextIncomeCreate($type, $textIncomeCreate);

        $this->hideExpenseCreate = $hideExpenseCreate;
        $this->routeExpenseCreate = $this->getRouteExpenseCreate($type, $routeExpenseCreate);
        $this->textExpenseCreate = $this->getTextExpenseCreate($type, $textExpenseCreate);

        $this->hideImport = $hideImport;
        $this->routeImport = $this->getRouteImport($type, $routeImport);

        $this->hideExport = $hideExport;
        $this->routeExport = $this->getRouteExport($type, $routeExport);
        /* -- Buttons End -- */

        /* -- Content Start -- */

        /* -- Empty Page Start -- */
        $this->hideEmptyPage = $this->getHideEmptyPage($hideEmptyPage);
        /* -- Empty Page End -- */

        /* -- Summary Start -- */
        $this->hideSummary = $hideSummary;
        $this->summaryItems = $this->getSummaryItems($type, $summaryItems);
        /* -- Summary End -- */

        /* Container Start */
        $this->hideSearchString = $hideSearchString;
        $this->hideBulkAction = $hideBulkAction;

        $this->searchStringModel = $this->getSearchStringModel($type, $searchStringModel);

        $this->bulkActionClass = $this->getBulkActionClass($type, $bulkActionClass);
        $this->bulkActionRouteParameters = $this->getBulkActionRouteParameters($type, $bulkActionRouteParameters);

        $this->searchRoute = $this->getIndexRoute($type, $searchRoute);

        $this->classBulkAction = $this->getClassBulkAction($type, $classBulkAction);

        $this->tabSuffix = $this->getTabSuffix($type, $tabSuffix);
        $this->tabActive = $this->getTabActive($type, $tabActive);

        $this->hidePaymentMethod = $hidePaymentMethod;

        /* Document Start */
        $this->hidePaidAt = $hidePaidAt;
        $this->hideNumber = $hideNumber;
        $this->classPaidAtAndNumber = $this->getClassPaidAtAndNumber($type, $classPaidAtAndNumber);
        $this->textPaidAt = $this->getTextPaidAt($type, $textPaidAt);
        $this->textNumber = $this->getTextNumber($type, $textNumber);

        $this->hideStartedAt = $hideStartedAt;
        $this->hideEndedAt = $hideEndedAt;
        $this->classStartedAtAndEndedAt = $this->getClassStartedAndEndedAt($type, $classStartedAtAndEndedAt);
        $this->textStartedAt = $this->getTextStartedAt($type, $textStartedAt);
        $this->textEndedAt = $this->getTextEndedAt($type, $textEndedAt);

        $this->hideType = $hideType;
        $this->hideCategory = $hideCategory;
        $this->classTypeAndCategory = $this->getClassTypeAndCategory($type, $classTypeAndCategory);
        $this->textType = $this->getTextType($type, $textType);
        $this->textCategory = $this->getTextCategory($type, $textCategory);

        $this->hideStatus = $hideStatus;
        $this->classStatus = $this->getClassStatus($type, $classStatus);

        $this->hideFrequency = $hideFrequency;
        $this->hideDuration = $hideDuration;
        $this->classFrequencyAndDuration = $this->getClassFrequencyAndDuration($type, $classFrequencyAndDuration);

        $this->hideAccount = $hideAccount;
        $this->classAccount = $this->getClassAccount($type, $classAccount);
        $this->textAccount = $this->getTextAccount($type, $textAccount);

        $this->hideContact = $hideContact;
        $this->hideDocument = $hideDocument;
        $this->classContactAndDocument = $this->getClassContactAndDocument($type, $classContactAndDocument);
        $this->textContact = $this->getTextContact($type, $textContact);
        $this->textDocument = $this->getTextDocument($type, $textDocument);

        $this->hideAmount = $hideAmount;
        $this->classAmount = $this->getClassAmount($type, $classAmount);
        $this->textAmount = $this->getTextAmount($type, $textAmount);

        /* Document End */

        /* Container End */

        /* -- Content End -- */

        // Set Parent data
        $this->setParentData();
    }

    protected function getTotalTransactions($totalTransactions)
    {
        if (! is_null($totalTransactions)) {
            return $totalTransactions;
        }

        return $this->transactions->count();
    }

    protected function getRouteIncomeCreate ($type, $routeIncomeCreate)
    {
        if (! empty($routeIncomeCreate)) {
            return $routeIncomeCreate;
        }

        $route = $this->getRouteFromConfig($type, 'income_create');

        if (! empty($route)) {
            return $route;
        }

        if (Str::contains($type, 'recurring')) {
            return ['recurring-transactions.create', ['type' => 'income-recurring']];
        }

        return ['transactions.create', ['type' => 'income']];
    }

    protected function getTextIncomeCreate($type, $textIncomeCreate)
    {
        if (! empty($textIncomeCreate)) {
            return $textIncomeCreate;
        }

        $translation = $this->getTextFromConfig($type, 'income_create', 'income_create');

        if (! empty($translation)) {
            return $translation;
        }

        if (Str::contains($type, 'recurring')) {
            return 'general.recurring_incomes';
        }

        return 'general.incomes';
    }

    protected function getRouteExpenseCreate ($type, $routeExpenseCreate)
    {
        if (! empty($routeExpenseCreate)) {
            return $routeExpenseCreate;
        }

        $route = $this->getRouteFromConfig($type, 'expense_create');

        if (! empty($route)) {
            return $route;
        }

        if (Str::contains($type, 'recurring')) {
            return ['recurring-transactions.create', ['type' => 'expense-recurring']];
        }

        return ['transactions.create', ['type' => 'expense']];
    }

    protected function getTextExpenseCreate($type, $textExpenseCreate)
    {
        if (! empty($textExpenseCreate)) {
            return $textExpenseCreate;
        }

        $translation = $this->getTextFromConfig($type, 'expense_create', 'expense_create');

        if (! empty($translation)) {
            return $translation;
        }

        if (Str::contains($type, 'recurring')) {
            return 'general.recurring_expenses';
        }

        return 'general.expenses';
    }

    protected function getRouteImport($type, $routeImport)
    {
        if (! empty($routeImport)) {
            return $routeImport;
        }

        $route = $this->getRouteFromConfig($type, 'import');

        if (! empty($route)) {
            //return $route;
        }

        if (Str::contains($type, 'recurring')) {
            return ['import.create', ['banking', 'recurring-transactions']];
        }

        return ['import.create', ['banking', 'transactions']];
    }

    protected function getRouteExport($type, $routeExport)
    {
        if (! empty($routeExport)) {
            return $routeExport;
        }

        $route = $this->getRouteFromConfig($type, 'export');

        if (! empty($route)) {
            return $route;
        }

        if (Str::contains($type, 'recurring')) {
            return 'recurring-transactions.export';
        }

        return 'transactions.export';
    }

    protected function getHideEmptyPage($hideEmptyPage): bool
    {
        if ($hideEmptyPage) {
            return $hideEmptyPage;
        }

        if ($this->totalTransactions > 0) {
            return true;
        }

        if (request()->has('search') && ($this->totalTransactions > 0)) {
            return true;
        }

        return false;
    }

    public function getSummaryItems($type, $summaryItems)
    {
        if (! empty($summaryItems)) {
            return $summaryItems;
        }

        #todo this lines
        return [];
    }

    public function getTabActive($type, $tabActive)
    {
        if (! empty($tabActive)) {
            return $tabActive;
        }

        $search_type = $type == 'income-recurring' ? 'recurring-transactions' : search_string_value('type');

        return ($this->tabSuffix) ? 'transactions-' . $this->tabSuffix : $search_type;
    }

    public function getTabSuffix($type, $tabSuffix)
    {
        if (! empty($tabSuffix)) {
            return $tabSuffix;
        }

        $search_type = $type == 'income-recurring' ? 'recurring-transactions' : search_string_value('type');

        if ($search_type == 'income') {
            return 'income';
        }

        if ($search_type == 'expense') {
            return 'expense';
        }

        $suffix = $this->getTabActiveFromSetting($type);

        if (! empty($suffix)) {
            return $suffix;
        }

        return 'all';
    }

    protected function getClassPaidAtAndNumber($type, $classPaidAtAndNumber)
    {
        if (! empty($classPaidAtAndNumber)) {
            return $classPaidAtAndNumber;
        }

        $class = $this->getClassFromConfig($type, 'paid_at_and_number');

        if (! empty($class)) {
            return $class;
        }

        return 'w-4/12 sm:w-3/12';
    }

    protected function getTextPaidAt($type, $textPaidAt)
    {
        if (! empty($textPaidAt)) {
            return $textPaidAt;
        }

        $translation = $this->getTextFromConfig($type, 'paid_at', 'paid_date');

        if (! empty($translation)) {
            return $translation;
        }

        return 'general.date';
    }

    protected function getTextNumber($type, $textNumber)
    {
        if (! empty($textNumber)) {
            return $textNumber;
        }

        $translation = $this->getTextFromConfig($type, 'number', 'number');

        if (! empty($translation)) {
            return $translation;
        }

        return 'general.numbers';
    }

    protected function getClassStartedAndEndedAt($type, $classStartedAtAndEndedAt)
    {
        if (! empty($classStartedAtAndEndedAt)) {
            return $classStartedAtAndEndedAt;
        }

        $class = $this->getClassFromConfig($type, 'started_at_and_end_at');

        if (! empty($class)) {
            return $class;
        }

        return '"w-4/12 sm:w-3/12';
    }

    protected function getTextStartedAt($type, $textStartedAt)
    {
        if (! empty($textStartedAt)) {
            return $textStartedAt;
        }

        $translation = $this->getTextFromConfig($type, 'started_at', 'started_date');

        if (! empty($translation)) {
            return $translation;
        }

        return 'general.start_date';
    }

    protected function getTextEndedAt($type, $textEndedAt)
    {
        if (! empty($textEndedAt)) {
            return $textEndedAt;
        }

        $translation = $this->getTextFromConfig($type, 'ended_at', 'ended_date');

        if (! empty($translation)) {
            return $translation;
        }

        return 'recurring.last_issued';
    }

    protected function getClassTypeAndCategory($type, $classTypeAndCategory)
    {
        if (! empty($classTypeAndCategory)) {
            return $classTypeAndCategory;
        }

        $class = $this->getClassFromConfig($type, 'type_and_category');

        if (! empty($class)) {
            return $class;
        }

        return 'w-2/12';
    }

    protected function getTextType($type, $textType)
    {
        if (! empty($textType)) {
            return $textType;
        }

        $translation = $this->getTextFromConfig($type, 'type', 'type');

        if (! empty($translation)) {
            return $translation;
        }

        return 'general.types';
    }

    protected function getTextCategory($type, $textCategory)
    {
        if (! empty($textCategory)) {
            return $textCategory;
        }

        $translation = $this->getTextFromConfig($type, 'category', 'category');

        if (! empty($translation)) {
            return $translation;
        }

        return 'general.categories';
    }

    protected function getClassStatus($type, $classStatus)
    {
        if (! empty($classStatus)) {
            return $classStatus;
        }

        $class = $this->getClassFromConfig($type, 'status');

        if (! empty($class)) {
            return $class;
        }

        return 'w-4/12 sm:w-3/12';
    }

    protected function getClassFrequencyAndDuration($type, $classFrequencyAndDuration)
    {
        if (! empty($classFrequencyAndDuration)) {
            return $classFrequencyAndDuration;
        }

        $class = $this->getClassFromConfig($type, 'frequency_and_duration');

        if (! empty($class)) {
            return $class;
        }

        return 'w-2/12';
    }

    protected function getClassAccount($type, $classAccount)
    {
        if (! empty($classAccount)) {
            return $classAccount;
        }

        $class = $this->getClassFromConfig($type, 'account');

        if (! empty($class)) {
            return $class;
        }

        return 'w-4/12 sm:w-3/12';
    }

    protected function getTextAccount($type, $textAccount)
    {
        if (! empty($textAccount)) {
            return $textAccount;
        }

        $translation = $this->getTextFromConfig($type, 'account', 'account');

        if (! empty($translation)) {
            return $translation;
        }

        return 'general.accounts';
    }

    protected function getClassContactAndDocument($type, $classContactAndDocument)
    {
        if (! empty($classContactAndDocument)) {
            return $classContactAndDocument;
        }

        $class = $this->getClassFromConfig($type, 'contact_and_document');

        if (! empty($class)) {
            return $class;
        }

        return 'w-2/12';
    }

    protected function getTextContact($type, $textContact)
    {
        if (! empty($textContact)) {
            return $textContact;
        }

        $translation = $this->getTextFromConfig($type, 'contact', 'contact');

        if (! empty($translation)) {
            return $translation;
        }

        return 'general.contacts';
    }

    protected function getTextDocument($type, $textDocument)
    {
        if (! empty($textDocument)) {
            return $textDocument;
        }

        $translation = $this->getTextFromConfig($type, 'document', 'document');

        if (! empty($translation)) {
            return $translation;
        }

        return 'general.documents';
    }

    protected function getClassAmount($type, $classAmount)
    {
        if (! empty($classAmount)) {
            return $classAmount;
        }

        $class = $this->getClassFromConfig($type, 'amount');

        if (! empty($class)) {
            return $class;
        }

        return 'w-4/12 sm:w-2/12';
    }

    protected function getTextAmount($type, $textAmount)
    {
        if (! empty($textAmount)) {
            return $textAmount;
        }

        $translation = $this->getTextFromConfig($type, 'amount', 'amount');

        if (! empty($translation)) {
            return $translation;
        }

        return 'general.amount';
    }
}
