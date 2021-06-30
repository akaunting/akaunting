<?php

namespace App\Abstracts\View\Components;

use App\Abstracts\View\Components\Document as Base;
use App\Models\Common\Media;
use App\Traits\DateTime;
use App\Traits\Documents;
use File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Image;
use Intervention\Image\Exception\NotReadableException;
use Illuminate\Support\Facades\Storage;

abstract class DocumentShow extends Base
{
    use DateTime;
    use Documents;

    public $type;

    public $document;

    /** @var string */
    public $documentTemplate;

    /** @var string */
    public $logo;

    /** @var string */
    public $backgroundColor;

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
    public $routeContactShow;

    /** @var string */
    public $permissionCreate;

    /** @var string */
    public $permissionUpdate;

    /** @var string */
    public $permissionDelete;

    /** @var string */
    public $permissionTransactionDelete;

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

    /** @var string */
    public $classHeaderStatus;

    /** @var string */
    public $classFooterHistories;

    /** @var string */
    public $classFooterTransactions;

    /** @var bool */
    public $hideHeaderContact;

    /** @var bool */
    public $hideHeaderAmount;

    /** @var bool */
    public $hideHeaderDueAt;

    /** @var bool */
    public $hideTimelineCreate;

    /** @var string */
    public $textDocumentTitle;

    /** @var string */
    public $textDocumentSubheading;

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
        $type, $document, $documentTemplate = '', $logo = '', $backgroundColor = '', string $signedUrl = '', $histories = [], $transactions = [],
        string $textRecurringType = '', string $textStatusMessage = '', string $textHistories = '', string $textHistoryStatus = '',
        string $routeButtonAddNew = '', string $routeButtonEdit = '', string $routeButtonDuplicate = '', string $routeButtonPrint = '', string $routeButtonPdf = '', string $routeButtonCancelled = '', string $routeButtonDelete = '', string $routeButtonCustomize = '', string $routeButtonSent = '',
        string $routeButtonReceived = '', string $routeButtonEmail = '', string $routeButtonPaid = '', string $routeContactShow = '',
        bool $checkButtonReconciled = true, bool $checkButtonCancelled = true,
        string $permissionCreate = '', string $permissionUpdate = '', string $permissionDelete = '', string $permissionTransactionDelete = '', string $permissionButtonCustomize = '',
        bool $hideButtonGroupDivider1 = false, bool $hideButtonGroupDivider2 = false, bool $hideButtonGroupDivider3 = false,
        bool $hideButtonMoreActions = false, bool $hideButtonAddNew = false, bool $hideButtonEdit = false, bool $hideButtonDuplicate = false, bool $hideButtonPrint = false, bool $hideButtonPdf = false, bool $hideButtonCancel = false, bool $hideButtonCustomize = false, bool $hideButtonDelete = false,
        bool $hideHeader = false,bool $hideRecurringMessage = false, bool $hideStatusMessage = false, bool $hideTimeline = false, bool $hideFooter = false, bool $hideFooterHistories = false, bool $hideFooterTransactions = false,
        array $hideTimelineStatuses = [], bool $hideTimelineSent = false, bool $hideTimelinePaid = false, bool $hideButtonSent = false, bool $hideButtonReceived = false, bool $hideButtonEmail = false, bool $hideButtonShare = false, bool $hideButtonPaid = false,
        string $textHeaderContact = '', string $textHeaderAmount = '', string $textHeaderDueAt = '',
        string $classHeaderStatus = '', string $classHeaderContact = '', string $classHeaderAmount = '', string $classHeaderDueAt = '', string $classFooterHistories = '', string $classFooterTransactions = '',
        bool $hideHeaderStatus = false, bool $hideHeaderContact = false, bool $hideHeaderAmount = false, bool $hideHeaderDueAt = false,
        string $textDocumentTitle = '', string $textDocumentSubheading = '',
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
        $this->backgroundColor = $backgroundColor;
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
        $this->routeContactShow = $this->getRouteContactShow($type, $routeContactShow);

        $this->permissionCreate = $this->getPermissionCreate($type, $permissionCreate);
        $this->permissionUpdate = $this->getPermissionUpdate($type, $permissionUpdate);
        $this->permissionDelete = $this->getPermissionDelete($type, $permissionDelete);
        $this->permissionTransactionDelete = $this->getPermissionTransactionDelete($type, $permissionTransactionDelete);
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

