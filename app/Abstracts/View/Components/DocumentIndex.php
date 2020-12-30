<?php

namespace App\Abstracts\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Str;
use App\Events\Common\BulkActionsAdding;

abstract class DocumentIndex extends Component
{
    /** @var string */
    public $type;

    public $documents;

    /** @var string */
    public $page;

    /** @var string */
    public $docsPath;

    /** @var bool */
    public $checkCreatePermission;

    /** @var string */
    public $createPermission;

    /** @var string */
    public $createRoute;

    /** @var string */
    public $importRoute;

    /** @var array */
    public $importRouteParameters;

    /** @var string */
    public $exportRoute;

    /** @var bool */
    public $hideCreate;

    /** @var bool */
    public $hideImport;

    /** @var bool */
    public $hideExport;

    /* -- Card Header Start -- */
    /** @var string */
    public $textBulkAction;

    /** @var string */
    public $bulkActionClass;

    /** @var array */
    public $bulkActions;

    /** @var array */
    public $bulkActionRouteParameters;

    /** @var string */
    public $formCardHeaderRoute;

    /** @var bool */
    public $hideBulkAction;

    /** @var string */
    public $classBulkAction;

    /** @var string */
    public $searchStringModel;

    /** @var bool */
    public $hideSearchString;
    /* -- Card Header End -- */

    /* -- Card Body Start -- */
    /** @var string */
    public $textDocumentNumber;

    /** @var string */
    public $textContactName;

    /** @var string */
    public $textIssueAt;

    /** @var string */
    public $textDueAt;

    /** @var string */
    public $textDocumentStatus;

    /** @var bool */
    public $checkButtonReconciled;

    /** @var bool */
    public $checkButtonCancelled;

    /** @var bool */
    public $hideDocumentNumber;

    /** @var string */
    public $classDocumentNumber;

    /** @var bool */
    public $hideContactName;

    /** @var string */
    public $classContactName;

    /** @var bool */
    public $hideAmount;

    /** @var string */
    public $classAmount;

    /** @var bool */
    public $hideIssuedAt;

    /** @var string */
    public $classIssuedAt;

    /** @var bool */
    public $hideDueAt;

    /** @var string */
    public $classDueAt;

    /** @var bool */
    public $hideStatus;

    /** @var string */
    public $classStatus;

    /** @var bool */
    public $hideActions;

    /** @var string */
    public $routeButtonShow;

    /** @var string */
    public $routeButtonEdit;

    /** @var string */
    public $routeButtonDuplicate;

    /** @var string */
    public $routeButtonCancelled;

    /** @var string */
    public $routeButtonDelete;

    /** @var bool */
    public $hideButtonShow;

    /** @var bool */
    public $hideButtonEdit;

    /** @var bool */
    public $hideButtonDuplicate;

    /** @var bool */
    public $hideButtonCancel;

    /** @var bool */
    public $hideButtonDelete;

    /** @var string */
    public $permissionDocumentCreate;

    /** @var string */
    public $permissionDocumentUpdate;

    /** @var string */
    public $permissionDocumentDelete;
    /* -- Card Body End -- */

    public $limits;

    public $hideEmptyPage;

    public $classActions;

