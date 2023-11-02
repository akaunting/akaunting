<?php

namespace App\Abstracts\View\Components\Contacts;

use App\Abstracts\View\Component;
use App\Traits\ViewComponents;

abstract class Show extends Component
{
    use ViewComponents;

    public const OBJECT_TYPE = 'contact';
    public const DEFAULT_TYPE = 'customer';
    public const DEFAULT_PLURAL_TYPE = 'customers';

    /* -- Main Start -- */
    public $type;

    public $contact;

    public $model;

    public $permissionCreate;

    public $permissionUpdate;

    public $permissionDelete;
    /* -- Main End -- */

    /* -- Buttons Start -- */
    public $hideNewDropdown;

    public $hideButtonDocument;

    public $hideButtonTransaction;

    public $permissionCreateDocument;

    public $permissionCreateTransaction;

    public $routeButtonDocument;

    public $routeButtonTransaction;

    public $textDocument;

    public $textTransaction;

    public $hideButtonEdit;

    public $routeButtonEdit;

    public $hideActionsDropdown;

    public $hideButtonDuplicate;

    public $routeButtonDuplicate;

    public $hideButtonDelete;

    public $routeButtonDelete;

    public $textDeleteModal;
    /* -- Buttons End -- */

    /* -- Profile Start -- */
    public $hideTopLeft;

    public $hideAvatar;

    public $hideEmail;

    public $hidePhone;

    public $hideTopRight;

    public $hideOverdue;

    public $hideOpen;

    public $hidePaid;

    public $hideBottomLeft;

    public $hideAddress;

    public $hideTaxNumber;

    public $hideWebsite;

    public $hideReference;

    public $hideUser;

    public $hidePersons;

