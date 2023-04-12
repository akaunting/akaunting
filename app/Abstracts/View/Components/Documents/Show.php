<?php

namespace App\Abstracts\View\Components\Documents;

use App\Traits\DateTime;
use App\Traits\Documents;
use App\Models\Common\Media;
use App\Traits\Tailwind;
use App\Traits\ViewComponents;
use App\Abstracts\View\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Exception\NotReadableException;
use Image;

abstract class Show extends Component
{
    use DateTime, Documents, Tailwind, ViewComponents;

    public const OBJECT_TYPE = 'document';
    public const DEFAULT_TYPE = 'invoice';
    public const DEFAULT_PLURAL_TYPE = 'invoices';

    /* -- Main Start -- */
    public $type;

    public $document;

    public $transactions;

    /** @var string */
    public $permissionCreate;

    /** @var string */
    public $permissionUpdate;

    /** @var string */
    public $permissionDelete;
    /* -- Main End -- */

    /* -- Buttons Start -- */
    /** @var string */
    public $textPage;

    /** @var bool */
    public $hideCreate;

    /** @var string */
    public $createRoute;

    /** @var string */
    public $textCreate;

    /** @var bool */
    public $hideButtonStatuses;

    /** @var bool */
    public $hideEdit;

    /** @var string */
    public $editRoute;

    /** @var string */
    public $showRoute;

    /** @var bool */
    public $hideMoreActions;

    /** @var bool */
    public $hideDuplicate;

    /** @var string */
    public $duplicateRoute;

    /** @var bool */
    public $hidePrint;

    /** @var string */
    public $printRoute;

    /** @var bool */
    public $hideShare;

    /** @var string */
    public $shareRoute;

    /** @var string */
    public $signedUrl;

    /** @var bool */
    public $hideEmail;

    /** @var string */
    public $emailRoute;

    /** @var string */
    public $textEmail;

    /** @var bool */
    public $hidePdf;

    /** @var string */
    public $pdfRoute;

    /** @var bool */
    public $hideCancel;

    /** @var string */
    public $cancelledRoute;

    /** @var bool */
    public $hideCustomize;

    /** @var string */
    public $permissionCustomize;

    /** @var string */
    public $customizeRoute;

    /** @var bool */
    public $hideEnd;

    /** @var string */
    public $endRoute;

    /** @var bool */
    public $hideDelete;

    /** @var bool */
    public $checkReconciled;

    /** @var string */
    public $deleteRoute;

    /** @var string */
    public $textDeleteModal;

    /** @var bool */
    public $hideDivider1;

    /** @var bool */
    public $hideDivider2;

    /** @var bool */
    public $hideDivider3;

    /** @var bool */
    public $hideDivider4;
    /* -- Buttons End -- */

    /* -- Content Start -- */
    /** @var string */
    public $accordionActive;

    /** @var bool */
    public $hideRecurringMessage;

    /** @var string */
    public $textRecurringType;

    /** @var bool */
    public $hideStatusMessage;

    /** @var string */
    public $textStatusMessage;

    /** @var bool */
    public $hideCreated;

    /** @var bool */
    public $hideSend;

    /** @var bool */
    public $hideMarkSent;

    /** @var string */
    public $markSentRoute;

    /** @var string */
    public $textMarkSent;

    /** @var bool */
    public $hideReceive;

    /** @var bool */
    public $hideMarkReceived;

    /** @var string */
    public $markReceivedRoute;

    /** @var string */
    public $textMarkReceived;

    /** @var bool */
    public $hideGetPaid;

    /** @var bool */
    public $hideAddPayment;

    /** @var bool */
    public $hideAcceptPayment;

    /** @var bool */
    public $hideMakePayment;

    /** @var string */
    public $transactionEmailRoute;

    /** @var string */
    public $transactionEmailTemplate;

    /** @var bool */
    public $hideRestore;

    /** @var bool */
    public $hideSchedule;

    /** @var bool */
    public $hideChildren;

    /** @var bool */
    public $hideAttachment;

    public $attachment;
    /* -- Content End -- */

    /** @var string */
    public $documentTemplate;

    public $logo;

    public $backgroundColor;

    public $hideFooter;

    public $hideCompanyLogo;

    public $hideCompanyDetails;

    public $hideCompanyName;

    public $hideCompanyAddress;

    public $hideCompanyTaxNumber;

    public $hideCompanyPhone;

    public $hideCompanyEmail;

    public $hideContactInfo;

    public $hideContactName;

