<?php

namespace App\Abstracts\View\Components;

use App\Abstracts\View\Components\Document as Base;
use App\Traits\DateTime;
use App\Models\Common\Media;
use File;
use Image;
use Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

abstract class DocumentShow extends Base
{
    use DateTime;

    public $type;

    public $document;

    /** @var string */
    public $documentTemplate;

    /** @var string */
    public $logo;

    /** @var string */
    public $backGroundColor;

    /** @var string */
    public $signedUrl;

    public $histories;

    public $transactions;

    public $date_format;

    /** @var string */
    public $textRecurringType;

    /** @var string */
    public $textStatusMessage;

    /** @var string */
    public $textHistories;

    /** @var string */
    public $textHistoryStatus;

    /** @var string */
    public $checkButtonReconciled;

    /** @var string */
    public $checkButtonCancelled;

    /** @var string */
    public $routeButtonAddNew;

    /** @var string */
    public $routeButtonEdit;

    /** @var string */
    public $routeButtonDuplicate;

    /** @var string */
    public $routeButtonPrint;

    /** @var string */
    public $routeButtonPdf;

    /** @var string */
    public $routeButtonCancelled;

    /** @var string */
    public $routeButtonCustomize;

    /** @var string */
    public $routeButtonDelete;

    /** @var string */
    public $routeButtonSent;

    /** @var string */
    public $routeButtonReceived;

    /** @var string */
    public $routeButtonEmail;

    /** @var string */
    public $routeButtonPaid;

    /** @var string */
    public $permissionCreate;

    /** @var string */
    public $permissionUpdate;

    /** @var string */
    public $permissionDelete;

    /** @var string */
    public $permissionButtonCustomize;

    /** @var bool */
    public $hideButtonGroupDivider1;

    /** @var bool */
    public $hideButtonGroupDivider2;

    /** @var bool */
    public $hideButtonGroupDivider3;

    /** @var bool */
    public $hideButtonMoreActions;

    /** @var bool */
    public $hideButtonAddNew;

    /** @var bool */
    public $hideButtonEdit;

    /** @var bool */
    public $hideButtonDuplicate;

    /** @var bool */
    public $hideButtonPrint;

    /** @var bool */
    public $hideButtonPdf;

    /** @var bool */
    public $hideButtonCancel;

    /** @var bool */
    public $hideButtonCustomize;

    /** @var bool */
    public $hideButtonDelete;

    /** @var bool */
    public $hideButtonAddPayment;

    /** @var bool */
    public $hideHeader;

    /** @var bool */
    public $hideRecurringMessage;

    /** @var bool */
    public $hideStatusMessage;

    /** @var bool */
    public $hideTimeline;

    /** @var bool */
    public $hideFooter;

    /** @var bool */
    public $hideFooterHistories;

    /** @var bool */
    public $hideFooterTransactions;

    /** @var array */
    public $hideTimelineStatuses;

    /** @var bool */
    public $hideTimelineSent;

    /** @var bool */
    public $hideTimelinePaid;

    /** @var bool */
    public $hideButtonSent;

    /** @var bool */
    public $hideButtonReceived;

    /** @var bool */
    public $hideButtonEmail;

    /** @var bool */
    public $hideButtonShare;

    /** @var bool */
    public $hideButtonPaid;

    /** @var string */
    public $textHeaderContact;

    /** @var string */
    public $textHeaderAmount;

    /** @var string */
    public $textHeaderDueAt;

    /** @var bool */
    public $hideHeaderStatus;

    /** @var string */
    public $classHeaderContact;

    /** @var string */
    public $classHeaderAmount;

    /** @var string */
    public $classHeaderDueAt;

    /** @var bool */
    public $classHeaderStatus;

    /** @var bool */
    public $hideHeaderContact;

    /** @var bool */
    public $hideHeaderAmount;

    /** @var bool */
    public $hideHeaderDueAt;

    /** @var bool */
    public $hideTimelineCreate;

    /** @var string */
    public $textTimelineCreateTitle;

    /** @var string */
    public $textTimelineCreateMessage;

    /** @var string */
    public $textTimelineSentTitle;

    /** @var string */
    public $textTimelineSentStatusDraft;

    /** @var string */
    public $textTimelineSentStatusReceived;

    /** @var string */
    public $textTimelineSendStatusMail;

    /** @var string */
    public $textTimelineSentStatusMarkSent;

    /** @var string */
    public $textTimelineGetPaidTitle;

    /** @var string */
    public $textTimelineGetPaidStatusAwait;

    /** @var string */
    public $textTimelineGetPaidStatusPartiallyPaid;

