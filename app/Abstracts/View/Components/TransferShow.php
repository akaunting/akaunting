<?php

namespace App\Abstracts\View\Components;

use App\Models\Common\Media;
use App\Traits\DateTime;
use App\Traits\Transactions;
use App\Utilities\Modules;
use File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Image;
use Intervention\Image\Exception\NotReadableException;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Component;

abstract class TransferShow extends Component
{
    use DateTime, Transactions;

    public $transfer;

    /** @var string */
    public $transferTemplate;

    /** @var array */
    public $payment_methods;

    public $date_format;

    /** @var bool */
    public $hideButtonAddNew;

    /** @var bool */
    public $hideButtonMoreActions;

    /** @var bool */
    public $hideButtonEdit;

    /** @var bool */
    public $hideButtonDuplicate;

    /** @var bool */
    public $hideButtonPrint;

    /** @var bool */
    public $hideButtonShare;

    /** @var bool */
    public $hideButtonEmail;

    /** @var bool */
    public $hideButtonPdf;

    /** @var bool */
    public $hideButtonDelete;

    /** @var bool */
    public $hideButtonGroupDivider1;

    /** @var bool */
    public $hideButtonGroupDivider2;

    /** @var bool */
    public $hideButtonGroupDivider3;

    /** @var string */
    public $permissionCreate;

    /** @var string */
    public $permissionUpdate;

    /** @var string */
    public $permissionDelete;

    /** @var string */
    public $routeButtonAddNew;

    /** @var string */
    public $routeButtonEdit;

    /** @var string */
    public $routeButtonDuplicate;

    /** @var string */
    public $routeButtonPrint;

    /** @var string */
    public $signedUrl;

    /** @var string */
    public $routeButtonEmail;

    /** @var string */
    public $routeButtonPdf;

    /** @var string */
    public $hideButtonTemplate;

    /** @var string */
    public $routeButtonDelete;

    /** @var string */
    public $routeFromAccountShow;

    /** @var string */
    public $routeToAccountShow;

    /** @var string */
    public $textDeleteModal;

    /** @var bool */
    public $hideHeader;

    /** @var bool */
    public $hideHeaderFromAccount;

    /** @var bool */
    public $hideHeaderToAccount;

    /** @var bool */
    public $hideHeaderAmount;

    /** @var bool */
    public $hideHeaderPaidAt;

    /** @var string */
    public $textHeaderFromAccount;

    /** @var string */
    public $textHeaderToAccount;

    /** @var string */
    public $textHeaderAmount;

    /** @var string */
    public $textHeaderPaidAt;

    /** @var string */
    public $classHeaderFromAccount;

    /** @var string */
    public $classHeaderToAccount;

    /** @var string */
    public $classHeaderAmount;

    /** @var string */
    public $classHeaderPaidAt;

    /** @var bool */
    public $hideFromAccount;

    /** @var bool */
    public $hideFromAccountTitle;

    /** @var bool */
    public $hideFromAccountName;

    /** @var bool */
    public $hideFromAccountNumber;

    /** @var bool */
    public $hideFromAccountBankName;

    /** @var bool */
    public $hideFromAccountBankPhone;

    /** @var bool */
    public $hideFromAccountBankAddress;

    /** @var string */
    public $textFromAccountTitle;

    /** @var string */
    public $textFromAccountNumber;

    /** @var bool */
    public $hideToAccount;

    /** @var bool */
    public $hideToAccountTitle;

    /** @var bool */
    public $hideToAccountName;

    /** @var bool */
    public $hideToAccountNumber;

    /** @var bool */
    public $hideToAccountBankName;

    /** @var bool */
    public $hideToAccountBankPhone;

    /** @var bool */
    public $hideToAccountBankAddress;

    /** @var string */
    public $textToAccountTitle;

    /** @var string */
    public $textToAccountNumber;

    /** @var bool */
    public $hideDetails;

    /** @var bool */
    public $hideDetailTitle;

    /** @var bool */
    public $hideDetailDate;

    /** @var bool */
    public $hideDetailPaymentMethod;

    /** @var bool */
    public $hideDetailReference;

    /** @var bool */
    public $hideDetailDescription;

    /** @var bool */
    public $hideDetailAmount;

    /** @var string */
    public $textDetailTitle;

    /** @var string */
    public $textDetailDate;