    public $hideContactAddress;

    public $hideContactTaxNumber;

    public $hideContactPhone;

    public $hideContactEmail;

    public $hideOrderNumber;

    public $hideDocumentNumber;

    public $hideIssuedAt;

    public $hideDueAt;

    /** @var string */
    public $textDocumentTitle;

    /** @var string */
    public $textDocumentSubheading;

    public $textContactInfo;

    /** @var string */
    public $textIssuedAt;

    /** @var string */
    public $textDueAt;

    /** @var string */
    public $textDocumentNumber;

    /** @var string */
    public $textOrderNumber;

    public $hideItems;

    public $hideName;

    public $hideDescription;

    public $hideQuantity;

    public $hidePrice;

    public $hideDiscount;

    public $hideAmount;

    /** @var string */
    public $textItems;

    /** @var string */
    public $textQuantity;

    /** @var string */
    public $textPrice;

    /** @var string */
    public $textAmount;

    public $hideNote;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $type, $document, $transactions = [],
        string $permissionCreate = '', string $permissionUpdate = '', string $permissionDelete = '', string $textPage = '',
        bool $hideCreate = false, string $createRoute = '', string $textCreate = '', bool $hideButtonStatuses = false, bool $hideEdit = false, string $editRoute = '', string $showRoute = '',
        bool $hideMoreActions = false, bool $hideDuplicate = false, string $duplicateRoute = '', bool $hidePrint = false, string $printRoute = '',
        bool $hideShare = false, string $shareRoute = '', string $signedUrl = '', bool $hideEmail = false, string $emailRoute = '', string $textEmail = '', bool $hidePdf = false, string $pdfRoute = '',
        bool $hideCancel = false, string $cancelledRoute = '', bool $hideCustomize = false, string $permissionCustomize = '', string $customizeRoute = '',
        bool $hideEnd = false, string $endRoute = '',
        bool $hideDelete = false, bool $checkReconciled = true, string $deleteRoute = '', string $textDeleteModal = '',
        bool $hideDivider1 = false, bool $hideDivider2 = false, bool $hideDivider3 = false, bool $hideDivider4 = false,
        string $accordionActive = '',
        bool $hideRecurringMessage = false, string $textRecurringType = '', bool $hideStatusMessage = false, string $textStatusMessage = '',
        bool $hideCreated = false, bool $hideSend = false, bool $hideMarkSent = false, string $markSentRoute = '', string $textMarkSent = '',
        bool $hideReceive = false, bool $hideMarkReceived = false, string $markReceivedRoute = '', string $textMarkReceived = '',
        bool $hideGetPaid = false,
        bool $hideRestore = false, bool $hideAddPayment = false, bool $hideAcceptPayment = false, string $transactionEmailRoute = '', string $transactionEmailTemplate = '',
        bool $hideMakePayment = false,
        bool $hideSchedule = false, bool $hideChildren = false,
        bool $hideAttachment = false, $attachment = [],
        $documentTemplate = '', $logo = '', $backgroundColor = '',
        bool $hideFooter = false, bool $hideCompanyLogo = false, bool $hideCompanyDetails = false,
        bool $hideCompanyName = false, bool $hideCompanyAddress = false, bool $hideCompanyTaxNumber = false, bool $hideCompanyPhone = false, bool $hideCompanyEmail = false, bool $hideContactInfo = false,
        bool $hideContactName = false, bool $hideContactAddress = false, bool $hideContactTaxNumber = false, bool $hideContactPhone = false, bool $hideContactEmail = false,
        bool $hideOrderNumber = false, bool $hideDocumentNumber = false, bool $hideIssuedAt = false, bool $hideDueAt = false,
        string $textDocumentTitle = '', string $textDocumentSubheading = '',
        string $textContactInfo = '', string $textDocumentNumber = '', string $textOrderNumber = '', string $textIssuedAt = '', string $textDueAt = '',
        bool $hideItems = false, bool $hideName = false, bool $hideDescription = false, bool $hideQuantity = false, bool $hidePrice = false, bool $hideDiscount = false, bool $hideAmount = false, bool $hideNote = false,
        string $textItems = '', string $textQuantity = '', string $textPrice = '', string $textAmount = ''
    ) {
        /* -- Main Start -- */
        $this->type = $type;
        $this->document = $document;
        $this->transactions = ($transactions) ? $transactions : $document->transactions;

        $this->permissionCreate = $this->getPermissionCreate($type, $permissionCreate);
        $this->permissionUpdate = $this->getPermissionUpdate($type, $permissionUpdate);
        $this->permissionDelete = $this->getPermissionDelete($type, $permissionDelete);
        /* -- Main End -- */

        /* -- Buttons Start -- */
        $this->textPage = $this->getTextPage($type, $textPage);
        $this->hideCreate = $hideCreate;
        $this->createRoute = $this->getCreateRoute($type, $createRoute);
        $this->textCreate = $this->getTextCreate($type, $textCreate);

        $this->hideButtonStatuses = $this->getHideButtonStatuses($type, $hideButtonStatuses);
        $this->hideEdit = $hideEdit;
        $this->editRoute = $this->getEditRoute($type, $editRoute);
        $this->showRoute = $this->getShowRoute($type, $showRoute);

        $this->hideMoreActions = $hideMoreActions;
        $this->hideDuplicate = $hideDuplicate;
        $this->duplicateRoute = $this->getDuplicateRoute($type, $duplicateRoute);

        $this->hidePrint = $hidePrint;
        $this->printRoute = $this->getPrintRoute($type, $printRoute);

        $this->hideShare = $hideShare;
        $this->shareRoute = $this->getShareRoute($type, $shareRoute);
        $this->signedUrl = $this->getSignedUrl($type, $signedUrl);

        $this->hideEmail = $hideEmail;
        $this->emailRoute = $this->getEmailRoute($type, $emailRoute);
        $this->textEmail = $this->getTextEmail($type, $textEmail);

        $this->hidePdf = $hidePdf;
        $this->pdfRoute = $this->getPdfRoute($type, $pdfRoute);

        $this->hideCancel = $hideCancel;
        $this->cancelledRoute = $this->getCancelledRoute($type, $cancelledRoute);

        $this->hideCustomize = $hideCustomize;
        $this->permissionCustomize = $this->getPermissionCustomize($type, $permissionCustomize);
        $this->customizeRoute = $this->getCustomizeRoute($type, $customizeRoute);

        $this->hideEnd = $hideEnd;
        $this->endRoute = $this->getEndRoute($type, $endRoute);

        $this->hideDelete = $hideDelete;
        $this->checkReconciled = $checkReconciled;
        $this->deleteRoute = $this->getDeleteRoute($type, $deleteRoute);
        $this->textDeleteModal = $textDeleteModal;

        $this->hideDivider1 = $hideDivider1;
        $this->hideDivider2 = $hideDivider2;
        $this->hideDivider3 = $hideDivider3;
        $this->hideDivider4 = $hideDivider4;
        /* -- Buttons End -- */

        /* -- Content Start -- */
        $this->accordionActive = $this->getAccordionActive($type, $accordionActive);
        $this->hideRecurringMessage = $hideRecurringMessage;
        $this->textRecurringType = $this->getTextRecurringType($type, $textRecurringType);

        $this->hideStatusMessage = $hideStatusMessage;
        $this->textStatusMessage = $this->getTextStatusMessage($type, $textStatusMessage);

        $this->hideCreated = $hideCreated;

        $this->hideSend = $hideSend;
        $this->hideMarkSent = $hideMarkSent;
        $this->textMarkSent = $this->getTextMarkSent($type, $textMarkSent);
        $this->markSentRoute = $this->getMarkSentRoute($type, $markSentRoute);

        $this->hideReceive = $hideReceive;
        $this->hideMarkReceived = $hideMarkReceived;
        $this->textMarkReceived = $this->getTextMarkReceived($type, $textMarkReceived);
        $this->markReceivedRoute = $this->getMarkReceivedRoute($type, $markReceivedRoute);

        $this->hideGetPaid = $hideGetPaid;

        $this->hideAddPayment = $hideAddPayment;
        $this->hideAcceptPayment = $hideAcceptPayment;

        $this->transactionEmailRoute = $this->getTransactionEmailRoute($type, $transactionEmailRoute);
        $this->transactionEmailTemplate = $this->getTransactionEmailTemplate($type, $transactionEmailTemplate);

        $this->hideRestore = $this->getHideRestore($hideRestore);

        $this->hideMakePayment = $hideMakePayment;

        $this->hideSchedule = $hideSchedule;
        $this->hideChildren = $hideChildren;

        $this->hideAttachment = $hideAttachment;
        $this->attachment = '';

        if (! empty($attachment)) {
            $this->attachment = $attachment;
        } else if (! empty($document)) {
            $this->attachment = $document->attachment;
        }
        /* -- Content End -- */

        /* -- Template Start -- */
        $this->documentTemplate = $this->getDocumentTemplate($type, $documentTemplate);
        $this->logo = $this->getLogo($logo);
        $this->backgroundColor = $this->getBackgroundColor($type, $backgroundColor);

        $this->hideFooter = $hideFooter;
        $this->hideCompanyLogo = $hideCompanyLogo;
        $this->hideCompanyDetails = $hideCompanyDetails;
        $this->hideCompanyName = $hideCompanyName;
        $this->hideCompanyAddress = $hideCompanyAddress;
        $this->hideCompanyTaxNumber = $hideCompanyTaxNumber;
        $this->hideCompanyPhone = $hideCompanyPhone;
        $this->hideCompanyEmail = $hideCompanyEmail;
        $this->hideContactInfo = $hideContactInfo;
        $this->hideContactName = $hideContactName;
        $this->hideContactAddress = $hideContactAddress;
        $this->hideContactTaxNumber = $hideContactTaxNumber;
        $this->hideContactPhone = $hideContactPhone;
        $this->hideContactEmail = $hideContactEmail;
        $this->hideOrderNumber = $hideOrderNumber;
        $this->hideDocumentNumber = $hideDocumentNumber;
        $this->hideIssuedAt = $hideIssuedAt;
        $this->hideDueAt = $hideDueAt;

        $this->textDocumentTitle = $this->getTextDocumentTitle($type, $textDocumentTitle);
        $this->textDocumentSubheading = $this->gettextDocumentSubheading($type, $textDocumentSubheading);
        $this->textContactInfo = $this->getTextContactInfo($type, $textContactInfo);
        $this->textIssuedAt = $this->getTextIssuedAt($type, $textIssuedAt);
        $this->textDocumentNumber = $this->getTextDocumentNumber($type, $textDocumentNumber);
        $this->textDueAt = $this->getTextDueAt($type, $textDueAt);
        $this->textOrderNumber = $this->getTextOrderNumber($type, $textOrderNumber);

        $this->hideItems = $this->getHideItems($type, $hideItems, $hideName, $hideDescription);
        $this->hideName = $this->getHideName($type, $hideName);
        $this->hideDescription = $this->getHideDescription($type, $hideDescription);
        $this->hideQuantity = $this->getHideQuantity($type, $hideQuantity);
        $this->hidePrice = $this->getHidePrice($type, $hidePrice);
        $this->hideDiscount = $this->getHideDiscount($type, $hideDiscount);
        $this->hideAmount = $this->getHideAmount($type, $hideAmount);
        $this->hideNote = $hideNote;

        $this->textItems = $this->getTextItems($type, $textItems);
        $this->textQuantity = $this->getTextQuantity($type, $textQuantity);
        $this->textPrice = $this->getTextPrice($type, $textPrice);
        $this->textAmount = $this->getTextAmount($type, $textAmount);
        /* -- Template End -- */

        // Set Parent data
        $this->setParentData();
    }

    protected function getTextCreate($type, $textCreate)
    {
        if (! empty($textCreate)) {
            return $textCreate;
        }

        return trans('general.new') . ' ' . ucfirst($type);
    }

    protected function getHideButtonStatuses($type, $hideButtonStatuses)
    {
        if (! empty($hideButtonStatuses)) {
            return $hideButtonStatuses;
        }

        $hideButtonStatuses = ['paid', 'cancelled'];

        if ($button_statuses = config('type.' . static::OBJECT_TYPE . '.' . $type . '.button_statuses')) {
            $hideButtonStatuses = $button_statuses;
        }

        return $hideButtonStatuses;
    }

    protected function getPrintRoute($type, $printRoute)
    {
        if (! empty($printRoute)) {
            return $printRoute;
        }

        //example route parameter.
        $parameter = 1;

        $route = $this->getRouteFromConfig($type, 'print', $parameter);

        if (! empty($route)) {
            return $route;
        }

        return 'invoices.print';
    }

    protected function getShareRoute($type, $shareRoute)
    {
        if (! empty($shareRoute)) {
            return $shareRoute;
        }

        //example route parameter.
        $parameter = 1;

        $route = $this->getRouteFromConfig($type, 'share', $parameter, true);

        if (! empty($route)) {
            return $route;
        }

        return 'modals.invoices.share.create';
    }

    protected function getSignedUrl($type, $signedUrl)
    {
        if (! empty($signedUrl)) {
            return $signedUrl;
        }

        $page = config('type.' . static::OBJECT_TYPE . '.' . $type . '.route.prefix');
        $alias = config('type.' . static::OBJECT_TYPE . '.' . $type . '.alias');

        $route = '';

        if (! empty($alias)) {
            $route .= $alias . '.';
        }

        $route .= 'signed.' . $page . '.show';

        try {
            route($route, [$this->document->id, 'company_id' => company_id()]);

            $signedUrl = URL::signedRoute($route, [$this->document->id]);
        } catch (\Exception $e) {
            $signedUrl = URL::signedRoute('signed.invoices.show', [$this->document->id]);
        }

        return $signedUrl;
    }

    protected function getEmailRoute($type, $emailRoute)
    {
        if (! empty($emailRoute)) {
            return $emailRoute;
        }

        //example route parameter.
        $parameter = 1;

        $route = $this->getRouteFromConfig($type, 'emails.create', $parameter, true);

        if (! empty($route)) {
            return $route;
        }

        return 'modals.invoices.emails.create';
    }

    protected function getTextEmail($type, $textEmail)
    {
        if (! empty($textEmail)) {
            return $textEmail;
        }

        $translation = $this->getTextFromConfig($type, 'send_mail', 'send_mail');

        if (! empty($translation)) {
            return $translation;
        }

        return 'invoices.send_mail';
    }

    protected function getPdfRoute($type, $pdfRoute)
    {
        if (! empty($pdfRoute)) {
            return $pdfRoute;
        }

        //example route parameter.
        $parameter = 1;

        $route = $this->getRouteFromConfig($type, 'pdf', $parameter);

        if (! empty($route)) {
            return $route;
        }

        return 'invoices.pdf';
    }

    protected function getCancelledRoute($type, $cancelledRoute)
    {
        if (! empty($cancelledRoute)) {
            return $cancelledRoute;
        }

        //example route parameter.
        $parameter = 1;

        $route = $this->getRouteFromConfig($type, 'cancelled', $parameter);

        if (! empty($route)) {
            return $route;
        }

        return 'invoices.cancelled';
    }

    protected function getPermissionCustomize($type, $permissionCustomize)
    {
        if (! empty($permissionCustomize)) {
            return $permissionCustomize;
        }

        $permissionUpdate = $this->getPermissionFromConfig($type, 'update');

        return $permissionUpdate;
    }

    protected function getCustomizeRoute($type, $customizeRoute)
    {
        if (!empty($customizeRoute)) {
            return $customizeRoute;
        }

        $route = '';

        $alias = config('type.' . static::OBJECT_TYPE . '.' . $type . '.alias');

        if (!empty($alias)) {
            $route .= $alias . '.';
        }

        $route .= 'settings.' . $type . '.edit';

        try {
            route($route);
        } catch (\Exception $e) {
            $route = 'settings.invoice.edit';
        }

        return $route;
    }

    protected function getEndRoute($type, $endButton)
    {
        if (!empty($endButton)) {
            return $endButton;
        }

        //example route parameter.
        $parameter = 1;

        $route = $this->getRouteFromConfig($type, 'end', $parameter);

        if (!empty($route)) {
            return $route;
        }

        return 'recurring-invoices.end';
    }

    protected function getAccordionActive($type, $accordionActive)
    {
        if (! empty($accordionActive)) {
            return $accordionActive;
        }

        $status_workflow = $alias = config('type.' . static::OBJECT_TYPE . '.' . $type . '.status_workflow');

        $status = false;

        if (! empty($status_workflow[$this->document->status])) {
            $status = $status_workflow[$this->document->status];
        }

        return $status;
    }

    protected function getTextRecurringType($type, $textRecurringType)
    {
        if (! empty($textRecurringType)) {
            return $textRecurringType;
        }

        $default_key = config('type.' . static::OBJECT_TYPE . '.' . $type . '.translation.prefix');

        $translation = $this->getTextFromConfig($type, 'recurring_tye', $default_key);

        if (! empty($translation)) {
            return $translation;
        }

        return 'general.invoices';
    }

    protected function getTextStatusMessage($type, $textStatusMessage)
    {
        if (! empty($textStatusMessage)) {
            return $textStatusMessage;
        }

        $default_key = 'messages.draft';

        $translation = $this->getTextFromConfig($type, 'status_message', $default_key);

        if (! empty($translation)) {
            return $translation;
        }

        return 'invoices.messages.draft';
    }

    protected function getTextMarkSent($type, $textMarkSent)
    {
        if (! empty($textMarkSent)) {
            return $textMarkSent;
        }

        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $default_key = 'mark_received';
                break;
            default:
                $default_key = 'mark_sent';
                break;
        }

        $translation = $this->getTextFromConfig($type, 'mark_sent', $default_key);

        if (! empty($translation)) {
            return $translation;
        }

        return 'invoices.mark_sent';
    }

    protected function getMarkSentRoute($type, $markSentRoute)
    {
        if (! empty($markSentRoute)) {
            return $markSentRoute;
        }

        //example route parameter.
        $parameter = 1;

        $route = $this->getRouteFromConfig($type, 'sent', $parameter);

        if (!empty($route)) {
            return $route;
        }

        return 'invoices.sent';
    }

    protected function getTextMarkReceived($type, $textMarkReceived)
    {
        if (! empty($textMarkReceived)) {
            return $textMarkReceived;
        }

        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $default_key = 'mark_received';
                break;
            default:
                $default_key = 'mark_sent';
                break;
        }

        $translation = $this->getTextFromConfig($type, 'mark_received', $default_key);

        if (! empty($translation)) {
            return $translation;
        }

        return 'bills.mark_received';
    }

    protected function getMarkReceivedRoute($type, $markReceivedRoute)
    {
        if (! empty($markReceivedRoute)) {
            return $markReceivedRoute;
        }

        //example route parameter.
        $parameter = 1;

        $route = $this->getRouteFromConfig($type, 'received', $parameter);

        if (!empty($route)) {
            return $route;
        }

        return 'bills.received';
    }

    protected function getTransactionEmailRoute($type, $transactionEmailRoute)
    {
        if (! empty($transactionEmailRoute)) {
            return $transactionEmailRoute;
        }

        //example route parameter.
        $parameter = 1;

        $route = $this->getRouteFromConfig($type, 'transaction_email', $parameter);

        if (! empty($route)) {
            return $route;
        }

        return 'modals.transactions.emails.create';
    }

    protected function getTransactionEmailTemplate($type, $transactionEmailTemplate)
    {
        if (! empty($transactionEmailTemplate)) {
            return $transactionEmailTemplate;
        }

        return config('type.' . static::OBJECT_TYPE . '.' . $type . '.transaction.email_template', false);
    }

    protected function getHideRestore($hideRestore)
    {
        if (! empty($hideRestore)) {
            return $hideRestore;
        }

        $hideRestore = true;

        if ($this->document->status == 'cancelled') {
            $hideRestore = false;
        }

        return $hideRestore;
    }

    protected function getDocumentTemplate($type, $documentTemplate)
    {
        if (! empty($documentTemplate)) {
            return $documentTemplate;
        }

        if ($template = config('type.' . static::OBJECT_TYPE . '.' . $type . '.template', false)) {
            return $template;
        }

        $documentTemplate =  setting($this->getDocumentSettingKey($type, 'template'), 'default');

        return $documentTemplate;
    }

    protected function getLogo($logo)
    {
        if (! empty($logo)) {
            return $logo;
        }

        $media_id = (! empty($this->document->contact->logo) && ! empty($this->document->contact->logo->id)) ? $this->document->contact->logo->id : setting('company.logo');

        $media = Media::find($media_id);

        if (! empty($media)) {
            $path = $media->getDiskPath();

            if (! $media->fileExists()) {
                return $logo;
            }
        } else {
            $path = base_path('public/img/company.png');
        }

        try {
            $image = Image::cache(function($image) use ($media, $path) {
                $width = setting('invoice.logo_size_width');
                $height = setting('invoice.logo_size_height');

                if ($media) {
                    $image->make($media->contents())->resize($width, $height)->encode();
                } else {
                    $image->make($path)->resize($width, $height)->encode();
                }
            });
        } catch (NotReadableException | \Exception $e) {
            Log::info('Company ID: ' . company_id() . ' components/documentshow.php exception.');
            Log::info($e->getMessage());

            $path = base_path('public/img/company.png');

            $image = Image::cache(function($image) use ($path) {
                $width = setting('invoice.logo_size_width');
                $height = setting('invoice.logo_size_height');

                $image->make($path)->resize($width, $height)->encode();
            });
        }

        if (empty($image)) {
            return $logo;
        }

        $extension = File::extension($path);

        return 'data:image/' . $extension . ';base64,' . base64_encode($image);
    }

    protected function getBackgroundColor($type, $backgroundColor)
    {
        if (! empty($backgroundColor)) {
            return $backgroundColor;
        }

        // checking setting color
        $key = $this->getDocumentSettingKey($type, 'color');

        if (! empty(setting($key))) {
            $backgroundColor = setting($key);
        }

        // checking config color
        if (empty($backgroundColor) && $background_color = config('type.document.' . $type . '.color', false)) {
            $backgroundColor = $background_color;
        }

        // set default color
        if (empty($backgroundColor)) {
            $backgroundColor = '#55588b';
        }

        return $this->getHexCodeOfTailwindClass($backgroundColor);
    }

    protected function getTextDocumentTitle($type, $textDocumentTitle)
    {
        if (! empty($textDocumentTitle)) {
            return $textDocumentTitle;
        }

        $key = $this->getDocumentSettingKey($type, 'title');

        if (! empty(setting($key))) {
            return setting($key);
        }

        $translation = $this->getTextFromConfig($type, 'document_title', Str::plural($type));

        if (! empty($translation)) {
            return trans_choice($translation, 1);
        }

        return setting('invoice.title');
    }

    protected function getTextDocumentSubheading($type, $textDocumentSubheading)
    {
        if (! empty($textDocumentSubheading)) {
            return $textDocumentSubheading;
        }

        $key = $this->getDocumentSettingKey($type, 'subheading');

        if (!empty(setting($key))) {
            return setting($key);
        }

        $translation = $this->getTextFromConfig($type, 'document_subheading', 'subheading');

        if (! empty($translation)) {
            return trans($translation);
        }

        return false;
    }

    protected function getTextDocumentNumber($type, $textDocumentNumber)
    {
        if (! empty($textDocumentNumber)) {
            return $textDocumentNumber;
        }

        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $default_key = 'bill_number';
                break;
            default:
                $default_key = 'invoice_number';
                break;
        }

        $translation = $this->getTextFromConfig($type, 'document_number', $default_key);

        if (! empty($translation)) {
            return $translation;
        }

        return 'general.numbers';
    }

    protected function getTextOrderNumber($type, $textOrderNumber)
    {
        if (! empty($textOrderNumber)) {
            return $textOrderNumber;
        }

        $translation = $this->getTextFromConfig($type, 'order_number');

        if (! empty($translation)) {
            return $translation;
        }

        return 'invoices.order_number';
    }

    protected function getTextContactInfo($type, $textContactInfo)
    {
        if (! empty($textContactInfo)) {
            return $textContactInfo;
        }

        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $default_key = 'bill_from';
                break;
            default:
                $default_key = 'bill_to';
                break;
        }

        $translation = $this->getTextFromConfig($type, 'contact_info', $default_key);

        if (! empty($translation)) {
            return $translation;
        }

        return 'invoices.bill_to';
    }

    protected function getTextIssuedAt($type, $textIssuedAt)
    {
        if (! empty($textIssuedAt)) {
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

        if (! empty($translation)) {
            return $translation;
        }

        return 'invoices.invoice_date';
    }

    protected function getTextDueAt($type, $textDueAt)
    {
        if (! empty($textDueAt)) {
            return $textDueAt;
        }

        $translation = $this->getTextFromConfig($type, 'due_at', 'due_date');

        if (! empty($translation)) {
            return $translation;
        }

        return 'invoices.due_date';
    }

    protected function getTextItems($type, $textItems)
    {
        if (! empty($textItems)) {
            return $textItems;
        }

        // if you use settting translation
        if (setting($this->getDocumentSettingKey($type, 'item_name'), 'items') == 'custom') {
            if (empty($textItems = setting($this->getDocumentSettingKey($type, 'item_name_input')))) {
                $textItems = 'general.items';
            }

            return $textItems;
        }

        $translation = $this->getTextFromConfig($type, 'items');

        if (! empty($translation)) {
            return $translation;
        }

        return 'general.items';
    }

    protected function getTextQuantity($type, $textQuantity)
    {
        if (! empty($textQuantity)) {
            return $textQuantity;
        }

        // if you use settting translation
        if (setting($this->getDocumentSettingKey($type, 'quantity_name'), 'quantity') === 'custom') {
            if (empty($textQuantity = setting($this->getDocumentSettingKey($type, 'quantity_name_input')))) {
                $textQuantity = 'invoices.quantity';
            }

            return $textQuantity;
        }

        $translation = $this->getTextFromConfig($type, 'quantity');

        if (! empty($translation)) {
            return $translation;
        }

        return 'invoices.quantity';
    }

    protected function getTextPrice($type, $textPrice)
    {
        if (!empty($textPrice)) {
            return $textPrice;
        }

        // if you use settting translation
        if (setting($this->getDocumentSettingKey($type, 'price_name'), 'price') === 'custom') {
            if (empty($textPrice = setting($this->getDocumentSettingKey($type, 'price_name_input')))) {
                $textPrice = 'invoices.price';
            }

            return $textPrice;
        }

        $translation = $this->getTextFromConfig($type, 'price');

        if (! empty($translation)) {
            return $translation;
        }

        return 'invoices.price';
    }

    protected function getTextAmount($type, $textAmount)
    {
        if (! empty($textAmount)) {
            return $textAmount;
        }

        $translation = $this->getTextFromConfig($type, 'amount');

        if (! empty($translation)) {
            return $translation;
        }

        return 'general.amount';
    }

    protected function getHideItems($type, $hideItems, $hideName, $hideDescription)
    {
        if (! empty($hideItems)) {
            return $hideItems;
        }

        $hide = $this->getHideFromConfig($type, 'items');

        if ($hide) {
            return $hide;
        }

        $hideItems = ($this->getHideName($type, $hideName) & $this->getHideDescription($type, $hideDescription)) ? true  : false;

        return $hideItems;
    }

    protected function getHideName($type, $hideName)
    {
        if (! empty($hideName)) {
            return $hideName;
        }

        // if you use settting translation
        if ($hideName = setting($this->getDocumentSettingKey($type, 'item_name'), false) && $hideName == 'hide') {
            return $hideName;
        }

        $hide = $this->getHideFromConfig($type, 'name');

        if ($hide) {
            return $hide;
        }

        // @todo what return value invoice or always false??
        return setting('invoice.item_name', $hideName) == 'hide' ? true : false;
    }

    protected function getHideDescription($type, $hideDescription)
    {
        if (! empty($hideDescription)) {
            return $hideDescription;
        }

        // if you use settting translation
        if ($hideDescription = setting($this->getDocumentSettingKey($type, 'hide_item_description'), false)) {
            return $hideDescription;
        }

        $hide = $this->getHideFromConfig($type, 'description');

        if ($hide) {
            return $hide;
        }

        // @todo what return value invoice or always false??
        return setting('invoice.hide_item_description', $hideDescription);
    }

    protected function getHideQuantity($type, $hideQuantity)
    {
        if (! empty($hideQuantity)) {
            return $hideQuantity;
        }

        // if you use settting translation
        if ($hideQuantity = setting($this->getDocumentSettingKey($type, 'hide_quantity'), false) && $hideQuantity == 'hide') {
            return $hideQuantity;
        }

        $hide = $this->getHideFromConfig($type, 'quantity');

        if ($hide) {
            return $hide;
        }

        // @todo what return value invoice or always false??
        return setting('invoice.quantity_name', $hideQuantity) == 'hide' ? true : false;
    }

    protected function getHidePrice($type, $hidePrice)
    {
        if (! empty($hidePrice)) {
            return $hidePrice;
        }

        // if you use settting translation
        if ($hidePrice = setting($this->getDocumentSettingKey($type, 'hide_price'), false) && $hidePrice == 'hide') {
            return $hidePrice;
        }

        $hide = $this->getHideFromConfig($type, 'price');

        if ($hide) {
            return $hide;
        }

        // @todo what return value invoice or always false??
        return setting('invoice.price_name', $hidePrice) == 'hide' ? true : false;
    }

    protected function getHideDiscount($type, $hideDiscount)
    {
        if (! empty($hideDiscount)) {
            return $hideDiscount;
        }

        // if you use settting translation
        if ($hideDiscount = setting($this->getDocumentSettingKey($type, 'hide_discount'), false)) {
            return $hideDiscount;
        }

        $hide = $this->getHideFromConfig($type, 'discount');

        if ($hide) {
            return $hide;
        }

        // @todo what return value invoice or always false??
        return setting('invoice.hide_discount', $hideDiscount);
    }

    protected function getHideAmount($type, $hideAmount)
    {
        if (! empty($hideAmount)) {
            return $hideAmount;
        }

        // if you use settting translation
        if ($hideAmount = setting($this->getDocumentSettingKey($type, 'hide_amount'), false)) {
            return $hideAmount;
        }

        $hide = $this->getHideFromConfig($type, 'amount');

        if ($hide) {
            return $hide;
        }

        // @todo what return value invoice or always false??
        return setting('invoice.hide_amount', $hideAmount);
    }
}
