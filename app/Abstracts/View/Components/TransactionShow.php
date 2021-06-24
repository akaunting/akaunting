<?php

namespace App\Abstracts\View\Components;

use App\Abstracts\View\Components\Transaction as Base;
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

abstract class TransactionShow extends Base
{
    use DateTime, Transactions;

    public $type;

    public $transaction;

    /** @var string */
    public $transactionTemplate;

    /** @var string */
    public $logo;

    /** @var string */
    public $signedUrl;

    public $histories;

    public $date_format;

    /** @var string */
    public $textHistories;

    /** @var array */
    public $payment_methods;

    /** @var string */
    public $checkButtonReconciled;

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
    public $routeButtonDelete;

    /** @var string */
    public $routeButtonEmail;

    /** @var string */
    public $permissionCreate;

    /** @var string */
    public $permissionUpdate;

    /** @var string */
    public $permissionDelete;

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
    public $hideButtonDelete;

    /** @var bool */
    public $hideHeader;

    /** @var bool */
    public $hideFooter;

    /** @var bool */
    public $hideFooterHistories;

    /** @var bool */
    public $hideButtonEmail;

    /** @var bool */
    public $hideButtonShare;

    /** @var string */
    public $textHeaderAccount;

    /** @var string */
    public $textHeaderCategory;

    /** @var string */
    public $textHeaderContact;

    /** @var string */
    public $textHeaderAmount;

    /** @var string */
    public $textHeaderPaidAt;

    /** @var bool */
    public $hideHeaderAccount;

    /** @var bool */
    public $hideHeaderCategory;

    /** @var string */
    public $classHeaderCategory;

    /** @var string */
    public $classHeaderContact;

    /** @var string */
    public $classHeaderAmount;

    /** @var string */
    public $classHeaderPaidAt;

    /** @var string */
    public $classHeaderAccount;

    /** @var string */
    public $classFooterHistories;

    /** @var bool */
    public $hideHeaderContact;

    /** @var bool */
    public $hideHeaderAmount;

    /** @var bool */
    public $hideHeaderPaidAt;

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

    public $hideIssuedAt;

    public $hideDueAt;

    public $textContactInfo;

    /** @var string */
    public $textIssuedAt;

    /** @var string */
    public $textDueAt;

    public $hideName;

    public $hideDescription;

    public $hideAmount;

