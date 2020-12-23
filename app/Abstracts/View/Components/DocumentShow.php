<?php

namespace App\Abstracts\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Str;
use App\Traits\DateTime;
use App\Models\Common\Media;
use File;
use Image;
use Storage;
use Illuminate\Support\Facades\URL;

abstract class DocumentShow extends Component
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
    public $permissionDocumentCreate;

    /** @var string */
    public $permissionDocumentUpdate;

    /** @var string */
    public $permissionDocumentDelete;

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
        string $permissionDocumentCreate = '', string $permissionDocumentUpdate = '', string $permissionDocumentDelete = '', string $permissionButtonCustomize = '',
        bool $hideButtonGroupDivider1 = false, bool $hideButtonGroupDivider2 = false, bool $hideButtonGroupDivider3 = false,
        bool $hideButtonMoreActions = false, bool $hideButtonAddNew = false, bool $hideButtonEdit = false, bool $hideButtonDuplicate = false, bool $hideButtonPrint = false, bool $hideButtonPdf = false, bool $hideButtonCancel = false, bool $hideButtonCustomize = false, bool $hideButtonDelete = false,
        bool $hideHeader = false,bool $hideRecurringMessage = false, bool $hideStatusMessage = false, bool $hideTimeline = false, bool $hideFooter = false, bool $hideFooterHistories = false, bool $hideFooterTransactions = false,
        array $hideTimelineStatuses = [], bool $hideTimelineSent = false, bool $hideButtonSent = false, bool $hideButtonReceived = false, bool $hideButtonEmail = false, bool $hideButtonShare = false, bool $hideButtonPaid = false,
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
        string $textItems = '', string $textQuantity = '', string $textPrice = '', string $textAmount = '', $attachment = []
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
        $this->routeButtonPdf = $this->getRouteButtonPrint($type, $routeButtonPdf);
        $this->routeButtonCancelled = $this->getRouteButtonCancelled($type, $routeButtonCancelled);
        $this->routeButtonCustomize = $this->getRouteButtonCustomize($type, $routeButtonCustomize);
        $this->routeButtonDelete = $this->getRouteButtonDelete($type, $routeButtonDelete);
        $this->routeButtonPaid = $this->getRouteButtonPaid($type, $routeButtonPaid);

        $this->permissionDocumentCreate = $this->getPermissionDocumentCreate($type, $permissionDocumentCreate);
        $this->permissionDocumentUpdate = $this->getPermissionDocumentUpdate($type, $permissionDocumentUpdate);
        $this->permissionDocumentDelete = $this->getPermissionDocumentDelete($type, $permissionDocumentDelete);
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

        $this->permissionDocumentUpdate = $this->getPermissionDocumentUpdate($type, $permissionDocumentUpdate);

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
    }

    protected function getTextRecurringType($type, $textRecurringType)
    {
        if (!empty($textRecurringType)) {
            return $textRecurringType;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $textRecurringType = 'general.invoices';
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $textRecurringType = 'general.bills';
                break;
        }

        return $textRecurringType;
    }

    protected function getTextStatusMessage($type, $textStatusMessage)
    {
        if (!empty($textStatusMessage)) {
            return $textStatusMessage;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $textStatusMessage = 'invoices.messages.draft';
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $textStatusMessage = 'bills.messages.draft';
                break;
        }

        return $textStatusMessage;
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

        $page = Str::plural($type, 2);

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

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $textHistories = 'invoices.histories';
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $textHistories = 'bills.histories';
                break;
        }

        return $textHistories;
    }

    protected function getTextHistoryStatus($type, $textHistoryStatus)
    {
        if (!empty($textHistoryStatus)) {
            return $textHistoryStatus;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $textHistoryStatus = 'invoices.statuses.';
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $textHistoryStatus = 'bills.statuses.';
                break;
        }

        return $textHistoryStatus;
    }

    protected function getRouteButtonAddNew($type, $routeButtonAddNew)
    {
        $page = Str::plural($type, 2);

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

    protected function getRouteButtonPrint($type, $routeButtonPrint)
    {
        if (!empty($routeButtonPrint)) {
            return $routeButtonPrint;
        }

        $page = Str::plural($type, 2);

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

        $page = Str::plural($type, 2);

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

    protected function getRouteButtonPaid($type, $routeButtonPaid)
    {
        if (!empty($routeButtonPaid)) {
            return $routeButtonPaid;
        }

        $page = Str::plural($type, 2);

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

        $page = Str::plural($type, 2);

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

        $page = Str::plural($type, 2);

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

        $page = Str::plural($type, 2);

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

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $textHeaderContact = 'general.customers';
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $textHeaderContact = 'general.vendors';
                break;
        }

        return $textHeaderContact;
    }

    protected function getTextHeaderAmount($type, $textHeaderAmount)
    {
        if (!empty($textHeaderAmount)) {
            return $textHeaderAmount;
        }

        return 'general.amount_due';
    }

    protected function getTextHeaderDueAt($type, $textHeaderDueAt)
    {
        if (!empty($textHeaderDueAt)) {
            return $textHeaderDueAt;
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

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
            case 'bill':
            case 'expense':
            case 'purchase':
                $hideTimelineStatuses = ['paid', 'cancelled'];
                break;
        }

        return $hideTimelineStatuses;
    }

    protected function getTextTimelineCreateTitle($type, $textTimelineCreateTitle)
    {
        if (!empty($textTimelineCreateTitle)) {
            return $textTimelineCreateTitle;
        }

        return Str::plural($type, 2) . '.create_' . $type;
    }

    protected function getTextTimelineCreateMessage($type, $textTimelineCreateMessage)
    {
        if (!empty($textTimelineCreateMessage)) {
            return $textTimelineCreateMessage;
        }

        return Str::plural($type, 2) . '.messages.status.created';
    }

    protected function getTextTimelineSentTitle($type, $textTimelineSentTitle)
    {
        if (!empty($textTimelineSentTitle)) {
            return $textTimelineSentTitle;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $textTimelineSentTitle = 'invoices.send_invoice';
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $textTimelineSentTitle = 'bills.receive_bill';
                break;
        }

        return $textTimelineSentTitle;
    }

    protected function getTextTimelineSentStatusDraft($type, $textTimelineSentStatusDraft)
    {
        if (!empty($textTimelineSentStatusDraft)) {
            return $textTimelineSentStatusDraft;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $textTimelineSentStatusDraft = 'invoices.messages.status.send.draft';
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $textTimelineSentStatusDraft = 'bills.messages.status.receive.draft';
                break;
        }

        return $textTimelineSentStatusDraft;
    }

    protected function getTextTimelineSentStatusMarkSent($type, $textTimelineSentStatusMarkSent)
    {
        if (!empty($textTimelineSentStatusMarkSent)) {
            return $textTimelineSentStatusMarkSent;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $textTimelineSentStatusMarkSent = 'invoices.mark_sent';
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $textTimelineSentStatusMarkSent = 'bills.mark_received';
                break;
        }

        return $textTimelineSentStatusMarkSent;
    }

    protected function getTextTimelineSentStatusReceived($type, $textTimelineSentStatusReceived)
    {
        if (!empty($textTimelineSentStatusReceived)) {
            return $textTimelineSentStatusReceived;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $textTimelineSentStatusReceived = 'invoices.mark_sent';
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $textTimelineSentStatusReceived = 'bills.mark_received';
                break;
        }

        return $textTimelineSentStatusReceived;
    }

    protected function getTextTimelineSendStatusMail($type, $textTimelineSendStatusMail)
    {
        if (!empty($textTimelineSendStatusMail)) {
            return $textTimelineSendStatusMail;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $textTimelineSendStatusMail = 'invoices.send_mail';
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $textTimelineSendStatusMail = 'invoices.send_mail';
                break;
        }

        return $textTimelineSendStatusMail;
    }

    protected function getTextTimelineGetPaidTitle($type, $textTimelineGetPaidTitle)
    {
        if (!empty($textTimelineGetPaidTitle)) {
            return $textTimelineGetPaidTitle;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $textTimelineGetPaidTitle = 'invoices.get_paid';
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $textTimelineGetPaidTitle = 'bills.make_payment';
                break;
        }

        return $textTimelineGetPaidTitle;
    }

    protected function getTextTimelineGetPaidStatusAwait($type, $textTimelineGetPaidStatusAwait)
    {
        if (!empty($textTimelineGetPaidStatusAwait)) {
            return $textTimelineGetPaidStatusAwait;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $textTimelineGetPaidStatusAwait = 'invoices.messages.status.paid.await';
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $textTimelineGetPaidStatusAwait = 'bills.messages.status.paid.await';
                break;
        }

        return $textTimelineGetPaidStatusAwait;
    }

    protected function getTextTimelineGetPaidStatusPartiallyPaid($type, $textTimelineGetPaidStatusPartiallyPaid)
    {
        if (!empty($textTimelineGetPaidStatusPartiallyPaid)) {
            return $textTimelineGetPaidStatusPartiallyPaid;
        }

        $textTimelineGetPaidStatusPartiallyPaid = 'general.partially_paid';

        return $textTimelineGetPaidStatusPartiallyPaid;
    }

    protected function getTextTimelineGetPaidMarkPaid($type, $textTimelineGetPaidMarkPaid)
    {
        if (!empty($textTimelineGetPaidMarkPaid)) {
            return $textTimelineGetPaidMarkPaid;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $textTimelineGetPaidMarkPaid = 'invoices.mark_paid';
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $textTimelineGetPaidMarkPaid = 'bills.mark_paid';
                break;
        }

        return $textTimelineGetPaidMarkPaid;
    }

    protected function getTextTimelineGetPaidAddPayment($type, $textTimelineGetPaidAddPayment)
   {
        if (!empty($textTimelineGetPaidAddPayment)) {
            return $textTimelineGetPaidAddPayment;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $textTimelineGetPaidAddPayment = 'invoices.add_payment';
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $textTimelineGetPaidAddPayment = 'bills.add_payment';
                break;
        }

        return $textTimelineGetPaidAddPayment;
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
           case 'sale':
           case 'income':
           case 'invoice':
               $hideName = setting('invoice.hide_item_name', $hideName);
               break;
           case 'bill':
           case 'expense':
           case 'purchase':
               $hideName = setting('bill.hide_item_name', $hideName);
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
           case 'sale':
           case 'income':
           case 'invoice':
               $hideDescription = setting('invoice.hide_item_description', $hideDescription);
               break;
           case 'bill':
           case 'expense':
           case 'purchase':
               $hideDescription = setting('bill.hide_item_description', $hideDescription);
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
           case 'sale':
           case 'income':
           case 'invoice':
               $hideQuantity = setting('invoice.hide_quantity', $hideQuantity);
               break;
           case 'bill':
           case 'expense':
           case 'purchase':
               $hideQuantity = setting('bill.hide_quantity', $hideQuantity);
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
           case 'sale':
           case 'income':
           case 'invoice':
               $hidePrice = setting('invoice.hide_price', $hidePrice);
               break;
           case 'bill':
           case 'expense':
           case 'purchase':
               $hidePrice = setting('bill.hide_price', $hidePrice);
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
           case 'sale':
           case 'income':
           case 'invoice':
               $hideDiscount = setting('invoice.hide_discount', $hideDiscount);
               break;
           case 'bill':
           case 'expense':
           case 'purchase':
               $hideDiscount = setting('bill.hide_discount', $hideDiscount);
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
           case 'sale':
           case 'income':
           case 'invoice':
               $hideAmount = setting('invoice.hide_amount', $hideAmount);
               break;
           case 'bill':
           case 'expense':
           case 'purchase':
               $hideAmount = setting('bill.hide_amount', $hideAmount);
               break;
       }

       return $hideAmount;
   }
}