    /** @var string */
    public $textDetailPaymentMethod;

    /** @var string */
    public $textDetailReference;

    /** @var string */
    public $textDetailDescription;

    /** @var string */
    public $textDetailAmount;

    /** @var bool */
    public $hideAttachment;

    public $attachment;

    /** @var bool */
    public $hideFooter;

    /** @var bool */
    public $hideFooterHistories;

    public $histories;

    /** @var string */
    public $textHistories;

    /** @var string */
    public $classFooterHistories;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $transfer, $transferTemplate = '', array $payment_methods = [],
        bool $hideButtonAddNew = false, bool $hideButtonMoreActions = false, bool $hideButtonEdit = false, bool $hideButtonDuplicate = false, bool $hideButtonPrint = false, bool $hideButtonShare = false,
        bool $hideButtonEmail = false, bool $hideButtonPdf = false, bool $hideButtonTemplate = false, bool $hideButtonDelete = false,
        bool $hideButtonGroupDivider1 = false, bool $hideButtonGroupDivider2 = false, bool $hideButtonGroupDivider3 = false,
        string $permissionCreate = '', string $permissionUpdate = '', string $permissionDelete = '',
        string $routeButtonAddNew = '', string $routeButtonEdit = '', string $routeButtonDuplicate = '', string $routeButtonPrint = '', string $signedUrl = '',
        string $routeButtonEmail = '', string $routeButtonPdf = '', string $routeButtonDelete = '', string $routeFromAccountShow = '', string $routeToAccountShow = '',
        string $textDeleteModal = '',
        bool $hideHeader = false, bool $hideHeaderFromAccount = false, bool $hideHeaderToAccount = false, bool $hideHeaderAmount = false, bool $hideHeaderPaidAt = false,
        string $textHeaderFromAccount = '', string $textHeaderToAccount = '', string $textHeaderAmount = '', string $textHeaderPaidAt = '',
        string $classHeaderFromAccount = '', string $classHeaderToAccount = '', string $classHeaderAmount = '', string $classHeaderPaidAt = '',

        bool $hideFromAccount = false, bool $hideFromAccountTitle = false, bool $hideFromAccountName = false, bool $hideFromAccountNumber = false,
        bool $hideFromAccountBankName = false, bool $hideFromAccountBankPhone = false, bool $hideFromAccountBankAddress = false,
        string $textFromAccountTitle = '', string $textFromAccountNumber = '',

        bool $hideToAccount = false, bool $hideToAccountTitle = false, bool $hideToAccountName = false, bool $hideToAccountNumber = false,
        bool $hideToAccountBankName = false, bool $hideToAccountBankPhone = false, bool $hideToAccountBankAddress = false,
        string $textToAccountTitle = '', string $textToAccountNumber = '',

        bool $hideDetails = false, bool $hideDetailTitle = false, bool $hideDetailDate = false, bool $hideDetailPaymentMethod = false,
        bool $hideDetailReference = false, bool $hideDetailDescription = false, bool $hideDetailAmount = false,
        string $textDetailTitle = '', string $textDetailDate = '', string $textDetailPaymentMethod = '', string $textDetailReference = '',
        string $textDetailDescription = '', string $textDetailAmount = '',