        $this->classFooterHistories = $this->getClassFooterHistories($type, $classFooterHistories);
        $this->classFooterTransactions = $this->getClassFooterTransactions($type, $classFooterTransactions);

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

        $this->textDocumentTitle = $this->getTextDocumentTitle($type, $textDocumentTitle);
        $this->textDocumentSubheading = $this->gettextDocumentSubheading($type, $textDocumentSubheading);
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

        $this->attachment = '';

        if (!empty($attachment)) {
            $this->attachment = $attachment;
        } else if (!empty($document)) {
            $this->attachment = $document->attachment;
        }

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

        $default_key = 'messages.draft';

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

        if ($template = config('type.' . $type . 'template', false)) {
            return $template;
        }

        if (!empty($alias = config('type.' . $type . '.alias'))) {
            $type = $alias . '.' . str_replace('-', '_', $type);
        }

        $documentTemplate = setting($this->getSettingKey($type, 'template')) ?: 'default';

        return $documentTemplate;
    }

    protected function getLogo($logo)
    {
        if (!empty($logo)) {
            return $logo;
        }

        $media_id = (!empty($this->document->contact->logo) && !empty($this->document->contact->logo->id)) ? $this->document->contact->logo->id : setting('company.logo');

        $media = Media::find($media_id);

        if (!empty($media)) {
            $path = $media->getDiskPath();

            if (Storage::missing($path)) {
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
                    $image->make(Storage::get($path))->resize($width, $height)->encode();
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

    protected function getSignedUrl($type, $signedUrl)
    {
        if (!empty($signedUrl)) {
            return $signedUrl;
        }

        $page = config('type.' . $type . '.route.prefix');
        $alias = config('type.' . $type . '.alias');

        $route = '';

        if (!empty($alias)) {
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

    protected function getTextHistories($type, $textHistories)
    {
        if (!empty($textHistories)) {
            return $textHistories;
        }

        $translation = $this->getTextFromConfig($type, 'histories', 'histories');

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

        $translation = $this->getTextFromConfig($type, 'document_status', 'statuses.');

        if (!empty($translation)) {
            return $translation;
        }

        $alias = config('type.' . $type . '.alias');

        if (!empty($alias)) {
            $translation = $alias . '::' . config('type.' . $type . '.translation.prefix') . '.statuses';

            if (is_array(trans($translation))) {
                return $translation . '.';
            }
        }

        return 'documents.statuses.';
    }

    protected function getRouteButtonAddNew($type, $routeButtonAddNew)
    {
        if (!empty($routeButtonAddNew)) {
            return $routeButtonAddNew;
        }

        $route = $this->getRouteFromConfig($type, 'create');

        if (!empty($route)) {
            return $route;
        }

        return 'invoices.create';
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

    protected function getRouteButtonPrint($type, $routeButtonPrint)
    {
        if (!empty($routeButtonPrint)) {
            return $routeButtonPrint;
        }

        //example route parameter.
        $parameter = 1;

        $route = $this->getRouteFromConfig($type, 'print', $parameter);

        if (!empty($route)) {
            return $route;
        }

        return 'invoices.print';
    }

    protected function getRouteButtonPdf($type, $routeButtonPdf)
    {
        if (!empty($routeButtonPdf)) {
            return $routeButtonPdf;
        }

        //example route parameter.
        $parameter = 1;

        $route = $this->getRouteFromConfig($type, 'pdf', $parameter);

        if (!empty($route)) {
            return $route;
        }

        return 'invoices.pdf';
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

    protected function getRouteButtonCustomize($type, $routeButtonCustomize)
    {
        if (!empty($routeButtonCustomize)) {
            return $routeButtonCustomize;
        }

        $route = '';

        $alias = config('type.' . $type . '.alias');

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

    protected function getRouteButtonPaid($type, $routeButtonPaid)
    {
        if (!empty($routeButtonPaid)) {
            return $routeButtonPaid;
        }

        //example route parameter.
        $parameter = 1;

        $route = $this->getRouteFromConfig($type, 'paid', $parameter);

        if (!empty($route)) {
            return $route;
        }

        return 'invoices.paid';
    }

    protected function getRouteContactShow($type, $routeContactShow)
    {
        if (!empty($routeContactShow)) {
            return $routeContactShow;
        }

        //example route parameter.
        $parameter = 1;

        $route = Str::plural(config('type.' . $type . '.contact_type'), 2) . '.show';

        try {
            route($route, $parameter);
        } catch (\Exception $e) {
            try {
                $route = Str::plural($type, 2) . '.' . $config_key;

                route($route, $parameter);
            } catch (\Exception $e) {
                $route = '';
            }
        }

        if (!empty($route)) {
            return $route;
        }

        return 'customers.show';
    }

    protected function getRouteButtonSent($type, $routeButtonSent)
    {
        if (!empty($routeButtonSent)) {
            return $routeButtonSent;
        }

        //example route parameter.
        $parameter = 1;

        $route = $this->getRouteFromConfig($type, 'sent', $parameter);

        if (!empty($route)) {
            return $route;
        }

        return 'invoices.sent';
    }

    protected function getRouteButtonReceived($type, $routeButtonReceived)
    {
        if (!empty($routeButtonReceived)) {
            return $routeButtonReceived;
        }

        //example route parameter.
        $parameter = 1;

        $route = $this->getRouteFromConfig($type, 'received', $parameter);

        if (!empty($route)) {
            return $route;
        }

        return 'invoices.received';
    }

    protected function getRouteButtonEmail($type, $routeButtonEmail)
    {
        if (!empty($routeButtonEmail)) {
            return $routeButtonEmail;
        }

        //example route parameter.
        $parameter = 1;

        $route = $this->getRouteFromConfig($type, 'email', $parameter);

        if (!empty($route)) {
            return $route;
        }

        return 'invoices.email';
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

    protected function getPermissionTransactionDelete($type, $permissionTransactionDelete)
    {
        if (!empty($permissionTransactionDelete)) {
            return $permissionTransactionDelete;
        }

        $permissionTransactionDelete = 'delete-banking-transactions';

        return $permissionTransactionDelete;
    }

    protected function getPermissionButtonCustomize($type, $permissionButtonCustomize)
    {
        if (!empty($permissionButtonCustomize)) {
            return $permissionButtonCustomize;
        }

        $permissionUpdate = $this->getPermissionFromConfig($type, 'update');

        return $permissionUpdate;
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

        $class = $this->getClassFromConfig($type, 'header_status');

        if (!empty($class)) {
            return $class;
        }

        return 'col-md-2';
    }

    protected function getClassHeaderContact($type, $classHeaderContact)
    {
        if (!empty($classHeaderContact)) {
            return $classHeaderContact;
        }

        $class = $this->getClassFromConfig($type, 'header_contact');

        if (!empty($class)) {
            return $class;
        }

        return 'col-md-6';
    }

    protected function getClassHeaderAmount($type, $classHeaderAmount)
    {
        if (!empty($classHeaderAmount)) {
            return $classHeaderAmount;
        }

        $class = $this->getClassFromConfig($type, 'header_amount');

        if (!empty($class)) {
            return $class;
        }

        return 'col-md-2';
    }

    protected function getClassHeaderDueAt($type, $classHeaderDueAt)
    {
        if (!empty($classHeaderDueAt)) {
            return $classHeaderDueAt;
        }

        $class = $this->getClassFromConfig($type, 'header_due_at');

        if (!empty($class)) {
            return $class;
        }

        return 'col-md-2';
    }

    protected function getClassFooterHistories($type, $classFooterHistories)
    {
        if (!empty($classFooterHistories)) {
            return $classFooterHistories;
        }

        $class = $this->getClassFromConfig($type, 'footer_histories');

        if (!empty($class)) {
            return $class;
        }

        return 'col-sm-6 col-md-6 col-lg-6 col-xl-6';
    }

    protected function getClassFooterTransactions($type, $classFooterTransactions)
    {
        if (!empty($classFooterTransactions)) {
            return $classFooterTransactions;
        }

        $class = $this->getClassFromConfig($type, 'footer_transactions');

        if (!empty($class)) {
            return $class;
        }

        return 'col-sm-6 col-md-6 col-lg-6 col-xl-6';
    }

    protected function getTimelineStatuses($type, $hideTimelineStatuses)
    {
        if (!empty($hideTimelineStatuses)) {
            return $hideTimelineStatuses;
        }

        $hideTimelineStatuses = ['paid', 'cancelled'];

        if ($timelime_statuses = config('type.' . $type . 'timeline_statuses')) {
            $hideTimelineStatuses = $timelime_statuses;
        }

        return $hideTimelineStatuses;
    }

    protected function getTextDocumentTitle($type, $textDocumentTitle)
    {
        if (!empty($textDocumentTitle)) {
            return $textDocumentTitle;
        }

        if (!empty(setting($type . '.title'))) {
            return setting($type . '.title');
        }

        $translation = $this->getTextFromConfig($type, 'document_title', Str::plural($type), 'trans_choice');

        if (!empty($translation)) {
            return trans_choice($translation, 1);
        }

        return setting('invoice.title');
    }

    protected function getTextDocumentSubheading($type, $textDocumentSubheading)
    {
        if (!empty($textDocumentSubheading)) {
            return $textDocumentSubheading;
        }

        if (!empty(setting($type . '.subheading'))) {
            return setting($type . '.subheading');
        }

        $translation = $this->getTextFromConfig($type, 'document_subheading', 'subheading');

        if (!empty($translation)) {
            return trans($translation);
        }

        return setting('invoice.subheading');
    }

    protected function getTextTimelineCreateTitle($type, $textTimelineCreateTitle)
    {
        if (!empty($textTimelineCreateTitle)) {
            return $textTimelineCreateTitle;
        }

        $default_key = 'create_' . str_replace('-', '_', $type);

        $translation = $this->getTextFromConfig($type, 'timeline_create_title', $default_key);

        if (!empty($translation)) {
            return $translation;
        }

        $default_key = 'create_' . str_replace('-', '_', config('type.' . $type . '.alias'));

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
                $default_key = 'mark_received';
                break;
            default:
                $default_key = 'mark_sent';
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

        $hide = $this->getHideFromConfig($type, 'items');

        if ($hide) {
            return $hide;
        }

        $hideItems = ($this->getHideName($type, $hideName) & $this->getHideDescription($type, $hideDescription)) ? true  : false;

        return $hideItems;
    }

    protected function getHideName($type, $hideName)
    {
        if (!empty($hideName)) {
            return $hideName;
        }

        // if you use settting translation
        if ($hideName = setting($this->getSettingKey($type, 'hide_item_name'), false)) {
            return $hideName;
        }

        $hide = $this->getHideFromConfig($type, 'name');

        if ($hide) {
            return $hide;
        }

        // @todo what return value invoice or always false??
        return setting('invoice.hide_item_name', $hideName);
    }

    protected function getHideDescription($type, $hideDescription)
    {
        if (!empty($hideDescription)) {
            return $hideDescription;
        }

        // if you use settting translation
        if ($hideDescription = setting($this->getSettingKey($type, 'hide_item_description'), false)) {
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
        if (!empty($hideQuantity)) {
            return $hideQuantity;
        }

        // if you use settting translation
        if ($hideQuantity = setting($this->getSettingKey($type, 'hide_quantity'), false)) {
            return $hideQuantity;
        }

        $hide = $this->getHideFromConfig($type, 'quantity');

        if ($hide) {
            return $hide;
        }

        // @todo what return value invoice or always false??
        return setting('invoice.hide_quantity', $hideQuantity);
    }

    protected function getHidePrice($type, $hidePrice)
    {
        if (!empty($hidePrice)) {
            return $hidePrice;
        }

        // if you use settting translation
        if ($hidePrice = setting($this->getSettingKey($type, 'hide_price'), false)) {
            return $hidePrice;
        }

        $hide = $this->getHideFromConfig($type, 'price');

        if ($hide) {
            return $hide;
        }

        // @todo what return value invoice or always false??
        return setting('invoice.hide_price', $hidePrice);
    }

    protected function getHideDiscount($type, $hideDiscount)
    {
        if (!empty($hideDiscount)) {
            return $hideDiscount;
        }

        // if you use settting translation
        if ($hideDiscount = setting($this->getSettingKey($type, 'hide_discount'), false)) {
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
        if (!empty($hideAmount)) {
            return $hideAmount;
        }

        // if you use settting translation
        if ($hideAmount = setting($this->getSettingKey($type, 'hide_amount'), false)) {
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