    public $hideBottomRight;
    /* -- Profile End -- */

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $type, $model = false, $contact = false,
        string $permissionCreate = '', string $permissionUpdate = '', string $permissionDelete = '',
        bool $hideNewDropdown = false, bool $hideButtonDocument = false, $hideButtonTransaction = false,
        string $permissionCreateDocument = '', string $permissionCreateTransaction = '',
        $routeButtonDocument = '', $routeButtonTransaction = '',
        string $textDocument = '', string $textTransaction = '',
        bool $hideButtonEdit = false, $routeButtonEdit = '',
        bool $hideActionsDropdown = false, bool $hideButtonDuplicate = false, $routeButtonDuplicate = '',
        bool $hideButtonDelete = false, $routeButtonDelete = '', $textDeleteModal = '',
        bool $hideTopLeft = false, bool $hideAvatar = false, bool $hideEmail = false, bool $hidePhone = false,
        bool $hideTopRight = false, bool $hideOverdue = false, bool $hideOpen = false, bool $hidePaid = false,
        bool $hideBottomLeft = false, bool $hideAddress = false, bool $hideTaxNumber = false , bool $hideWebsite = false, bool $hideReference = false, bool $hideUser = false, bool $hidePersons = false,
        bool $hideBottomRight = false
    ) {
        /* -- Main Start -- */
        $this->type = $type;

        $this->model = ! empty($model) ? $model : $contact;
        $this->contact = $this->model;

        $this->permissionCreate = $this->getPermissionCreate($type, $permissionCreate);
        $this->permissionUpdate = $this->getPermissionUpdate($type, $permissionUpdate);
        $this->permissionDelete = $this->getPermissionDelete($type, $permissionDelete);
        /* -- Main End -- */

        /* -- Buttons Start -- */
        $this->hideNewDropdown = $hideNewDropdown;
        $this->hideButtonDocument = $hideButtonDocument;
        $this->hideButtonTransaction = $hideButtonTransaction;

        $this->permissionCreateDocument = $this->getPermissionCreateDocument($type, $permissionCreateDocument);
        $this->permissionCreateTransaction = $this->getPermissionCreateTransaction($type, $permissionCreateTransaction);

        $this->routeButtonDocument = $this->getCreateDocumentRoute($type, $routeButtonDocument);
        $this->routeButtonTransaction = $this->getCreateTransactionRoute($type, $routeButtonTransaction);

        $this->textDocument = $this->getTextDocument($type, $textDocument);
        $this->textTransaction = $this->getTextTransaction($type, $textTransaction);

        $this->hideButtonEdit = $hideButtonEdit;
        $this->routeButtonEdit = $this->getEditRoute($type, $routeButtonEdit);

        $this->hideActionsDropdown = $hideActionsDropdown;
        $this->hideButtonDuplicate = $hideButtonDuplicate;
        $this->routeButtonDuplicate = $this->getDuplicateRoute($type, $routeButtonDuplicate);

        $this->hideButtonDelete = $hideButtonDelete;
        $this->routeButtonDelete = $this->getDeleteRoute($type, $routeButtonDelete);
        $this->textDeleteModal = $this->getTextDeleteModal($type, $textDeleteModal);
        /* -- Buttons End -- */

        /* -- Profile Start -- */
        $this->hideProfile = $hideTopLeft;
        $this->hideAvatar = $hideAvatar;
        $this->hideEmail = $hideEmail;
        $this->hidePhone = $hidePhone;

        $this->hideDetails = $hideTopRight;
        $this->hideOverdue = $hideOverdue;
        $this->hideOpen = $hideOpen;
        $this->hidePaid = $hidePaid;

        $this->hideSummary = $hideBottomLeft;
        $this->hideAddress = $hideAddress;
        $this->hideTaxNumber = $hideTaxNumber;
        $this->hideWebsite = $hideWebsite;
        $this->hideReference = $hideReference;
        $this->hideUser = $hideUser;
        $this->hidePersons = $hidePersons;

        $this->hideContent = $hideBottomRight;
        /* -- Profile End -- */
    }

    protected function getPermissionCreateDocument($type, $permissionCreateDocument)
    {
        if (! empty($permissionCreateDocument)) {
            return $permissionCreateDocument;
        }

        $document_type = config('type.contact.' . $type . '.document_type', 'invoice');

        $permission = '';
        $config_key = 'create';

        // if set config translation config_key
        if ($permission = config('type.document.' . $document_type . '.permission.' . $config_key)) {
            return $permission;
        }

        $alias = config('type.document.' . $document_type . '.alias');
        $group = config('type.document.' . $document_type . '.group');
        $prefix = config('type.document.' . $document_type . '.permission.prefix');

        $permission = $config_key . '-';

        // if use module set module alias
        if (! empty($alias)) {
            $permission .= $alias . '-';
        }

        // if controller in folder it must
        if (! empty($group)) {
            $permission .= $group . '-';
        }

        $permission .= $prefix;

        $permissionCreateDocument = $permission;

        return $permissionCreateDocument;
    }

    protected function getPermissionCreateTransaction($type, $permissionCreateTransaction)
    {
        if (! empty($permissionCreateTransaction)) {
            return $permissionCreateTransaction;
        }

        $permissionCreateTransaction = 'create-banking-transactions';

        return $permissionCreateTransaction;
    }

    protected function getCreateDocumentRoute($type, $routeButtonDocument)
    {
        if (! empty($routeButtonDocument)) {
            return $routeButtonDocument;
        }

        $prefix = config('type.contact.' . $type . '.route.prefix');
        $document_type = config('type.contact.' . $type . '.document_type');

        return $prefix . '.create-' . $document_type;
    }

    protected function getCreateTransactionRoute($type, $routeButtonDocument)
    {
        if (! empty($routeButtonDocument)) {
            return $routeButtonDocument;
        }

        $prefix = config('type.contact.' . $type . '.route.prefix');
        $transaction_type = config('type.contact.' . $type . '.transaction_type');

        return $prefix . '.create-' . $transaction_type;
    }

    protected function getTextDocument($type, $textDocument)
    {
        if (! empty($textDocument)) {
            return $textDocument;
        }

        $document_type = config('type.contact.' . $type . '.document_type');

        switch ($document_type) {
            case 'invoice':
                $text = 'general.invoices';
                break;
            case 'bill':
                $text = 'general.bills';
                break;
            default:
                $text = config('type.contact.' . $type . '.translation.prefix') . '.' . config('type.contact.' . $type . '.route.prefix');
        }

        return $text;
    }

    protected function getTextTransaction($type, $textTransaction)
    {
        if (! empty($textTransaction)) {
            return $textTransaction;
        }

        $document_type = config('type.contact.' . $type . '.document_type');

        switch ($document_type) {
            case 'invoice':
                $text = 'general.incomes';
                break;
            case 'bill':
                $text = 'general.expenses';
                break;
            default:
                $text = config('type.contact.' . $type . '.translation.prefix') . '.' . config('type.contact.' . $type . '.transaction_type') . 's';
        }

        return $text;
    }

    protected function getTextDeleteModal($type, $textDeleteModal)
    {
        if (! empty($textDeleteModal)) {
            return $textDeleteModal;
        }

        $document_type = config('type.contact.' . $type . '.document_type');

        switch ($document_type) {
            case 'invoice':
                $text = 'general.incomes';
                break;
            case 'bill':
                $text = 'general.expenses';
                break;
            default:
                $text = config('type.contact.' . $type . '.translation.prefix') . '.' . config('type.contact.' . $type . '.transaction_type') . 's';
        }

        return $text;
    }
}
