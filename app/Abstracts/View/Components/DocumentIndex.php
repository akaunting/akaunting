<?php

namespace App\Abstracts\View\Components;

use Akaunting\Module\Module;
use App\Abstracts\View\Components\Document as Base;
use App\Events\Common\BulkActionsAdding;
use Illuminate\Support\Str;

abstract class DocumentIndex extends Base
{
    /** @var string */
    public $type;

    public $documents;

    /** @var string */
    public $page;

    /** @var string */
    public $docsPath;

    /** @var bool */
    public $checkPermissionCreate;

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
    public $textIssuedAt;

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
    public $permissionCreate;

    /** @var string */
    public $permissionUpdate;

    /** @var string */
    public $permissionDelete;
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
        bool $checkPermissionCreate = true, string $createRoute = '', string $importRoute = '', array $importRouteParameters = [], string $exportRoute = '',
        bool $hideCreate = false, bool $hideImport = false, bool $hideExport = false, // Advanced
        string $textBulkAction = '', array $bulkActions = [], string $bulkActionClass = '', array $bulkActionRouteParameters = [], string $formCardHeaderRoute = '', string $searchStringModel = '',
        bool $hideBulkAction = false, bool $hideSearchString = false,
        string $classActions = '', string $classBulkAction = '', string $classDocumentNumber = '', string $classContactName = '', string $classIssuedAt = '', string $classDueAt = '', string $classStatus = '',
        string $textDocumentNumber = '', string $textContactName = '', string $classAmount = '', string $textIssuedAt = '', string $textDueAt = '', string $textDocumentStatus = '',
        bool $checkButtonReconciled = true, bool $checkButtonCancelled = true,
        string $routeButtonShow = '', string $routeButtonEdit = '', string $routeButtonDuplicate = '', string $routeButtonCancelled = '', string $routeButtonDelete = '',
        bool $hideDocumentNumber = false, bool $hideContactName = false, bool $hideAmount = false, bool $hideIssuedAt = false, bool $hideDueAt = false, bool $hideStatus = false, bool $hideActions = false,
        bool $hideButtonShow = false, bool $hideButtonEdit = false, bool $hideButtonDuplicate = false, bool $hideButtonCancel = false, bool $hideButtonDelete = false,
        string $permissionCreate = '', string $permissionUpdate = '', string $permissionDelete = ''
    ) {
        $this->type = $type;
        $this->documents = $documents;
        $this->page = $this->getPage($type, $page);
        $this->docsPath = $this->getDocsPath($type, $docsPath);
        $this->hideEmptyPage = $hideEmptyPage;

        /* -- Top Buttons Start -- */
        $this->checkPermissionCreate = $checkPermissionCreate;

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
        $this->textDocumentNumber = $this->getTextDocumentNumber($type, $textDocumentNumber);
        $this->textContactName = $this->getTextContactName($type, $textContactName);
        $this->textIssuedAt = $this->getTextIssuedAt($type, $textIssuedAt);
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
        $this->classIssuedAt = $this->getClassIssuedAt($type, $classIssuedAt);
        $this->classDueAt = $this->getClassDueAt($type, $classDueAt);
        $this->classStatus = $this->getClassStatus($type, $classStatus);
        $this->classActions = $this->getClassActions($type, $classActions);

        $this->hideButtonShow = $hideButtonShow;
        $this->hideButtonEdit = $hideButtonEdit;
        $this->hideButtonDuplicate = $hideButtonDuplicate;
        $this->hideButtonCancel = $hideButtonCancel;
        $this->hideButtonDelete = $hideButtonDelete;

        $this->permissionCreate = $this->getPermissionCreate($type, $permissionCreate);
        $this->permissionUpdate = $this->getPermissionUpdate($type, $permissionUpdate);
        $this->permissionDelete = $this->getPermissionDelete($type, $permissionDelete);
        /* -- Card Body End -- */

        $this->limits = ($limits) ? $limits : ['10' => '10', '25' => '25', '50' => '50', '100' => '100'];
    }

    protected function getPage($type, $page)
    {
        if (!empty($page)) {
            return $page;
        }

        return config('type.' . $type . '.route.prefix', 'invoices');
    }

    protected function getDocsPath($type, $docsPath)
    {
        if (!empty($docsPath)) {
            return $docsPath;
        }

        $docs_path = config('type.' . $type . '.docs_path');

        if (!empty($docs_path)) {
            return $docs_path;
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

    protected function getCreateRoute($type, $createRoute)
    {
        if (!empty($createRoute)) {
            return $createRoute;
        }

        $route = $this->getRouteFromConfig($type, 'create');

        if (!empty($route)) {
            return $route;
        }

        return 'invoices.create';
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

        $route = $this->getRouteFromConfig($type, 'import');

        $importRouteParameters = [
            'group' => config('type.' . $type . '.group'),
            'type' => config('type.' . $type . '.route.prefix'),
            'route' => ($route) ? $route : 'invoices.import',
        ];

        return $importRouteParameters;
    }

    protected function getExportRoute($type, $exportRoute)
    {
        if (!empty($exportRoute)) {
            return $exportRoute;
        }

        $route = $this->getRouteFromConfig($type, 'export');

        if (!empty($route)) {
            return $route;
        }

        return 'invoices.export';
    }

    protected function getRoute($type, $formCardHeaderRoute)
    {
        if (!empty($formCardHeaderRoute)) {
            return $formCardHeaderRoute;
        }

        $route = $this->getRouteFromConfig($type, 'index');

        if (!empty($route)) {
            return $route;
        }

        return 'invoices.index';
    }

    protected function getSearchStringModel($type, $searchStringModel)
    {
        if (!empty($searchStringModel)) {
            return $searchStringModel;
        }
        
        $search_string_model = config('type.' . $type . '.search_string_model');

        if (!empty($search_string_model)) {
            return $search_string_model;
        }

        if ($group = config('type.' . $type . '.group')) {
            $group = Str::studly(Str::singular($group)) . '\\';
        }

        $prefix = Str::studly(Str::singular(config('type.' . $type . '.route.prefix')));

        if ($alias = config('type.' . $type . '.alias')) {
            $searchStringModel = 'Modules\\' . Str::studly($alias) .'\Models\\' . $group . $prefix;
        } else {
            $searchStringModel = 'App\Models\\' . $group . $prefix;
        }

        return $searchStringModel;
    }

    protected function getTextBulkAction($type, $textBulkAction)
    {
        if (!empty($textBulkAction)) {
            return $textBulkAction;
        }
        
        $default_key = config('type.' . $type . '.translation.prefix');

        $translation = $this->getTextFromConfig($type, 'bulk_action', $default_key, 'trans_choice');

        if (!empty($translation)) {
            return $translation;
        }

        return 'general.invoices';
    }

    protected function getBulkActions($type, $bulkActions, $bulkActionClass)
    {
        if (!empty($bulkActions)) {
            return $bulkActions;
        }

        $bulk_actions = config('type.' . $type . '.bulk_actions');

        if (!empty($bulk_actions)) {
            return $bulk_actions;
        }

        $file_name = '';

        if ($group = config('type.' . $type . '.group')) {
            $file_name .= Str::studly($group) . '\\';
        }

        if ($prefix = config('type.' . $type . '.route.prefix')) {
            $file_name .= Str::studly($prefix);
        }

        if ($alias = config('type.' . $type . '.alias')) {
            $module = module($alias);

            if (!$module instanceof Module) {
                $b = new \stdClass();
                $b->actions = [];

                event(new BulkActionsAdding($b));

                return $b->actions;
            }

            $bulkActionClass = 'Modules\\' . $module->getStudlyName() . '\BulkActions\\' . $file_name;
        } else {
            $bulkActionClass = 'App\BulkActions\\' .  $file_name;
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

        $group = config('type.' . $type . '.group');

        if (!empty(config('type.' . $type . '.alias'))) {
            $group = config('type.' . $type . '.alias');
        }

        $bulkActionRouteParameters = [
            'group' => $group,
            'type' => config('type.' . $type . '.route.prefix')
        ];

        return $bulkActionRouteParameters;
    }

    protected function getClassBulkAction($type, $classBulkAction)
    {
        if (!empty($classBulkAction)) {
            return $classBulkAction;
        }

        $class = $this->getTextFromConfig($type, 'bulk_action');

        if (!empty($class)) {
            return $class;
        }

        return 'col-sm-2 col-md-1 col-lg-1 col-xl-1 d-none d-sm-block';
    }

    protected function getTextDocumentNumber($type, $textDocumentNumber)
    {
        if (!empty($textDocumentNumber)) {
            return $textDocumentNumber;
        }

        $translation = $this->getTextFromConfig($type, 'document_number', 'numbers');

        if (!empty($translation)) {
            return $translation;
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

        $class = $this->getTextFromConfig($type, 'document_number');

        if (!empty($class)) {
            return $class;
        }

        return 'col-md-2 col-lg-1 col-xl-1 d-none d-md-block';
    }

    protected function getTextContactName($type, $textContactName)
    {
        if (!empty($textContactName)) {
            return $textContactName;
        }

        $default_key = Str::plural(config('type.' . $type . '.contact_type'), 2);

        $translation = $this->getTextFromConfig($type, 'contact_name', $default_key, 'trans_choice');

        if (!empty($translation)) {
            return $translation;
        }

        return 'general.customers';
    }

    protected function getClassContactName($type, $classContactName)
    {
        if (!empty($classContactName)) {
            return $classContactName;
        }

        if ($classContactName = $this->getClass('classContactName')) {
            return $classContactName;
        }

        $class = $this->getTextFromConfig($type, 'contact_name');

        if (!empty($class)) {
            return $class;
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

        $class = $this->getTextFromConfig($type, 'amount');

        if (!empty($class)) {
            return $class;
        }

        return 'col-xs-4 col-sm-4 col-md-3 col-lg-2 col-xl-2 text-right';
    }

    protected function getTextIssuedAt($type, $textIssuedAt)
    {
        if (!empty($textIssuedAt)) {
            return $textIssuedAt;
        }

        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $default_key = 'bill_date';
                break;
            default:
                $default_key = 'invoice_date';
                break;
        }

        $translation = $this->getTextFromConfig($type, 'issued_at', $default_key);

        if (!empty($translation)) {
            return $translation;
        }

        return 'invoices.invoice_date';
    }

    protected function getClassIssuedAt($type, $classIssuedAt)
    {
        if (!empty($classIssuedAt)) {
            return $classIssuedAt;
        }

        if ($classIssuedAt = $this->getClass('classIssuedAt')) {
            return $classIssuedAt;
        }

        $class = $this->getTextFromConfig($type, 'issued_at');

        if (!empty($class)) {
            return $class;
        }

        return 'col-lg-2 col-xl-2 d-none d-lg-block text-left';
    }

    protected function getTextDueAt($type, $textDueAt)
    {
        if (!empty($textDueAt)) {
            return $textDueAt;
        }

        $translation = $this->getTextFromConfig($type, 'due_at', 'due_date');

        if (!empty($translation)) {
            return $translation;
        }

        return 'invoices.due_date';
    }

    protected function getClassDueAt($type, $classDueAt)
    {
        if (!empty($classDueAt)) {
            return $classDueAt;
        }

        $class = $this->getTextFromConfig($type, 'due_at');

        if (!empty($class)) {
            return $class;
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

        $default_key = config('type.' . $type . '.translation.prefix') . '.statuses.';

        $translation = $this->getTextFromConfig($type, 'document_status', $default_key);

        if (!empty($translation)) {
            return $translation;
        }

        return 'invoices.statuses.';
    }

    protected function getClassStatus($type, $classStatus)
    {
        if (!empty($classStatus)) {
            return $classStatus;
        }

        if ($classStatus = $this->getClass('classStatus')) {
            return $classStatus;
        }

        $class = $this->getTextFromConfig($type, 'status');

        if (!empty($class)) {
            return $class;
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

        $class = $this->getTextFromConfig($type, 'actions');

        if (!empty($class)) {
            return $class;
        }

        return 'col-xs-4 col-sm-2 col-md-2 col-lg-1 col-xl-1 text-center';
    }

    protected function getRouteButtonShow($type, $routeButtonShow)
    {
        if (!empty($routeButtonShow)) {
            return $routeButtonShow;
        }

        //example route parameter.
        $parameter = 1;

        $route = $this->getRouteFromConfig($type, 'show', $parameter);

        if (!empty($route)) {
            return $route;
        }

        return 'invoices.show';
    }

    protected function getRouteButtonEdit($type, $routeButtonEdit)
    {
        if (!empty($routeButtonEdit)) {
            return $routeButtonEdit;
        }

        //example route parameter.
        $parameter = 1;

        $route = $this->getRouteFromConfig($type, 'edit', $parameter);

        if (!empty($route)) {
            return $route;
        }

        return 'invoices.edit';
    }

    protected function getRouteButtonDuplicate($type, $routeButtonDuplicate)
    {
        if (!empty($routeButtonDuplicate)) {
            return $routeButtonDuplicate;
        }

        //example route parameter.
        $parameter = 1;

        $route = $this->getRouteFromConfig($type, 'duplicate', $parameter);

        if (!empty($route)) {
            return $route;
        }

        return 'invoices.duplicate';
    }

    protected function getRouteButtonCancelled($type, $routeButtonCancelled)
    {
        if (!empty($routeButtonCancelled)) {
            return $routeButtonCancelled;
        }

        //example route parameter.
        $parameter = 1;

        $route = $this->getRouteFromConfig($type, 'cancelled', $parameter);

        if (!empty($route)) {
            return $route;
        }

        return 'invoices.cancelled';
    }

    protected function getRouteButtonDelete($type, $routeButtonDelete)
    {
        if (!empty($routeButtonDelete)) {
            return $routeButtonDelete;
        }

        //example route parameter.
        $parameter = 1;

        $route = $this->getRouteFromConfig($type, 'destroy', $parameter);

        if (!empty($route)) {
            return $route;
        }

        return 'invoices.destroy';
    }

    protected function getPermissionCreate($type, $permissionCreate)
    {
        if (!empty($permissionCreate)) {
            return $permissionCreate;
        }

        $permissionCreate = $this->getPermissionFromConfig($type, 'create');

        return $permissionCreate;
    }

    protected function getPermissionUpdate($type, $permissionUpdate)
    {
        if (!empty($permissionUpdate)) {
            return $permissionUpdate;
        }

        $permissionUpdate = $this->getPermissionFromConfig($type, 'update');

        return $permissionUpdate;
    }

    protected function getPermissionDelete($type, $permissionDelete)
    {
        if (!empty($permissionDelete)) {
            return $permissionDelete;
        }

        $permissionDelete = $this->getPermissionFromConfig($type, 'delete');

        return $permissionDelete;
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
                        $class = 'col-md-4 col-lg-3 col-xl-3 d-none d-md-block';
                        $this->class_count += 2;
                        break;
                    case 3:
                        $class = 'col-md-5 col-lg-4 col-xl-4 d-none d-md-block';
                        $this->class_count += 3;
                        break;
                }
        }

        return $class;
    }
}