    public $class_count;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $type, $documents = [], string $page = '', string $docsPath = '', $limits = [], $hideEmptyPage = false,
        bool $checkCreatePermission = true, string $createPermission = '', string $createRoute = '', string $importRoute = '', array $importRouteParameters = [], string $exportRoute = '',
        bool $hideCreate = false, bool $hideImport = false, bool $hideExport = false, // Advanced
        string $textBulkAction = '', array $bulkActions = [], string $bulkActionClass = '', array $bulkActionRouteParameters = [], string $formCardHeaderRoute = '', string $searchStringModel = '',
        bool $hideBulkAction = false, bool $hideSearchString = false,
        string $classActions = '', string $classBulkAction = '', string $classDocumentNumber = '', string $classContactName = '', string $classIssuedAt = '', string $classDueAt = '', string $classStatus = '',
        string $textDocumentNumber = '', string $textContactName = '', string $classAmount = '', string $textIssueAt = '', string $textDueAt = '', string $textDocumentStatus = '',
        bool $checkButtonReconciled = true, bool $checkButtonCancelled = true,
        string $routeButtonShow = '', string $routeButtonEdit = '', string $routeButtonDuplicate = '', string $routeButtonCancelled = '', string $routeButtonDelete = '',
        bool $hideDocumentNumber = false, bool $hideContactName = false, bool $hideAmount = false, bool $hideIssuedAt = false, bool $hideDueAt = false, bool $hideStatus = false, bool $hideActions = false,
        bool $hideButtonShow = false, bool $hideButtonEdit = false, bool $hideButtonDuplicate = false, bool $hideButtonCancel = false, bool $hideButtonDelete = false,
        string $permissionDocumentCreate = '', string $permissionDocumentUpdate = '', string $permissionDocumentDelete = ''
    ) {
        $this->type = $type;
        $this->documents = $documents;
        $this->page = $this->getPage($type, $page);
        $this->docsPath = $this->getDocsPath($type, $docsPath);
        $this->hideEmptyPage = $hideEmptyPage;

        /* -- Top Buttons Start -- */
        $this->checkCreatePermission = $checkCreatePermission;
        $this->createPermission = $this->getCreatePermission($type, $createPermission);

        $this->createRoute = $this->getCreateRoute($type, $createRoute);
        $this->importRoute = $this->getImportRoute($importRoute);
        $this->importRouteParameters = $this->getImportRouteParameters($type, $importRouteParameters);
        $this->exportRoute = $this->getExportRoute($type, $exportRoute);

        $this->hideCreate = $hideCreate;
        $this->hideImport = $hideImport;
        $this->hideExport = $hideExport;
        /* -- Top Buttons End -- */

        /* -- Card Header Start -- */
        $this->textBulkAction = $this->getTextBulkAction($type, $textBulkAction);
        $this->bulkActionClass = $bulkActionClass;
        $this->bulkActions = $this->getBulkActions($type, $bulkActions, $bulkActionClass);

        $this->bulkActionRouteParameters = $this->getBulkActionRouteParameters($type, $bulkActionRouteParameters);

        $this->formCardHeaderRoute = $this->getRoute($type, $formCardHeaderRoute);

        $this->searchStringModel = $this->getSearchStringModel($type, $searchStringModel);

        $this->hideBulkAction = $hideBulkAction;
        $this->hideSearchString = $hideSearchString;
        /* -- Card Header End -- */

        /* -- Card Body Start -- */
        $this->textDocumentNumber = $this->getTextDocumentNumber($textDocumentNumber);
        $this->textContactName = $this->getTextContactName($type, $textContactName);
        $this->textIssueAt = $this->getTextIssueAt($type, $textIssueAt);
        $this->textDueAt = $this->getTextDueAt($type, $textDueAt);
        $this->textDocumentStatus = $this->getTextDocumentStatus($type, $textDocumentStatus);

        $this->checkButtonReconciled = $checkButtonReconciled;
        $this->checkButtonCancelled = $checkButtonCancelled;

        $this->routeButtonShow = $this->getRouteButtonShow($type, $routeButtonShow);
        $this->routeButtonEdit = $this->getRouteButtonEdit($type, $routeButtonEdit);
        $this->routeButtonDuplicate = $this->getRouteButtonDuplicate($type, $routeButtonDuplicate);
        $this->routeButtonCancelled = $this->getRouteButtonCancelled($type, $routeButtonCancelled);
        $this->routeButtonDelete = $this->getRouteButtonDelete($type, $routeButtonDelete);

        $this->hideBulkAction = $hideBulkAction;
        $this->hideDocumentNumber = $hideDocumentNumber;
        $this->hideContactName = $hideContactName;
        $this->hideAmount = $hideAmount;
        $this->hideIssuedAt = $hideIssuedAt;
        $this->hideDueAt = $hideDueAt;
        $this->hideStatus = $hideStatus;
        $this->hideActions = $hideActions;

        $this->class_count = 12;

        $this->calculateClass();

        $this->classBulkAction = $this->getClassBulkAction($type, $classBulkAction);
        $this->classDocumentNumber = $this->getClassDocumentNumber($type, $classDocumentNumber);
        $this->classContactName = $this->getClassContactName($type, $classContactName);
        $this->classAmount = $this->getClassAmount($type, $classAmount);
        $this->classIssuedAt = $this->getclassIssuedAt($type, $classIssuedAt);
        $this->classDueAt = $this->getClassDueAt($type, $classDueAt);
        $this->classStatus = $this->getClassStatus($type, $classStatus);
        $this->classActions = $this->getClassActions($type, $classActions);

        $this->hideButtonShow = $hideButtonShow;
        $this->hideButtonEdit = $hideButtonEdit;
        $this->hideButtonDuplicate = $hideButtonDuplicate;
        $this->hideButtonCancel = $hideButtonCancel;
        $this->hideButtonDelete = $hideButtonDelete;

        $this->permissionDocumentCreate = $this->getPermissionDocumentCreate($type, $permissionDocumentCreate);
        $this->permissionDocumentUpdate = $this->getPermissionDocumentUpdate($type, $permissionDocumentUpdate);
        $this->permissionDocumentDelete = $this->getPermissionDocumentDelete($type, $permissionDocumentDelete);
        /* -- Card Body End -- */

        $this->limits = ($limits) ? $limits : ['10' => '10', '25' => '25', '50' => '50', '100' => '100'];
    }

    protected function getPage($type, $page)
    {
        if (!empty($page)) {
            return $page;
        }

        return Str::plural($type, 2);
    }

    protected function getDocsPath($type, $docsPath)
    {
        if (!empty($docsPath)) {
            return $docsPath;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $docsPath = 'sales/invoices';
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $docsPath = 'purchases/bills';
                break;
        }

        return $docsPath;
    }

    protected function getCreatePermission($type, $createPermission)
    {
        if (!empty($createPermission)) {
            return $createPermission;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $createPermission = 'create-sales-invoices';
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $createPermission = 'create-purchases-bills';
                break;
        }

        return $createPermission;
    }

    protected function getCreateRoute($type, $createRoute)
    {
        if (!empty($createRoute)) {
            return $createRoute;
        }

        $page = Str::plural($type, 2);

        $route = $page . '.create';

        try {
            route($route);
        } catch (\Exception $e) {
            $route = '';
        }

        return $route;
    }

    protected function getImportRoute($importRoute)
    {
        if (!empty($importRoute)) {
            return $importRoute;
        }

        $route = 'import.create';

        return $route;
    }

    protected function getImportRouteParameters($type, $importRouteParameters)
    {
        if (!empty($importRouteParameters)) {
            return $importRouteParameters;
        }

        $importRouteParameters = [
            'group' => ($type == 'invoice') ? 'sales' : 'purchases',
            'type' => Str::plural($type, 2)
        ];

        return $importRouteParameters;
    }

    protected function getExportRoute($type, $exportRoute)
    {
        if (!empty($exportRoute)) {
            return $exportRoute;
        }

        $page = Str::plural($type, 2);

        $route = $page . '.export';

        try {
            route($route);
        } catch (\Exception $e) {
            $route = '';
        }

        return $route;
    }

    protected function getRoute($type, $formCardHeaderRoute)
    {
        if (!empty($formCardHeaderRoute)) {
            return $formCardHeaderRoute;
        }

        $page = Str::plural($type, 2);

        $route = $page . '.index';

        try {
            route($route);
        } catch (\Exception $e) {
            $route = '';
        }

        return $route;
    }

    protected function getSearchStringModel($type, $searchStringModel)
    {
        if (!empty($searchStringModel)) {
            return $searchStringModel;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $searchStringModel = 'App\Models\Sale\Invoice';
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $searchStringModel = 'App\Models\Purchase\Bill';
                break;
        }

        return $searchStringModel;
    }

    protected function getTextBulkAction($type, $textBulkAction)
    {
        if (!empty($textBulkAction)) {
            return $textBulkAction;
        }

        $textBulkAction = 'general.' . Str::plural($type, 2);

        return $textBulkAction;
    }

    protected function getBulkActions($type, $bulkActions, $bulkActionClass)
    {
        if (!empty($bulkActions)) {
            return $bulkActions;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $bulkActionClass = 'App\BulkActions\Sales\Invoices';
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $bulkActionClass = 'App\BulkActions\Purchases\Bills';
                break;
        }

        if (class_exists($bulkActionClass)) {
            event(new BulkActionsAdding(app($bulkActionClass)));

            $bulkActions = app($bulkActionClass)->actions;
        } else {
            $b = new \stdClass();
            $b->actions = [];

            event(new BulkActionsAdding($b));

            $bulkActions = $b->actions;
        }

        return $bulkActions;
    }

    protected function getBulkActionRouteParameters($type, $bulkActionRouteParameters)
    {
        if (!empty($bulkActionRouteParameters)) {
            return $bulkActionRouteParameters;
        }

        $bulkActionRouteParameters = [
            'group' => ($type == 'invoice') ? 'sales' : 'purchases',
            'type' => Str::plural($type, 2)
        ];

        return $bulkActionRouteParameters;
    }

    protected function getClassBulkAction($type, $classBulkAction)
    {
        if (!empty($classBulkAction)) {
            return $classBulkAction;
        }

        return 'col-sm-2 col-md-1 col-lg-1 col-xl-1 d-none d-sm-block';
    }

    protected function getTextDocumentNumber($textDocumentNumber)
    {
        if (!empty($textDocumentNumber)) {
            return $textDocumentNumber;
        }

        return 'general.numbers';
    }

    protected function getClassDocumentNumber($type, $classDocumentNumber)
    {
        if (!empty($classDocumentNumber)) {
            return $classDocumentNumber;
        }

        if ($classDocumentNumber = $this->getClass('classDocumentNumber')) {
            return $classDocumentNumber;
        }

        return 'col-md-2 col-lg-1 col-xl-1 d-none d-md-block';
    }

    protected function getTextContactName($type, $textContactName)
    {
        if (!empty($textContactName)) {
            return $textContactName;
        }

        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $textContactName = 'general.vendors';
                break;
            default:
                $textContactName = 'general.customers';
                break;
        }

        return $textContactName;
    }

    protected function getClassContactName($type, $classContactName)
    {
        if (!empty($classContactName)) {
            return $classContactName;
        }

        if ($classContactName = $this->getClass('classContactName')) {
            return $classContactName;
        }

        return 'col-xs-4 col-sm-4 col-md-4 col-lg-2 col-xl-2 text-left';
    }

    protected function getClassAmount($type, $classAmount)
    {
        if (!empty($classAmount)) {
            return $classAmount;
        }

        if ($classAmount = $this->getClass('classAmount')) {
            return $classAmount;
        }

        return 'col-xs-4 col-sm-4 col-md-3 col-lg-2 col-xl-2 text-right';
    }

    protected function getTextIssueAt($type, $textIssueAt)
    {
        if (!empty($textIssueAt)) {
            return $textIssueAt;
        }

        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $textIssueAt = 'bills.bill_date';
                break;
            default:
                $textIssueAt = 'invoices.invoice_date';
                break;
        }

        return $textIssueAt;
    }

    protected function getclassIssuedAt($type, $classIssuedAt)
    {
        if (!empty($classIssuedAt)) {
            return $classIssuedAt;
        }

        if ($classIssuedAt = $this->getClass('classIssuedAt')) {
            return $classIssuedAt;
        }

        return 'col-lg-2 col-xl-2 d-none d-lg-block text-left';
    }

    protected function getTextDueAt($type, $textDueAt)
    {
        if (!empty($textDueAt)) {
            return $textDueAt;
        }

        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $textDueAt = 'bills.due_date';
                break;
            default:
                $textDueAt = 'invoices.due_date';
                break;
        }

        return $textDueAt;
    }

    protected function getClassDueAt($type, $classDueAt)
    {
        if (!empty($classDueAt)) {
            return $classDueAt;
        }

        if ($classDueAt = $this->getClass('classDueAt')) {
            return $classDueAt;
        }

        return 'col-lg-2 col-xl-2 d-none d-lg-block text-left';
    }

    protected function getTextDocumentStatus($type, $textDocumentStatus)
    {
        if (!empty($textDocumentStatus)) {
            return $textDocumentStatus;
        }

        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $textDocumentStatus = 'bills.statuses.';
                break;
            default:
                $textDocumentStatus = 'invoices.statuses.';
                break;
        }

        return $textDocumentStatus;
    }

    protected function getClassStatus($type, $classStatus)
    {
        if (!empty($classStatus)) {
            return $classStatus;
        }

        if ($classStatus = $this->getClass('classStatus')) {
            return $classStatus;
        }

        return 'col-lg-1 col-xl-1 d-none d-lg-block text-center';
    }

    protected function getClassActions($type, $classActions)
    {
        if (!empty($classActions)) {
            return $classActions;
        }

        if ($classActions = $this->getClass('classActions')) {
            return $classActions;
        }

        return 'col-xs-4 col-sm-2 col-md-2 col-lg-1 col-xl-1 text-center';
    }

    protected function getRouteButtonShow($type, $routeButtonShow)
    {
        if (!empty($routeButtonShow)) {
            return $routeButtonShow;
        }

        $page = Str::plural($type, 2);

        $route = $page . '.show';

        try {
            //example route parameter.
            $parameter = 1;

            route($route, $parameter);
        } catch (\Exception $e) {
            $route = '';
        }

        return $route;
    }

    protected function getRouteButtonEdit($type, $routeButtonEdit)
    {
        if (!empty($routeButtonEdit)) {
            return $routeButtonEdit;
        }

        $page = Str::plural($type, 2);

        $route = $page . '.edit';

        try {
            //example route parameter.
            $parameter = 1;

            route($route, $parameter);
        } catch (\Exception $e) {
            $route = '';
        }

        return $route;
    }

    protected function getRouteButtonDuplicate($type, $routeButtonDuplicate)
    {
        if (!empty($routeButtonDuplicate)) {
            return $routeButtonDuplicate;
        }

        $page = Str::plural($type, 2);

        $route = $page . '.duplicate';

        try {
            //example route parameter.
            $parameter = 1;

            route($route, $parameter);
        } catch (\Exception $e) {
            $route = '';
        }

        return $route;
    }

    protected function getRouteButtonCancelled($type, $routeButtonCancelled)
    {
        if (!empty($routeButtonCancelled)) {
            return $routeButtonCancelled;
        }

        $page = Str::plural($type, 2);

        $route = $page . '.cancelled';

        try {
            //example route parameter.
            $parameter = 1;

            route($route, $parameter);
        } catch (\Exception $e) {
            $route = '';
        }

        return $route;
    }

    protected function getRouteButtonDelete($type, $routeButtonDelete)
    {
        if (!empty($routeButtonDelete)) {
            return $routeButtonDelete;
        }

        $page = Str::plural($type, 2);

        $route = $page . '.destroy';

        try {
            //example route parameter.
            $parameter = 1;

            route($route, $parameter);
        } catch (\Exception $e) {
            $route = '';
        }

        return $route;
    }

    protected function getPermissionDocumentCreate($type, $permissionDocumentCreate)
    {
        if (!empty($permissionDocumentCreate)) {
            return $permissionDocumentCreate;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $permissionDocumentCreate = 'create-sales-invoices';
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $permissionDocumentCreate = 'create-purchases-bills';
                break;
        }

        return $permissionDocumentCreate;
    }

    protected function getPermissionDocumentUpdate($type, $permissionDocumentUpdate)
    {
        if (!empty($permissionDocumentUpdate)) {
            return $permissionDocumentUpdate;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $permissionDocumentUpdate = 'update-sales-invoices';
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $permissionDocumentUpdate = 'update-purchases-bills';
                break;
        }

        return $permissionDocumentUpdate;
    }

    protected function getPermissionDocumentDelete($type, $permissionDocumentDelete)
    {
        if (!empty($permissionDocumentDelete)) {
            return $permissionDocumentDelete;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $permissionDocumentDelete = 'delete-sales-invoices';
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $permissionDocumentDelete = 'delete-purchases-bills';
                break;
        }

        return $permissionDocumentDelete;
    }

    protected function calculateClass()
    {
        $hides = [
            'BulkAction' => '1',
            'DocumentNumber' => '1',
            'ContactName' => '2',
            'Amount' => '2',
            'IssuedAt' => '2',
            'DueAt' => '2',
            'Status' => '1',
            'Actions' => '1',
        ];

        foreach ($hides as $hide => $count) {
            if ($this->{'hide'. $hide}) {
                $this->class_count -= $count;
            }
        }
    }

    protected function getClass($type)
    {
        $hide_count = 12 - $this->class_count;

        if (empty($hide_count)) {
            //return false;
        }

        $class = false;

        switch($type) {
            case 'classDocumentNumber':
                switch ($hide_count) {
                    case 1:
                        $class = 'col-md-3 col-lg-2 col-xl-2 d-none d-md-block';
                        $this->class_count++;
                        break;
                    case 2:
                        $class = 'col-md-4 col-lg-2 col-xl-2 d-none d-md-block';
                        $this->class_count += 2;
                        break;
                    case 3:
                        $class = 'col-md-5 col-lg-2 col-xl-2 d-none d-md-block';
                        $this->class_count += 3;
                        break;
                }
        }

        return $class;
    }
}