        bool $hideAttachment = false, $attachment = [],
        bool $hideFooter = false, bool $hideFooterHistories = false, $histories = [],
        string $textHistories = '', string $classFooterHistories = ''
    ) {
        $this->transfer = $transfer;
        $this->transferTemplate = $this->getTransferTemplate($transferTemplate);
        $this->payment_methods = ($payment_methods) ?: Modules::getPaymentMethods('all');
        $this->date_format = $this->getCompanyDateFormat();

        // Navbar Hide
        $this->hideButtonAddNew = $hideButtonAddNew;
        $this->hideButtonMoreActions = $hideButtonMoreActions;
        $this->hideButtonEdit = $hideButtonEdit;
        $this->hideButtonDuplicate  = $hideButtonDuplicate;
        $this->hideButtonPrint  = $hideButtonPrint;
        $this->hideButtonShare = $hideButtonShare;
        $this->hideButtonEmail = $hideButtonEmail;
        $this->hideButtonPdf  = $hideButtonPdf;
        $this->hideButtonTemplate = $hideButtonTemplate;
        $this->hideButtonDelete = $hideButtonDelete;
        $this->hideButtonGroupDivider1 = $hideButtonGroupDivider1;
        $this->hideButtonGroupDivider2 = $hideButtonGroupDivider2;
        $this->hideButtonGroupDivider3 = $hideButtonGroupDivider3;

        // Navbar Permission
        $this->permissionCreate = $this->getPermissionCreate($permissionCreate);
        $this->permissionUpdate = $this->getPermissionUpdate($permissionUpdate);
        $this->permissionDelete = $this->getPermissionDelete($permissionDelete);

        // Navbar route
        $this->routeButtonAddNew = $this->getRouteButtonAddNew($routeButtonAddNew);
        $this->routeButtonEdit = $this->getRouteButtonEdit($routeButtonEdit);
        $this->routeButtonDuplicate = $this->getRouteButtonDuplicate($routeButtonDuplicate);
        $this->routeButtonPrint = $this->getRouteButtonPrint($routeButtonPrint);
        $this->signedUrl = $this->getSignedUrl($signedUrl);
        $this->routeButtonEmail = $this->getRouteButtonEmail($routeButtonEmail);
        $this->routeButtonPdf = $this->getRouteButtonPdf($routeButtonPdf);
        $this->routeButtonDelete = $this->getRouteButtonDelete($routeButtonDelete);
        $this->routeFromAccountShow = $this->getRouteFromAccountShow($routeFromAccountShow);
        $this->routeToAccountShow = $this->getRouteToAccountShow($routeToAccountShow);

        // Navbar Text
        $this->textDeleteModal = $textDeleteModal;

        // Header Hide
        $this->hideHeader = $hideHeader;

        $this->hideHeaderFromAccount = $hideHeaderFromAccount;
        $this->hideHeaderToAccount = $hideHeaderToAccount;
        $this->hideHeaderToAccount = $hideHeaderToAccount;
        $this->hideHeaderAmount = $hideHeaderAmount;
        $this->hideHeaderPaidAt = $hideHeaderPaidAt;

        // Header Text
        $this->textHeaderFromAccount = $this->getTextHeaderFromAccount($textHeaderFromAccount);
        $this->textHeaderToAccount = $this->getTextHeaderToAccount($textHeaderToAccount);
        $this->textHeaderAmount = $this->getTextHeaderAmount($textHeaderAmount);
        $this->textHeaderPaidAt = $this->gettextHeaderPaidAt($textHeaderPaidAt);

        // Header Class
        $this->classHeaderFromAccount = $this->getclassHeaderFromAccount($classHeaderFromAccount);
        $this->classHeaderToAccount = $this->getClassHeaderToAccount($classHeaderToAccount);
        $this->classHeaderAmount = $this->getClassHeaderAmount($classHeaderAmount);
        $this->classHeaderPaidAt = $this->getclassHeaderPaidAt($classHeaderPaidAt);

        // From account Hide
        $this->hideFromAccount = $hideFromAccount;
        $this->hideFromAccountTitle = $hideFromAccountTitle;
        $this->hideFromAccountName = $hideFromAccountName;
        $this->hideFromAccountNumber = $hideFromAccountNumber;
        $this->hideFromAccountBankName = $hideFromAccountBankName;
        $this->hideFromAccountBankPhone = $hideFromAccountBankPhone;
        $this->hideFromAccountBankAddress = $hideFromAccountBankAddress;

        // From account text
        $this->textFromAccountTitle = $this->getTextFromAccountTitle($textFromAccountTitle);
        $this->textFromAccountNumber = $this->getTextFromAccountNumber($textFromAccountNumber);

        // To account Hide
        $this->hideToAccount = $hideToAccount;
        $this->hideToAccountTitle = $hideToAccountTitle;
        $this->hideToAccountName = $hideToAccountName;
        $this->hideToAccountNumber = $hideToAccountNumber;
        $this->hideToAccountBankName = $hideToAccountBankName;
        $this->hideToAccountBankPhone = $hideToAccountBankPhone;
        $this->hideToAccountBankAddress = $hideToAccountBankAddress;

        // To account text
        $this->textToAccountTitle = $this->getTextToAccountTitle($textToAccountTitle);
        $this->textToAccountNumber = $this->getTextToAccountNumber($textToAccountNumber);

        // Detail Information Hide checker
        $this->hideDetails = $hideDetails;
        $this->hideDetailTitle = $hideDetailTitle;
        $this->hideDetailDate = $hideDetailDate;
        $this->hideDetailPaymentMethod = $hideDetailPaymentMethod;
        $this->hideDetailReference = $hideDetailReference;
        $this->hideDetailDescription = $hideDetailDescription;
        $this->hideDetailAmount = $hideDetailAmount;

        // Releated Information Text
        $this->textDetailTitle = $this->getTextDetailTitle($textDetailTitle);
        $this->textDetailDate = $this->getTextDetailDate($textDetailDate);
        $this->textDetailPaymentMethod = $this->getTextDetailPaymentMethod($textDetailPaymentMethod);
        $this->textDetailReference = $this->getTextDetailReference($textDetailReference);
        $this->textDetailDescription = $this->getTextDetailDescription($textDetailDescription);
        $this->textDetailAmount = $this->getTextDetailAmount($textDetailAmount);

        // Hide Attachment
        $this->hideAttachment = $hideAttachment;

        // Attachment data..
        $this->attachment = '';

        if (!empty($attachment)) {
            $this->attachment = $attachment;
        } else if (!empty($transfer)) {
            $this->attachment = $transfer->attachment;
        }

        // Histories Hide
        $this->hideFooter = $hideFooter;
        $this->hideFooterHistories = $hideFooterHistories;

        // Histories
        $this->histories = $this->getHistories($histories);
        $this->textHistories = $this->getTextHistories($textHistories);
        $this->classFooterHistories = $this->getClassFooterHistories($classFooterHistories);
    }

    protected function getTransferTemplate($transferTemplate)
    {
        if (!empty($transferTemplate)) {
            return $transferTemplate;
        }

        return setting('transfer.template');
    }

    protected function getRouteButtonAddNew($routeButtonAddNew)
    {
        if (!empty($routeButtonAddNew)) {
            return $routeButtonAddNew;
        }

        return 'transfers.create';
    }

    protected function getRouteButtonEdit($routeButtonEdit)
    {
        if (!empty($routeButtonEdit)) {
            return $routeButtonEdit;
        }

        return 'transfers.edit';
    }

    protected function getRouteButtonDuplicate($routeButtonDuplicate)
    {
        if (!empty($routeButtonDuplicate)) {
            return $routeButtonDuplicate;
        }

        return 'transfers.duplicate';
    }

    protected function getRouteButtonPrint($routeButtonPrint)
    {
        if (!empty($routeButtonPrint)) {
            return $routeButtonPrint;
        }

        return 'transfers.print';
    }

    protected function getSignedUrl($signedUrl)
    {
        if (!empty($signedUrl)) {
            return $signedUrl;
        }

        try {
            $signedUrl = URL::signedRoute('signed.transfer.show', [$this->transfer->id]);
        } catch (\Exception $e) {
            $signedUrl = false;
        }

        return $signedUrl;
    }

    protected function getRouteButtonEmail($routeButtonEmail)
    {
        if (!empty($routeButtonEmail)) {
            return $routeButtonEmail;
        }

        return 'transfers.email';
    }

    protected function getRouteButtonPdf($routeButtonPdf)
    {
        if (!empty($routeButtonPdf)) {
            return $routeButtonPdf;
        }

        return 'transfers.pdf';
    }

    protected function getRouteButtonDelete($routeButtonDelete)
    {
        if (!empty($routeButtonDelete)) {
            return $routeButtonDelete;
        }

        return 'transfers.destroy';
    }

    protected function getRouteFromAccountShow($routeFromAccountShow)
    {
        if (!empty($routeFromAccountShow)) {
            return $routeFromAccountShow;
        }

        return 'accounts.show';
    }

    protected function getRouteToAccountShow($routeToAccountShow)
    {
        if (!empty($routeToAccountShow)) {
            return $routeToAccountShow;
        }

        return 'accounts.show';
    }

    protected function getPermissionCreate($permissionCreate)
    {
        if (!empty($permissionCreate)) {
            return $permissionCreate;
        }

        return 'create-banking-transfers';
    }

    protected function getPermissionUpdate($permissionUpdate)
    {
        if (!empty($permissionUpdate)) {
            return $permissionUpdate;
        }

        return 'update-banking-transfers';
    }

    protected function getPermissionDelete($permissionDelete)
    {
        if (!empty($permissionDelete)) {
            return $permissionDelete;
        }

        return 'delete-banking-transfers';
    }

    protected function getTextHeaderFromAccount($textHeaderFromAccount)
    {
        if (!empty($textHeaderFromAccount)) {
            return $textHeaderFromAccount;
        }

        return 'transfers.from_account';
    }

    protected function getTextHeaderToAccount($textHeaderToAccount)
    {
        if (!empty($textHeaderToAccount)) {
            return $textHeaderToAccount;
        }

        return 'transfers.to_account';
    }

    protected function getTextHeaderAmount($textHeaderAmount)
    {
        if (!empty($textHeaderAmount)) {
            return $textHeaderAmount;
        }

        return 'general.amount';
    }

    protected function getTextHeaderPaidAt($textHeaderPaidAt)
    {
        if (!empty($textHeaderPaidAt)) {
            return $textHeaderPaidAt;
        }

        return 'general.date';
    }

    protected function getClassHeaderFromAccount($classHeaderFromAccount)
    {
        if (!empty($classHeaderFromAccount)) {
            return $classHeaderFromAccount;
        }

        return 'col-4 col-lg-2';
    }

    protected function getClassHeaderToAccount($classHeaderToAccount)
    {
        if (!empty($classHeaderToAccount)) {
            return $classHeaderToAccount;
        }

        return 'col-4 col-lg-6';
    }

    protected function getClassHeaderAmount($classHeaderAmount)
    {
        if (!empty($classHeaderAmount)) {
            return $classHeaderAmount;
        }

        return 'col-4 col-lg-2 float-right';
    }

    protected function getClassHeaderPaidAt($classHeaderPaidAt)
    {
        if (!empty($classHeaderPaidAt)) {
            return $classHeaderPaidAt;
        }

        return 'col-4 col-lg-2';
    }

    protected function getTextFromAccountTitle($textToAccountTitle)
    {
        if (!empty($textToAccountTitle)) {
            return $textToAccountTitle;
        }

        return 'transfers.from_account';
    }

    protected function getTextFromAccountNumber($textFromAccountNumber)
    {
        if (!empty($textFromAccountNumber)) {
            return $textFromAccountNumber;
        }

        return 'accounts.number';
    }

    protected function getTextToAccountTitle($textFromAccountTitle)
    {
        if (!empty($textFromAccountTitle)) {
            return $textFromAccountTitle;
        }

        return 'transfers.to_account';
    }

    protected function getTextToAccountNumber($textToAccountNumber)
    {
        if (!empty($textToAccountNumber)) {
            return $textToAccountNumber;
        }

        return 'accounts.number';
    }

    protected function getTextDetailTitle($textDetailTitle)
    {
        if (!empty($textDetailTitle)) {
            return $textDetailTitle;
        }

        return 'transfers.details';
    }

    protected function getTextDetailDate($textDetailDate)
    {
        if (!empty($textDetailDate)) {
            return $textDetailDate;
        }

        return 'general.date';
    }

    protected function getTextDetailPaymentMethod($textDetailPaymentMethod)
    {
        if (!empty($textDetailPaymentMethod)) {
            return $textDetailPaymentMethod;
        }

        return 'general.payment_methods';
    }

    protected function getTextDetailReference($textDetailReference)
    {
        if (!empty($textDetailReference)) {
            return $textDetailReference;
        }

        return 'general.reference';
    }

    protected function getTextDetailDescription($textDetailDescription)
    {
        if (!empty($textDetailDescription)) {
            return $textDetailDescription;
        }

        return 'general.description';
    }

    protected function getTextDetailAmount($textDetailAmount)
    {
        if (!empty($textDetailAmount)) {
            return $textDetailAmount;
        }

        return 'general.amount';
    }

    protected function getHistories($histories)
    {
        if (!empty($histories)) {
            return $histories;
        }

        $histories[] = $this->transfer;

        return $histories;
    }

    protected function getTextHistories($textHistories)
    {
        if (!empty($textHistories)) {
            return $textHistories;
        }

        return 'invoices.histories';
    }

    protected function getClassFooterHistories($classFooterHistories)
    {
        if (!empty($classFooterHistories)) {
            return $classFooterHistories;
        }

        return 'col-sm-6 col-md-6 col-lg-6 col-xl-6';
    }
}