    /** @var string */
    public $textAmount;

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
        $type, $transaction, $transactionTemplate = '', $logo = '', string $signedUrl = '', $histories = [], string $textHistories = '',
        string $routeButtonAddNew = '', string $routeButtonEdit = '', string $routeButtonDuplicate = '', string $routeButtonPrint = '', string $routeButtonPdf = '', string $routeButtonDelete = '', string $routeButtonEmail = '',
        string $permissionCreate = '', string $permissionUpdate = '', string $permissionDelete = '',
        bool $checkButtonReconciled = true, array $payment_methods = [],
        bool $hideButtonGroupDivider1 = false, bool $hideButtonGroupDivider2 = false, bool $hideButtonGroupDivider3 = false,
        bool $hideButtonMoreActions = false, bool $hideButtonAddNew = false, bool $hideButtonEdit = false, bool $hideButtonDuplicate = false, bool $hideButtonPrint = false, bool $hideButtonPdf = false, bool $hideButtonDelete = false,
        bool $hideHeader = false, bool $hideFooter = false, bool $hideFooterHistories = false,
        bool $hideButtonEmail = false, bool $hideButtonShare = false,
        string $textHeaderAccount = '', string $textHeaderCategory = '', string $textHeaderContact = '', string $textHeaderAmount = '', string $textHeaderPaidAt = '',
        string $classHeaderAccount = '', string $classHeaderCategory = '', string $classHeaderContact = '', string $classHeaderAmount = '', string $classHeaderPaidAt = '', string $classFooterHistories = '',
        bool $hideHeaderAccount = false, bool $hideHeaderCategory = false, bool $hideHeaderContact = false, bool $hideHeaderAmount = false, bool $hideHeaderPaidAt = false,
        bool $hideTimelineCreate = false, bool $hideCompanyLogo = false, bool $hideCompanyDetails = false,
        bool $hideCompanyName = false, bool $hideCompanyAddress = false, bool $hideCompanyTaxNumber = false, bool $hideCompanyPhone = false, bool $hideCompanyEmail = false, bool $hideContactInfo = false,
        bool $hideContactName = false, bool $hideContactAddress = false, bool $hideContactTaxNumber = false, bool $hideContactPhone = false, bool $hideContactEmail = false,
        bool $hideOrderNumber = false, bool $hidetransactionNumber = false, bool $hideIssuedAt = false, bool $hideDueAt = false,
        string $textContactInfo = '', string $textIssuedAt = '', string $textDueAt = '',
        bool $hideName = false, bool $hideDescription = false, bool $hideAmount = false, bool $hideAttachment = false,
        string $textAmount = '', $attachment = [],
        string $textDeleteModal = ''
    ) {
        $this->type = $type;
        $this->transaction = $transaction;
        $this->transactionTemplate = $this->getTransactionTemplate($type, $transactionTemplate);
        $this->logo = $this->getLogo($logo);
        $this->signedUrl = $this->getSignedUrl($type, $signedUrl);

        $this->histories = $this->getHistories($histories);

        $this->date_format = $this->getCompanyDateFormat();

        $this->textHistories = $this->getTextHistories($type, $textHistories);

        $this->checkButtonReconciled = $checkButtonReconciled;

        $this->payment_methods = ($payment_methods) ?: Modules::getPaymentMethods();

        $this->routeButtonAddNew = $this->getRouteButtonAddNew($type, $routeButtonAddNew);
        $this->routeButtonEdit = $this->getRouteButtonEdit($type, $routeButtonEdit);
        $this->routeButtonDuplicate = $this->getRouteButtonDuplicate($type, $routeButtonDuplicate);
        $this->routeButtonPrint = $this->getRouteButtonPrint($type, $routeButtonPrint);
        $this->routeButtonPdf = $this->getRouteButtonPdf($type, $routeButtonPdf);
        $this->routeButtonDelete = $this->getRouteButtonDelete($type, $routeButtonDelete);

        $this->permissionCreate = $this->getPermissionCreate($type, $permissionCreate);
        $this->permissionUpdate = $this->getPermissionUpdate($type, $permissionUpdate);
        $this->permissionDelete = $this->getPermissionDelete($type, $permissionDelete);

        $this->hideButtonGroupDivider1 = $hideButtonGroupDivider1;
        $this->hideButtonGroupDivider2 = $hideButtonGroupDivider2;
        $this->hideButtonGroupDivider3 = $hideButtonGroupDivider3;

        $this->hideButtonMoreActions = $hideButtonMoreActions;
        $this->hideButtonAddNew = $hideButtonAddNew;
        $this->hideButtonEdit = $hideButtonEdit;
        $this->hideButtonDuplicate  = $hideButtonDuplicate;
        $this->hideButtonPrint  = $hideButtonPrint;
        $this->hideButtonPdf  = $hideButtonPdf;
        $this->hideButtonDelete = $hideButtonDelete;

        $this->hideHeader = $hideHeader;
        $this->hideFooter = $hideFooter;
        $this->hideFooterHistories = $hideFooterHistories;

        $this->classHeaderAccount = $this->getclassHeaderAccount($type, $classHeaderAccount);
        $this->classHeaderContact = $this->getClassHeaderContact($type, $classHeaderContact);
        $this->classHeaderCategory = $this->getClassHeaderCategory($type, $classHeaderCategory);
        $this->classHeaderAmount = $this->getClassHeaderAmount($type, $classHeaderAmount);
        $this->classHeaderPaidAt = $this->getclassHeaderPaidAt($type, $classHeaderPaidAt);

        $this->classFooterHistories = $this->getClassFooterHistories($type, $classFooterHistories);

        $this->hideHeaderAccount = $hideHeaderAccount;
        $this->hideHeaderCategory = $hideHeaderCategory;
        $this->hideHeaderContact = $hideHeaderContact;
        $this->hideHeaderCategory = $hideHeaderCategory;
        $this->hideHeaderAmount = $hideHeaderAmount;
        $this->hideHeaderPaidAt = $hideHeaderPaidAt;

        $this->textHeaderAccount = $this->getTextHeaderAccount($type, $textHeaderAccount);
        $this->textHeaderCategory = $this->getTextHeaderCategory($type, $textHeaderCategory);
        $this->textHeaderContact = $this->getTextHeaderContact($type, $textHeaderContact);
        $this->textHeaderAmount = $this->getTextHeaderAmount($type, $textHeaderAmount);
        $this->textHeaderPaidAt = $this->gettextHeaderPaidAt($type, $textHeaderPaidAt);

        $this->hideTimelineCreate = $hideTimelineCreate;
        $this->hideButtonEmail = $hideButtonEmail;
        $this->hideButtonShare = $hideButtonShare;

        $this->routeButtonEmail = $this->getRouteButtonEmail($type, $routeButtonEmail);

        $this->hideCompanyDetails = $hideCompanyDetails;
        $this->hideCompanyLogo = $hideCompanyLogo;
        $this->hideCompanyName = $hideCompanyName;
        $this->hideContactAddress = $hideContactAddress;
        $this->hideContactTaxNumber = $hideContactTaxNumber;
        $this->hideContactPhone = $hideContactPhone;
        $this->hideContactEmail = $hideContactEmail;
        $this->hideIssuedAt = $hideIssuedAt;
        $this->hideDueAt = $hideDueAt;

        $this->textContactInfo = $textContactInfo;
        $this->textIssuedAt = $textIssuedAt;
        $this->textDueAt = $textDueAt;

        $this->hideName = $this->getHideName($type, $hideName);
        $this->hideDescription = $this->getHideDescription($type, $hideDescription);
        $this->hideAmount = $this->getHideAmount($type, $hideAmount);
        $this->hideAttachment = $hideAttachment;

        $this->attachment = '';

        if (!empty($attachment)) {
            $this->attachment = $attachment;
        } else if (!empty($transaction)) {
            $this->attachment = $transaction->attachment;
        }

        $this->textAmount = $textAmount;

        $this->textDeleteModal = $textDeleteModal;
    }

    protected function getTransactionTemplate($type, $transactionTemplate)
    {
        if (!empty($transactionTemplate)) {
            return $transactionTemplate;
        }

        if ($template = config('type.' . $type . 'template', false)) {
            return $template;
        }

        if (!empty($alias = config('type.' . $type . '.alias'))) {
            $type = $alias . '.' . str_replace('-', '_', $type);
        }

        $transactionTemplate = setting($this->getSettingKey($type, 'template')) ?: 'default';

        return $transactionTemplate;
    }

    protected function getLogo($logo)
    {
        if (!empty($logo)) {
            return $logo;
        }

        $media = Media::find(setting('company.logo'));

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
            Log::info('Company ID: ' . company_id() . ' components/transactionshow.php exception.');
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

    protected function getHistories($histories)
    {
        if (!empty($histories)) {
            return $histories;
        }

        $histories[] = $this->transaction;

        return $histories;
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
            route($route, [$this->transaction->id, 'company_id' => company_id()]);

            $signedUrl = URL::signedRoute($route, [$this->transaction->id]);
        } catch (\Exception $e) {
            $signedUrl = URL::signedRoute('signed.payments.show', [$this->transaction->id]);
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

    protected function getRouteButtonAddNew($type, $routeButtonAddNew)
    {
        if (!empty($routeButtonAddNew)) {
            return $routeButtonAddNew;
        }

        $route = $this->getRouteFromConfig($type, 'create');

        if (!empty($route)) {
            return $route;
        }

        return 'revenues.create';
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

        return 'revenues.edit';
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

        return 'revenues.duplicate';
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

        return 'revenues.print';
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

        return 'revenues.pdf';
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

        return 'revenues.destroy';
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

        return 'revenues.email';
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

    protected function getTextHeaderAccount($type, $textHeaderAccount)
    {
        if (!empty($textHeaderAccount)) {
            return $textHeaderAccount;
        }

        $default_key = Str::plural(config('type.' . $type . '.contact_type'), 2);

        $translation = $this->getTextFromConfig($type, 'header_contact', $default_key, 'trans_choice');

        if (!empty($translation)) {
            return $translation;
        }

        return 'general.customers';
    }

    protected function getTextHeaderCategory($type, $textHeaderCategory)
    {
        if (!empty($textHeaderCategory)) {
            return $textHeaderCategory;
        }

        $default_key = Str::plural(config('type.' . $type . '.contact_type'), 2);

        $translation = $this->getTextFromConfig($type, 'header_contact', $default_key, 'trans_choice');

        if (!empty($translation)) {
            return $translation;
        }

        return 'general.customers';
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

    protected function gettextHeaderPaidAt($type, $textHeaderPaidAt)
    {
        if (!empty($textHeaderPaidAt)) {
            return $textHeaderPaidAt;
        }

        $translation = $this->getTextFromConfig($type, 'header_due_at', 'due_on');

        if (!empty($translation)) {
            return $translation;
        }

        return 'general.due_on';
    }

    protected function getclassHeaderAccount($type, $classHeaderAccount)
    {
        if (!empty($classHeaderAccount)) {
            return $classHeaderAccount;
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

        return 'col-md-3';
    }

    protected function getClassHeaderCategory($type, $classHeaderCategory)
    {
        if (!empty($classHeaderCategory)) {
            return $classHeaderCategory;
        }

        $class = $this->getClassFromConfig($type, 'header_contact');

        if (!empty($class)) {
            return $class;
        }

        return 'col-md-3';
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

    protected function getclassHeaderAccountAt($type, $classHeaderAccountAt)
    {
        if (!empty($classHeaderPaidAt)) {
            return $classHeaderPaidAt;
        }

        $class = $this->getClassFromConfig($type, 'header_paid_at');

        if (!empty($class)) {
            return $class;
        }

        return 'col-md-2';
    }

    protected function getclassHeaderPaidAt($type, $classHeaderPaidAt)
    {
        if (!empty($classHeaderPaidAt)) {
            return $classHeaderPaidAt;
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