    /** @var string */
    public $textTimelineGetPaidMarkPaid;

    /** @var string */
    public $textTimelineGetPaidAddPayment;

    public $hideCompanyLogo;

    public $hideCompanyDetails;

    /** @var bool */
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

    /** @var bool */
    public $hideNote;

    /** @var bool */
    public $hideAttachment;

    public $attachment;

    /** @var string */
    public $textDeleteModal;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $type, $document, $documentTemplate = '', $logo = '', $backGroundColor = '', string $signedUrl = '', $histories = [], $transactions = [],
        string $textRecurringType = '', string $textStatusMessage = '', string $textHistories = '', string $textHistoryStatus = '',
        string $routeButtonAddNew = '', string $routeButtonEdit = '', string $routeButtonDuplicate = '', string $routeButtonPrint = '', string $routeButtonPdf = '', string $routeButtonCancelled = '', string $routeButtonDelete = '', string $routeButtonCustomize = '', string $routeButtonSent = '',
        string $routeButtonReceived = '', string $routeButtonEmail = '', string $routeButtonPaid = '',
        bool $checkButtonReconciled = true, bool $checkButtonCancelled = true,
        string $permissionCreate = '', string $permissionUpdate = '', string $permissionDelete = '', string $permissionButtonCustomize = '',
        bool $hideButtonGroupDivider1 = false, bool $hideButtonGroupDivider2 = false, bool $hideButtonGroupDivider3 = false,
        bool $hideButtonMoreActions = false, bool $hideButtonAddNew = false, bool $hideButtonEdit = false, bool $hideButtonDuplicate = false, bool $hideButtonPrint = false, bool $hideButtonPdf = false, bool $hideButtonCancel = false, bool $hideButtonCustomize = false, bool $hideButtonDelete = false,
        bool $hideHeader = false,bool $hideRecurringMessage = false, bool $hideStatusMessage = false, bool $hideTimeline = false, bool $hideFooter = false, bool $hideFooterHistories = false, bool $hideFooterTransactions = false,
        array $hideTimelineStatuses = [], bool $hideTimelineSent = false, bool $hideTimelinePaid = false, bool $hideButtonSent = false, bool $hideButtonReceived = false, bool $hideButtonEmail = false, bool $hideButtonShare = false, bool $hideButtonPaid = false,
        string $textHeaderContact = '', string $textHeaderAmount = '', string $textHeaderDueAt = '',
        string $classHeaderStatus = '', string $classHeaderContact = '', string $classHeaderAmount = '', string $classHeaderDueAt = '',
        bool $hideHeaderStatus = false, bool $hideHeaderContact = false, bool $hideHeaderAmount = false, bool $hideHeaderDueAt = false,
        string $textTimelineCreateTitle = '', string $textTimelineCreateMessage = '', string $textTimelineSentTitle = '', string $textTimelineSentStatusDraft = '', string $textTimelineSentStatusMarkSent = '', string $textTimelineSentStatusReceived = '', string $textTimelineSendStatusMail = '',
        string $textTimelineGetPaidTitle = '', string $textTimelineGetPaidStatusAwait = '', string $textTimelineGetPaidStatusPartiallyPaid = '', string $textTimelineGetPaidMarkPaid = '', string $textTimelineGetPaidAddPayment = '',
        bool $hideTimelineCreate = false, bool $hideCompanyLogo = false, bool $hideCompanyDetails = false,
        bool $hideCompanyName = false, bool $hideCompanyAddress = false, bool $hideCompanyTaxNumber = false, bool $hideCompanyPhone = false, bool $hideCompanyEmail = false, bool $hideContactInfo = false,
        bool $hideContactName = false, bool $hideContactAddress = false, bool $hideContactTaxNumber = false, bool $hideContactPhone = false, bool $hideContactEmail = false,
        bool $hideOrderNumber = false, bool $hideDocumentNumber = false, bool $hideIssuedAt = false, bool $hideDueAt = false,
        string $textContactInfo = '', string $textDocumentNumber = '', string $textOrderNumber = '', string $textIssuedAt = '', string $textDueAt = '',
        bool $hideItems = false, bool $hideName = false, bool $hideDescription = false, bool $hideQuantity = false, bool $hidePrice = false, bool $hideDiscount = false, bool $hideAmount = false, bool $hideNote = false, bool $hideAttachment = false,
        string $textItems = '', string $textQuantity = '', string $textPrice = '', string $textAmount = '', $attachment = [],
        string $textDeleteModal = ''
    ) {
        $this->type = $type;
        $this->document = $document;
        $this->documentTemplate = $this->getDocumentTemplate($type, $documentTemplate);
        $this->logo = $this->getLogo($logo);
        $this->backGroundColor = $backGroundColor;
        $this->signedUrl = $this->getSignedUrl($type, $signedUrl);

        $this->histories = ($histories) ? $histories : $document->histories;
        $this->transactions = ($transactions) ? $transactions : $document->transactions;

        $this->date_format = $this->getCompanyDateFormat();
        $this->textRecurringType = $this->getTextRecurringType($type, $textRecurringType);
        $this->textStatusMessage = $this->getTextStatusMessage($type, $textStatusMessage);

        $this->textHistories = $this->getTextHistories($type, $textHistories);
        $this->textHistoryStatus = $this->getTextHistoryStatus($type, $textHistoryStatus);

        $this->checkButtonReconciled = $checkButtonReconciled;
        $this->checkButtonCancelled = $checkButtonCancelled;

        $this->routeButtonAddNew = $this->getRouteButtonAddNew($type, $routeButtonAddNew);
        $this->routeButtonEdit = $this->getRouteButtonEdit($type, $routeButtonEdit);
        $this->routeButtonDuplicate = $this->getRouteButtonDuplicate($type, $routeButtonDuplicate);
        $this->routeButtonPrint = $this->getRouteButtonPrint($type, $routeButtonPrint);
        $this->routeButtonPdf = $this->getRouteButtonPdf($type, $routeButtonPdf);
        $this->routeButtonCancelled = $this->getRouteButtonCancelled($type, $routeButtonCancelled);
        $this->routeButtonCustomize = $this->getRouteButtonCustomize($type, $routeButtonCustomize);
        $this->routeButtonDelete = $this->getRouteButtonDelete($type, $routeButtonDelete);
        $this->routeButtonPaid = $this->getRouteButtonPaid($type, $routeButtonPaid);

        $this->permissionCreate = $this->getPermissionCreate($type, $permissionCreate);
        $this->permissionUpdate = $this->getPermissionUpdate($type, $permissionUpdate);
        $this->permissionDelete = $this->getPermissionDelete($type, $permissionDelete);
        $this->permissionButtonCustomize = $this->getPermissionButtonCustomize($type, $permissionButtonCustomize);

        $this->hideButtonGroupDivider1 = $hideButtonGroupDivider1;
        $this->hideButtonGroupDivider2 = $hideButtonGroupDivider2;
        $this->hideButtonGroupDivider3 = $hideButtonGroupDivider3;

        $this->hideButtonMoreActions = $hideButtonMoreActions;
        $this->hideButtonAddNew = $hideButtonAddNew;
        $this->hideButtonEdit = $hideButtonEdit;
        $this->hideButtonDuplicate  = $hideButtonDuplicate;
        $this->hideButtonPrint  = $hideButtonPrint;
        $this->hideButtonPdf  = $hideButtonPdf;
        $this->hideButtonCancel = $hideButtonCancel;
        $this->hideButtonCustomize = $hideButtonCustomize;
        $this->hideButtonDelete = $hideButtonDelete;

        $this->hideHeader = $hideHeader;
        $this->hideRecurringMessage = $hideRecurringMessage;
        $this->hideStatusMessage = $hideStatusMessage;
        $this->hideTimeline = $hideTimeline;
        $this->hideFooter = $hideFooter;
        $this->hideFooterHistories = $hideFooterHistories;
        $this->hideFooterTransactions = $hideFooterTransactions;

        $this->classHeaderStatus = $this->getClassHeaderStatus($type, $classHeaderStatus);
        $this->classHeaderContact = $this->getClassHeaderContact($type, $classHeaderContact);
        $this->classHeaderAmount = $this->getClassHeaderAmount($type, $classHeaderAmount);
        $this->classHeaderDueAt = $this->getClassHeaderDueAt($type, $classHeaderDueAt);

        $this->hideHeaderStatus = $hideHeaderStatus;
        $this->hideHeaderContact = $hideHeaderContact;
        $this->hideHeaderAmount = $hideHeaderAmount;
        $this->hideHeaderDueAt = $hideHeaderDueAt;

        $this->textHeaderContact = $this->getTextHeaderContact($type, $textHeaderContact);
        $this->textHeaderAmount = $this->getTextHeaderAmount($type, $textHeaderAmount);
        $this->textHeaderDueAt = $this->getTextHeaderDueAt($type, $textHeaderDueAt);

        $this->hideTimelineStatuses = $this->getTimelineStatuses($type, $hideTimelineStatuses);

        $this->hideTimelineCreate = $hideTimelineCreate;
        $this->hideTimelineSent = $hideTimelineSent;
        $this->hideTimelinePaid = $hideTimelinePaid;
        $this->hideButtonSent = $hideButtonSent;
        $this->hideButtonReceived = $hideButtonReceived;
        $this->hideButtonEmail = $hideButtonEmail;
        $this->hideButtonShare = $hideButtonShare;
        $this->hideButtonPaid = $hideButtonPaid;

        $this->textTimelineCreateTitle = $this->getTextTimelineCreateTitle($type, $textTimelineCreateTitle);
        $this->textTimelineCreateMessage = $this->getTextTimelineCreateMessage($type, $textTimelineCreateMessage);
        $this->textTimelineSentTitle = $this->getTextTimelineSentTitle($type, $textTimelineSentTitle);
        $this->textTimelineSentStatusDraft = $this->getTextTimelineSentStatusDraft($type, $textTimelineSentStatusDraft);
        $this->textTimelineSentStatusMarkSent = $this->getTextTimelineSentStatusMarkSent($type, $textTimelineSentStatusMarkSent);
        $this->textTimelineSentStatusReceived = $this->getTextTimelineSentStatusReceived($type, $textTimelineSentStatusReceived);
        $this->textTimelineSendStatusMail = $this->getTextTimelineSendStatusMail($type, $textTimelineSendStatusMail);
        $this->textTimelineGetPaidTitle = $this->getTextTimelineGetPaidTitle($type, $textTimelineGetPaidTitle);
        $this->textTimelineGetPaidStatusAwait = $this->getTextTimelineGetPaidStatusAwait($type, $textTimelineGetPaidStatusAwait);
        $this->textTimelineGetPaidStatusPartiallyPaid = $this->getTextTimelineGetPaidStatusPartiallyPaid($type, $textTimelineGetPaidStatusPartiallyPaid);
        $this->textTimelineGetPaidMarkPaid = $this->getTextTimelineGetPaidMarkPaid($type, $textTimelineGetPaidMarkPaid);
        $this->textTimelineGetPaidAddPayment = $this->getTextTimelineGetPaidAddPayment($type, $textTimelineGetPaidAddPayment);

        $this->permissionUpdate = $this->getPermissionUpdate($type, $permissionUpdate);

        $this->routeButtonSent = $this->getRouteButtonSent($type, $routeButtonSent);
        $this->routeButtonReceived = $this->getRouteButtonReceived($type, $routeButtonReceived);
        $this->routeButtonEmail = $this->getRouteButtonEmail($type, $routeButtonEmail);

        $this->hideCompanyDetails = $hideCompanyDetails;
        $this->hideCompanyLogo = $hideCompanyLogo;
        $this->hideCompanyName = $hideCompanyName;
        $this->hideContactAddress = $hideContactAddress;
        $this->hideContactTaxNumber = $hideContactTaxNumber;
        $this->hideContactPhone = $hideContactPhone;
        $this->hideContactEmail = $hideContactEmail;
        $this->hideOrderNumber = $hideOrderNumber;
        $this->hideDocumentNumber = $hideDocumentNumber;
        $this->hideOrderNumber = $hideOrderNumber;
        $this->hideIssuedAt = $hideIssuedAt;
        $this->hideDueAt = $hideDueAt;

        $this->textContactInfo = $textContactInfo;
        $this->textIssuedAt = $textIssuedAt;
        $this->textDocumentNumber = $textDocumentNumber;
        $this->textDueAt = $textDueAt;
        $this->textOrderNumber = $textOrderNumber;

        $this->hideItems = $this->getHideItems($type, $hideItems, $hideName, $hideDescription);
        $this->hideName = $this->getHideName($type, $hideName);
        $this->hideDescription = $this->getHideDescription($type, $hideDescription);
        $this->hideQuantity = $this->getHideQuantity($type, $hideQuantity);
        $this->hidePrice = $this->getHidePrice($type, $hidePrice);
        $this->hideDiscount = $this->getHideDiscount($type, $hideDiscount);
        $this->hideAmount = $this->getHideAmount($type, $hideAmount);
        $this->hideNote = $hideNote;
        $this->hideAttachment = $hideAttachment;

        $this->attachment = $attachment;

        $this->textItems = $textItems;
        $this->textQuantity = $textQuantity;
        $this->textPrice = $textPrice;
        $this->textAmount = $textAmount;

        $this->textDeleteModal = $textDeleteModal;
    }

    protected function getTextRecurringType($type, $textRecurringType)
    {
        if (!empty($textRecurringType)) {
            return $textRecurringType;
        }

        $default_key = config('type.' . $type . '.translation.prefix');

        $translation = $this->getTextFromConfig($type, 'recurring_tye', $default_key);

        if (!empty($translation)) {
            return $translation;
        }

        return 'general.invoices';
    }

    protected function getTextStatusMessage($type, $textStatusMessage)
    {
        if (!empty($textStatusMessage)) {
            return $textStatusMessage;
        }

        $default_key = config('type.' . $type . '.translation.prefix') . '.messages.draft';

        $translation = $this->getTextFromConfig($type, 'status_message', $default_key);

        if (!empty($translation)) {
            return $translation;
        }

        return 'invoices.messages.draft';
    }

    protected function getDocumentTemplate($type, $documentTemplate)
    {
        if (!empty($documentTemplate)) {
            return $documentTemplate;
        }

        $documentTemplate = 'default';

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $documentTemplate =  setting('invoice.template', 'default');
                break;
        }

        return $documentTemplate;
    }

    protected function getLogo($logo)
    {
        if (!empty($logo)) {
            return $logo;
        }

        $media = Media::find(setting('company.logo'));

        if (!empty($media)) {
            $path = Storage::path($media->getDiskPath());

            if (!is_file($path)) {
                return $logo;
            }
        } else {
            $path = base_path('public/img/company.png');
        }

        $image = Image::cache(function($image) use ($path) {
            $width = $height = setting('invoice.logo_size', 128);

            $image->make($path)->resize($width, $height)->encode();
        });

        if (empty($image)) {
            return $logo;
        }

        $extension = File::extension($path);

        return 'data:image/' . $extension . ';base64,' . base64_encode($image);
    }

    protected function getSignedUrl($type, $signedUrl)
    {
        if (!empty($signedUrl)) {
            return $signedUrl;
        }

        $page = config("type.{$type}.route.prefix");

        $route = 'signed.' . $page . '.show';

        try {
            route($route, [$this->document->id, 'company_id' => session('company_id')]);

            $signedUrl = URL::signedRoute($route, [$this->document->id, 'company_id' => session('company_id')]);
        } catch (\Exception $e) {
            $route = '';
        }

        return $signedUrl;
    }

    protected function getTextHistories($type, $textHistories)
    {
        if (!empty($textHistories)) {
            return $textHistories;
        }

        $default_key = config('type.' . $type . '.translation.prefix') . '.histories';

        $translation = $this->getTextFromConfig($type, 'histories', $default_key);

        if (!empty($translation)) {
            return $translation;
        }

        return 'invoices.histories';
    }

    protected function getTextHistoryStatus($type, $textHistoryStatus)
    {
        if (!empty($textHistoryStatus)) {
            return $textHistoryStatus;
        }

        $default_key = config('type.' . $type . '.translation.prefix') . '.statuses.';

        $translation = $this->getTextFromConfig($type, 'history_status', $default_key);

        if (!empty($translation)) {
            return $translation;
        }

        return 'invoices.statuses.';
    }

    protected function getRouteButtonAddNew($type, $routeButtonAddNew)
    {
        if (!empty($routeButtonAddNew)) {
            return $routeButtonAddNew;
        }

        $page = config("type.{$type}.route.prefix");

        $route = $page . '.create';

        try {
            route($route);
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

        $page = config("type.{$type}.route.prefix");

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

        $page = config("type.{$type}.route.prefix");

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

    protected function getRouteButtonPrint($type, $routeButtonPrint)
    {
        if (!empty($routeButtonPrint)) {
            return $routeButtonPrint;
        }

        $page = config("type.{$type}.route.prefix");

        $route = $page . '.print';

        try {
            //example route parameter.
            $parameter = 1;

            route($route, $parameter);
        } catch (\Exception $e) {
            $route = '';
        }

        return $route;
    }

    protected function getRouteButtonPdf($type, $routeButtonPdf)
    {
        if (!empty($routeButtonPdf)) {
            return $routeButtonPdf;
        }

        $page = config("type.{$type}.route.prefix");

        $route = $page . '.pdf';

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

        $page = config("type.{$type}.route.prefix");

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

    protected function getRouteButtonCustomize($type, $routeButtonCustomize)
    {
        if (!empty($routeButtonCustomize)) {
            return $routeButtonCustomize;
        }

        $route = 'settings.' . $type . '.edit';

        try {
            route($route);
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

        $page = config("type.{$type}.route.prefix");

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

    protected function getRouteButtonPaid($type, $routeButtonPaid)
    {
        if (!empty($routeButtonPaid)) {
            return $routeButtonPaid;
        }

        $page = config("type.{$type}.route.prefix");

        $route = $page . '.paid';

        try {
            //example route parameter.
            $parameter = 1;

            route($route, $parameter);
        } catch (\Exception $e) {
            $route = '';
        }

        return $route;
    }

    protected function getRouteButtonSent($type, $routeButtonSent)
    {
        if (!empty($routeButtonSent)) {
            return $routeButtonSent;
        }

        $page = config("type.{$type}.route.prefix");

        $route = $page . '.sent';

        try {
            //example route parameter.
            $parameter = 1;

            route($route, $parameter);
        } catch (\Exception $e) {
            $route = '';
        }

        return $route;
    }

    protected function getRouteButtonReceived($type, $routeButtonReceived)
    {
        if (!empty($routeButtonReceived)) {
            return $routeButtonReceived;
        }

        $page = config("type.{$type}.route.prefix");

        $route = $page . '.received';

        try {
            //example route parameter.
            $parameter = 1;

            route($route, $parameter);
        } catch (\Exception $e) {
            $route = '';
        }

        return $route;
    }

    protected function getRouteButtonEmail($type, $routeButtonEmail)
    {
        if (!empty($routeButtonEmail)) {
            return $routeButtonEmail;
        }

        $page = config("type.{$type}.route.prefix");

        $route = $page . '.email';

        try {
            //example route parameter.
            $parameter = 1;

            route($route, $parameter);
        } catch (\Exception $e) {
            $route = '';
        }

        return $route;
    }

    protected function getPermissionCreate($type, $permissionCreate)
    {
        if (!empty($permissionCreate)) {
            return $permissionCreate;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $permissionCreate = 'create-sales-invoices';
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $permissionCreate = 'create-purchases-bills';
                break;
        }

        return $permissionCreate;
    }

    protected function getPermissionUpdate($type, $permissionUpdate)
    {
        if (!empty($permissionUpdate)) {
            return $permissionUpdate;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $permissionUpdate = 'update-sales-invoices';
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $permissionUpdate = 'update-purchases-bills';
                break;
        }

        return $permissionUpdate;
    }

    protected function getPermissionDelete($type, $permissionDelete)
    {
        if (!empty($permissionDelete)) {
            return $permissionDelete;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $permissionDelete = 'delete-sales-invoices';
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $permissionDelete = 'delete-purchases-bills';
                break;
        }

        return $permissionDelete;
    }

    protected function getPermissionButtonCustomize($type, $permissionButtonCustomize)
    {
        if (!empty($permissionButtonCustomize)) {
            return $permissionButtonCustomize;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $permissionButtonCustomize = 'update-sales-invoices';
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $permissionButtonCustomize = 'update-purchases-bills';
                break;
        }

        return $permissionButtonCustomize;
    }

    protected function getTextHeaderContact($type, $textHeaderContact)
    {
        if (!empty($textHeaderContact)) {
            return $textHeaderContact;
        }

        $default_key = Str::plural(config('type.' . $type . '.contact_type'), 2);

        $translation = $this->getTextFromConfig($type, 'header_contact', $default_key, 'trans_choice');

        if (!empty($translation)) {
            return $translation;
        }

        return 'general.customers';
    }

    protected function getTextHeaderAmount($type, $textHeaderAmount)
    {
        if (!empty($textHeaderAmount)) {
            return $textHeaderAmount;
        }

        $translation = $this->getTextFromConfig($type, 'header_amount', 'amount_due');

        if (!empty($translation)) {
            return $translation;
        }

        return 'general.amount_due';
    }

    protected function getTextHeaderDueAt($type, $textHeaderDueAt)
    {
        if (!empty($textHeaderDueAt)) {
            return $textHeaderDueAt;
        }

        $translation = $this->getTextFromConfig($type, 'header_due_at', 'due_on');

        if (!empty($translation)) {
            return $translation;
        }

        return 'general.due_on';
    }

    protected function getClassHeaderStatus($type, $classHeaderStatus)
    {
        if (!empty($classHeaderStatus)) {
            return $classHeaderStatus;
        }

        return 'col-md-2';
    }

    protected function getClassHeaderContact($type, $classHeaderContact)
    {
        if (!empty($classHeaderContact)) {
            return $classHeaderContact;
        }

        return 'col-md-6';
    }

    protected function getClassHeaderAmount($type, $classHeaderAmount)
    {
        if (!empty($classHeaderAmount)) {
            return $classHeaderAmount;
        }

        return 'col-md-2';
    }

    protected function getClassHeaderDueAt($type, $classHeaderDueAt)
    {
        if (!empty($classHeaderDueAt)) {
            return $classHeaderDueAt;
        }

        return 'col-md-2';
    }

    protected function getTimelineStatuses($type, $hideTimelineStatuses)
    {
        if (!empty($hideTimelineStatuses)) {
            return $hideTimelineStatuses;
        }

        $hideTimelineStatuses = ['paid', 'cancelled'];

        return $hideTimelineStatuses;
    }

    protected function getTextTimelineCreateTitle($type, $textTimelineCreateTitle)
    {
        if (!empty($textTimelineCreateTitle)) {
            return $textTimelineCreateTitle;
        }

        $default_key = 'create_' . $type;

        $translation = $this->getTextFromConfig($type, 'timeline_create_title', $default_key);

        if (!empty($translation)) {
            return $translation;
        }

        return 'invoices.create_invoice';
    }

    protected function getTextTimelineCreateMessage($type, $textTimelineCreateMessage)
    {
        if (!empty($textTimelineCreateMessage)) {
            return $textTimelineCreateMessage;
        }

        $translation = $this->getTextFromConfig($type, 'timeline_create_message', 'messages.status.created');

        if (!empty($translation)) {
            return $translation;
        }

        return 'invoices.messages.status.created';
    }

    protected function getTextTimelineSentTitle($type, $textTimelineSentTitle)
    {
        if (!empty($textTimelineSentTitle)) {
            return $textTimelineSentTitle;
        }

        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $default_key = 'receive_bill';
                break;
            default:
                $default_key = 'send_invoice';
                break;
        }

        $translation = $this->getTextFromConfig($type, 'timeline_sent_title', $default_key);

        if (!empty($translation)) {
            return $translation;
        }

        return 'invoices.send_invoice';
    }

    protected function getTextTimelineSentStatusDraft($type, $textTimelineSentStatusDraft)
    {
        if (!empty($textTimelineSentStatusDraft)) {
            return $textTimelineSentStatusDraft;
        }

        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $default_key = 'messages.status.receive.draft';
                break;
            default:
                $default_key = 'messages.status.send.draft';
                break;
        }

        $translation = $this->getTextFromConfig($type, 'timeline_sent_status_draft', $default_key);

        if (!empty($translation)) {
            return $translation;
        }

        return 'invoices.messages.status.send.draft';
    }

    protected function getTextTimelineSentStatusMarkSent($type, $textTimelineSentStatusMarkSent)
    {
        if (!empty($textTimelineSentStatusMarkSent)) {
            return $textTimelineSentStatusMarkSent;
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

        $translation = $this->getTextFromConfig($type, 'timeline_sent_status_mark_sent', $default_key);

        if (!empty($translation)) {
            return $translation;
        }

        return 'invoices.mark_sent';
    }

    protected function getTextTimelineSentStatusReceived($type, $textTimelineSentStatusReceived)
    {
        if (!empty($textTimelineSentStatusReceived)) {
            return $textTimelineSentStatusReceived;
        }

        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $textTimelineSentStatusReceived = 'mark_received';
                break;
            default:
                $textTimelineSentStatusReceived = 'mark_sent';
                break;
        }

        $translation = $this->getTextFromConfig($type, 'timeline_sent_status_received', $default_key);

        if (!empty($translation)) {
            return $translation;
        }

        return 'invoices.mark_sent';
    }

    protected function getTextTimelineSendStatusMail($type, $textTimelineSendStatusMail)
    {
        if (!empty($textTimelineSendStatusMail)) {
            return $textTimelineSendStatusMail;
        }

        $translation = $this->getTextFromConfig($type, 'timeline_sent_status_mail', 'send_mail');

        if (!empty($translation)) {
            return $translation;
        }

        return 'invoices.send_mail';
    }

    protected function getTextTimelineGetPaidTitle($type, $textTimelineGetPaidTitle)
    {
        if (!empty($textTimelineGetPaidTitle)) {
            return $textTimelineGetPaidTitle;
        }

        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $default_key = 'make_payment';
                break;
            default:
                $default_key = 'get_paid';
                break;
        }

        $translation = $this->getTextFromConfig($type, 'timeline_get_paid_title', $default_key);

        if (!empty($translation)) {
            return $translation;
        }

        return 'invoices.get_paid';
    }

    protected function getTextTimelineGetPaidStatusAwait($type, $textTimelineGetPaidStatusAwait)
    {
        if (!empty($textTimelineGetPaidStatusAwait)) {
            return $textTimelineGetPaidStatusAwait;
        }

        $translation = $this->getTextFromConfig($type, 'timeline_get_paid_status_await', 'messages.status.paid.await');

        if (!empty($translation)) {
            return $translation;
        }

        return 'invoices.messages.status.paid.await';
    }

    protected function getTextTimelineGetPaidStatusPartiallyPaid($type, $textTimelineGetPaidStatusPartiallyPaid)
    {
        if (!empty($textTimelineGetPaidStatusPartiallyPaid)) {
            return $textTimelineGetPaidStatusPartiallyPaid;
        }

        $translation = $this->getTextFromConfig($type, 'timeline_get_paid_status_partially_paid', 'partially_paid');

        if (!empty($translation)) {
            return $translation;
        }

        return 'general.partially_paid';
    }

    protected function getTextTimelineGetPaidMarkPaid($type, $textTimelineGetPaidMarkPaid)
    {
        if (!empty($textTimelineGetPaidMarkPaid)) {
            return $textTimelineGetPaidMarkPaid;
        }

        $translation = $this->getTextFromConfig($type, 'timeline_get_paid_mark_paid', 'mark_paid');

        if (!empty($translation)) {
            return $translation;
        }

        return 'invoices.mark_paid';
    }

    protected function getTextTimelineGetPaidAddPayment($type, $textTimelineGetPaidAddPayment)
   {
        if (!empty($textTimelineGetPaidAddPayment)) {
            return $textTimelineGetPaidAddPayment;
        }

        $translation = $this->getTextFromConfig($type, 'timeline_get_paid_add_payment', 'add_payment');

        if (!empty($translation)) {
            return $translation;
        }

        return 'invoices.add_payment';
   }

   protected function getHideItems($type, $hideItems, $hideName, $hideDescription)
   {
       if (!empty($hideItems)) {
           return $hideItems;
       }

       $hideItems = ($this->getHideName($type, $hideName) & $this->getHideDescription($type, $hideDescription)) ? true  : false;

       return $hideItems;
   }

   protected function getHideName($type, $hideName)
   {
       if (!empty($hideName)) {
           return $hideName;
       }

       switch ($type) {
           case 'bill':
           case 'expense':
           case 'purchase':
               $hideName = setting('bill.hide_item_name', $hideName);
               break;
           default:
               $hideName = setting('invoice.hide_item_name', $hideName);
               break;
       }

       return $hideName;
   }

   protected function getHideDescription($type, $hideDescription)
   {
       if (!empty($hideDescription)) {
           return $hideDescription;
       }

       switch ($type) {
           case 'bill':
           case 'expense':
           case 'purchase':
               $hideDescription = setting('bill.hide_item_description', $hideDescription);
               break;
           default:
               $hideDescription = setting('invoice.hide_item_description', $hideDescription);
               break;
       }

       return $hideDescription;
   }

   protected function getHideQuantity($type, $hideQuantity)
   {
       if (!empty($hideQuantity)) {
           return $hideQuantity;
       }

       switch ($type) {
           case 'bill':
           case 'expense':
           case 'purchase':
               $hideQuantity = setting('bill.hide_quantity', $hideQuantity);
               break;
           default:
               $hideQuantity = setting('invoice.hide_quantity', $hideQuantity);
               break;
       }

       return $hideQuantity;
   }

   protected function getHidePrice($type, $hidePrice)
   {
       if (!empty($hidePrice)) {
           return $hidePrice;
       }

       switch ($type) {
           case 'bill':
           case 'expense':
           case 'purchase':
               $hidePrice = setting('bill.hide_price', $hidePrice);
               break;
           default:
               $hidePrice = setting('invoice.hide_price', $hidePrice);
               break;
       }

       return $hidePrice;
   }

   protected function getHideDiscount($type, $hideDiscount)
   {
       if (!empty($hideDiscount)) {
           return $hideDiscount;
       }

       switch ($type) {
           case 'bill':
           case 'expense':
           case 'purchase':
               $hideDiscount = setting('bill.hide_discount', $hideDiscount);
               break;
           default:
               $hideDiscount = setting('invoice.hide_discount', $hideDiscount);
               break;
       }

       return $hideDiscount;
   }

   protected function getHideAmount($type, $hideAmount)
   {
       if (!empty($hideAmount)) {
           return $hideAmount;
       }

       switch ($type) {
           case 'bill':
           case 'expense':
           case 'purchase':
               $hideAmount = setting('bill.hide_amount', $hideAmount);
               break;
           default:
               $hideAmount = setting('invoice.hide_amount', $hideAmount);
               break;
       }

       return $hideAmount;
   }
}
