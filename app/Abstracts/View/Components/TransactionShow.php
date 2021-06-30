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

    /** @var string */
    public $checkButtonReconciled;

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
    public $routeContactShow;

    /** @var string */
    public $signedUrl;

    /** @var string */
    public $routeButtonEmail;

    /** @var string */
    public $routeButtonPdf;

    /** @var string */
    public $routeButtonDelete;

    /** @var string */
    public $textDeleteModal;

    /** @var bool */
    public $hideHeader;

    /** @var bool */
    public $hideHeaderAccount;

    /** @var bool */
    public $hideHeaderCategory;

    /** @var bool */
    public $hideHeaderContact;

    /** @var bool */
    public $hideHeaderAmount;

    /** @var bool */
    public $hideHeaderPaidAt;

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

    /** @var string */
    public $classHeaderAccount;

    /** @var string */
    public $classHeaderCategory;

    /** @var string */
    public $classHeaderContact;

    /** @var string */
    public $classHeaderAmount;

    /** @var string */
    public $classHeaderPaidAt;

    /** @var bool */
    public $hideCompany;

    /** @var bool */
    public $hideCompanyLogo;

    /** @var bool */
    public $hideCompanyDetails;

    /** @var bool */
    public $hideCompanyName;

    /** @var bool */
    public $hideCompanyAddress;

    /** @var bool */
    public $hideCompanyTaxNumber;

    /** @var bool */
    public $hideCompanyPhone;

    /** @var bool */
    public $hideCompanyEmail;

    /** @var bool */
    public $hideContentTitle;

    /** @var bool */
    public $hidePaidAt;

    /** @var bool */
    public $hideAccount;

    /** @var bool */
    public $hideCategory;

    /** @var bool */
    public $hidePaymentMethods;

    /** @var bool */
    public $hideReference;

    /** @var bool */
    public $hideDescription;

    /** @var bool */
    public $hideAmount;

    /** @var string */
    public $textContentTitle;

    /** @var string */
    public $textPaidAt;

    /** @var string */
    public $textAccount;

    /** @var string */
    public $textCategory;

    /** @var string */
    public $textPaymentMethods;

    /** @var string */
    public $textReference;

    /** @var string */
    public $textDescription;

    /** @var string */
    public $textAmount;

    /** @var string */
    public $textPaidBy;

    /** @var bool */
    public $hideContact;

    /** @var bool */
    public $hideContactInfo;

    /** @var bool */
    public $hideContactName;

    /** @var bool */
    public $hideContactAddress;

    /** @var bool */
    public $hideContactTaxNumber;

    /** @var bool */
    public $hideContactPhone;

    /** @var bool */
    public $hideContactEmail;

    /** @var bool */
    public $hideReletad;

    /** @var bool */
    public $hideReletadDocumentNumber;

    /** @var bool */
    public $hideReletadContact;

    /** @var bool */
    public $hideReletadDocumentDate;

    /** @var bool */
    public $hideReletadDocumentAmount;

    /** @var bool */
    public $hideReletadAmount;

    /** @var string */
    public $textReleatedTransansaction;

    /** @var string */
    public $textReleatedDocumentNumber;

    /** @var string */
    public $textReleatedContact;

    /** @var string */
    public $textReleatedDocumentDate;

    /** @var string */
    public $textReleatedDocumentAmount;

    /** @var string */
    public $textReleatedAmount;

    /** @var string */
    public $routeDocumentShow;

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
        $type, $transaction, $transactionTemplate = '', $logo = '', array $payment_methods = [],
        bool $hideButtonAddNew = false, bool $hideButtonMoreActions = false, bool $hideButtonEdit = false, bool $hideButtonDuplicate = false, bool $hideButtonPrint = false, bool $hideButtonShare = false,
        bool $hideButtonEmail = false, bool $hideButtonPdf = false, bool $hideButtonDelete = false, bool $checkButtonReconciled = true,
        bool $hideButtonGroupDivider1 = false, bool $hideButtonGroupDivider2 = false, bool $hideButtonGroupDivider3 = false,
        string $permissionCreate = '', string $permissionUpdate = '', string $permissionDelete = '',
        string $routeButtonAddNew = '', string $routeButtonEdit = '', string $routeButtonDuplicate = '', string $routeButtonPrint = '', string $signedUrl = '',
        string $routeButtonEmail = '', string $routeButtonPdf = '', string $routeButtonDelete = '', string $routeContactShow = '',
        string $textDeleteModal = '',
        bool $hideHeader = false, bool $hideHeaderAccount = false, bool $hideHeaderCategory = false, bool $hideHeaderContact = false, bool $hideHeaderAmount = false, bool $hideHeaderPaidAt = false,
        string $textHeaderAccount = '', string $textHeaderCategory = '', string $textHeaderContact = '', string $textHeaderAmount = '', string $textHeaderPaidAt = '',
        string $classHeaderAccount = '', string $classHeaderCategory = '', string $classHeaderContact = '', string $classHeaderAmount = '', string $classHeaderPaidAt = '',

        bool $hideCompany = false, bool $hideCompanyLogo = false, bool $hideCompanyDetails = false, bool $hideCompanyName = false, bool $hideCompanyAddress = false,
        bool $hideCompanyTaxNumber = false, bool $hideCompanyPhone = false, bool $hideCompanyEmail = false,
        bool $hideContentTitle = false,bool $hidePaidAt = false, bool $hideAccount = false, bool $hideCategory = false, bool $hidePaymentMethods = false, bool $hideReference = false, bool $hideDescription = false,
        bool $hideAmount = false,
        string $textContentTitle = '', string $textPaidAt = '', string $textAccount = '', string $textCategory = '', string $textPaymentMethods = '', string $textReference = '', string $textDescription = '',
        string $textAmount = '', string $textPaidBy = '',
        bool $hideContact = false, bool $hideContactInfo = false, bool $hideContactName = false, bool $hideContactAddress = false, bool $hideContactTaxNumber = false,
        bool $hideContactPhone = false, bool $hideContactEmail = false,
        bool $hideReletad = false, bool $hideReletadDocumentNumber = false, bool $hideReletadContact = false, bool $hideReletadDocumentDate = false, bool $hideReletadDocumentAmount = false, bool $hideReletadAmount = false,
        string $textReleatedTransansaction = '', string $textReleatedDocumentNumber = '', string $textReleatedContact = '', string $textReleatedDocumentDate = '', string $textReleatedDocumentAmount = '', string $textReleatedAmount = '',
        string $routeDocumentShow = '',

        bool $hideAttachment = false, $attachment = [],
        bool $hideFooter = false, bool $hideFooterHistories = false, $histories = [],
        string $textHistories = '', string $classFooterHistories = ''
    ) {
        $this->type = $type;
        $this->transaction = $transaction;
        $this->transactionTemplate = $this->getTransactionTemplate($type, $transactionTemplate);
        $this->logo = $this->getLogo($logo);
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
        $this->hideButtonDelete = $hideButtonDelete;
        $this->checkButtonReconciled = $checkButtonReconciled;
        $this->hideButtonGroupDivider1 = $hideButtonGroupDivider1;
        $this->hideButtonGroupDivider2 = $hideButtonGroupDivider2;
        $this->hideButtonGroupDivider3 = $hideButtonGroupDivider3;

        // Navbar Permission
        $this->permissionCreate = $this->getPermissionCreate($type, $permissionCreate);
        $this->permissionUpdate = $this->getPermissionUpdate($type, $permissionUpdate);
        $this->permissionDelete = $this->getPermissionDelete($type, $permissionDelete);

        // Navbar route
        $this->routeButtonAddNew = $this->getRouteButtonAddNew($type, $routeButtonAddNew);
        $this->routeButtonEdit = $this->getRouteButtonEdit($type, $routeButtonEdit);
        $this->routeButtonDuplicate = $this->getRouteButtonDuplicate($type, $routeButtonDuplicate);
        $this->routeButtonPrint = $this->getRouteButtonPrint($type, $routeButtonPrint);
        $this->signedUrl = $this->getSignedUrl($type, $signedUrl);
        $this->routeButtonEmail = $this->getRouteButtonEmail($type, $routeButtonEmail);
        $this->routeButtonPdf = $this->getRouteButtonPdf($type, $routeButtonPdf);
        $this->routeButtonDelete = $this->getRouteButtonDelete($type, $routeButtonDelete);
        $this->routeContactShow = $this->getRouteContactShow($type, $routeContactShow);

        // Navbar Text
        $this->textDeleteModal = $textDeleteModal;

        // Header Hide
        $this->hideHeader = $hideHeader;

        $this->hideHeaderAccount = $hideHeaderAccount;
        $this->hideHeaderCategory = $hideHeaderCategory;
        $this->hideHeaderContact = $hideHeaderContact;
        $this->hideHeaderCategory = $hideHeaderCategory;
        $this->hideHeaderAmount = $hideHeaderAmount;
        $this->hideHeaderPaidAt = $hideHeaderPaidAt;

        // Header Text
        $this->textHeaderAccount = $this->getTextHeaderAccount($type, $textHeaderAccount);
        $this->textHeaderCategory = $this->getTextHeaderCategory($type, $textHeaderCategory);
        $this->textHeaderContact = $this->getTextHeaderContact($type, $textHeaderContact);
        $this->textHeaderAmount = $this->getTextHeaderAmount($type, $textHeaderAmount);
        $this->textHeaderPaidAt = $this->gettextHeaderPaidAt($type, $textHeaderPaidAt);

        // Header Class
        $this->classHeaderAccount = $this->getclassHeaderAccount($type, $classHeaderAccount);
        $this->classHeaderContact = $this->getClassHeaderContact($type, $classHeaderContact);
        $this->classHeaderCategory = $this->getClassHeaderCategory($type, $classHeaderCategory);
        $this->classHeaderAmount = $this->getClassHeaderAmount($type, $classHeaderAmount);
        $this->classHeaderPaidAt = $this->getclassHeaderPaidAt($type, $classHeaderPaidAt);

        // Hide Attachment
        $this->hideAttachment = $hideAttachment;

        // Company Information Hide checker
        $this->hideCompany = $hideCompany;
        $this->hideCompanyLogo = $hideCompanyLogo;
        $this->hideCompanyDetails = $hideCompanyDetails;
        $this->hideCompanyName = $hideCompanyName;
        $this->hideCompanyAddress = $hideCompanyAddress;
        $this->hideCompanyTaxNumber = $hideCompanyTaxNumber;
        $this->hideCompanyPhone = $hideCompanyPhone;
        $this->hideCompanyEmail = $hideCompanyEmail;

        // Transaction Information Hide checker
        $this->hideContentTitle = $hideContentTitle;
        $this->hidePaidAt = $hidePaidAt;
        $this->hideAccount = $hideAccount;
        $this->hideCategory = $hideCategory;
        $this->hidePaymentMethods = $hidePaymentMethods;
        $this->hideReference = $hideReference;
        $this->hideDescription = $hideDescription;
        $this->hideAmount = $hideAmount;

        // Transaction Information Text
        $this->textContentTitle = $this->getTextContentTitle($type, $textContentTitle);
        $this->textPaidAt = $this->getTextPaidAt($type, $textPaidAt);
        $this->textAccount = $this->getTextAccount($type, $textAccount);
        $this->textCategory = $this->getTextCategory($type, $textCategory);
        $this->textPaymentMethods = $this->getTextPaymentMethods($type, $textPaymentMethods);
        $this->textReference = $this->getTextReference($type, $textReference);
        $this->textDescription = $this->getTextDescription($type, $textDescription);
        $this->textAmount = $this->getTextAmount($type, $textAmount);
        $this->textPaidBy = $this->getTextPaidBy($type, $textPaidBy);

        // Contact Information Hide checker
        $this->hideContact = $hideContact;
        $this->hideContactInfo = $hideContactInfo;
        $this->hideContactName = $hideContactName;
        $this->hideContactAddress = $hideContactAddress;
        $this->hideContactTaxNumber = $hideContactTaxNumber;
        $this->hideContactPhone = $hideContactPhone;
        $this->hideContactEmail = $hideContactEmail;

        // Releated Information Hide checker
        $this->hideReletad = $hideReletad;
        $this->hideReletadDocumentNumber = $hideReletadDocumentNumber;
        $this->hideReletadContact = $hideReletadContact;
        $this->hideReletadDocumentDate = $hideReletadDocumentDate;
        $this->hideReletadDocumentAmount = $hideReletadDocumentAmount;
        $this->hideReletadAmount = $hideReletadAmount;

        // Releated Information Text
        $this->textReleatedTransansaction = $this->getTextReleatedTransansaction($type, $textReleatedTransansaction);
        $this->textReleatedDocumentNumber = $this->getTextReleatedDocumentNumber($type, $textReleatedDocumentNumber);
        $this->textReleatedContact = $this->getTextReleatedContact($type, $textReleatedContact);
        $this->textReleatedDocumentDate = $this->getTextReleatedDocumentDate($type, $textReleatedDocumentDate);
        $this->textReleatedDocumentAmount = $this->getTextReleatedDocumentAmount($type, $textReleatedDocumentAmount);
        $this->textReleatedAmount = $this->getTextReleatedAmount($type, $textReleatedAmount);

        $this->routeDocumentShow = $this->routeDocumentShow($type, $routeDocumentShow);

        // Attachment data..
        $this->attachment = '';

        if (!empty($attachment)) {
            $this->attachment = $attachment;
        } else if (!empty($transaction)) {
            $this->attachment = $transaction->attachment;
        }

        // Histories Hide
        $this->hideFooter = $hideFooter;
        $this->hideFooterHistories = $hideFooterHistories;

        // Histories
        $this->histories = $this->getHistories($histories);
        $this->textHistories = $this->getTextHistories($type, $textHistories);
        $this->classFooterHistories = $this->getClassFooterHistories($type, $classFooterHistories);
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

        $media_id = (!empty($this->transaction->contact->logo) && !empty($this->transaction->contact->logo->id)) ? $this->transaction->contact->logo->id : setting('company.logo');

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

        $translation = $this->getTextFromConfig($type, 'header_account', 'accounts', 'trans_choice');

        if (!empty($translation)) {
            return $translation;
        }

        return 'general.accounts';
    }

    protected function getTextHeaderCategory($type, $textHeaderCategory)
    {
        if (!empty($textHeaderCategory)) {
            return $textHeaderCategory;
        }

        $translation = $this->getTextFromConfig($type, 'header_category', 'categories', 'trans_choice');

        if (!empty($translation)) {
            return $translation;
        }

        return 'general.categories';
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

        $translation = $this->getTextFromConfig($type, 'header_amount', 'amount');

        if (!empty($translation)) {
            return $translation;
        }

        return 'general.amount';
    }

    protected function getTextHeaderPaidAt($type, $textHeaderPaidAt)
    {
        if (!empty($textHeaderPaidAt)) {
            return $textHeaderPaidAt;
        }

        $translation = $this->getTextFromConfig($type, 'header_paid_at', 'date');

        if (!empty($translation)) {
            return $translation;
        }

        return 'general.date';
    }

    protected function getClassHeaderAccount($type, $classHeaderAccount)
    {
        if (!empty($classHeaderAccount)) {
            return $classHeaderAccount;
        }

        $class = $this->getClassFromConfig($type, 'header_account');

        if (!empty($class)) {
            return $class;
        }

        return 'col-4 col-lg-3';
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

        return 'col-4 col-lg-2';
    }

    protected function getClassHeaderCategory($type, $classHeaderCategory)
    {
        if (!empty($classHeaderCategory)) {
            return $classHeaderCategory;
        }

        $class = $this->getClassFromConfig($type, 'header_category');

        if (!empty($class)) {
            return $class;
        }

        return 'col-4 col-lg-3';
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

        return 'col-4 col-lg-2';
    }

    protected function getClassHeaderPaidAt($type, $classHeaderPaidAt)
    {
        if (!empty($classHeaderPaidAt)) {
            return $classHeaderPaidAt;
        }

        $class = $this->getClassFromConfig($type, 'header_paid_at');

        if (!empty($class)) {
            return $class;
        }

        return 'col-4 col-lg-2';
    }

    protected function getTextContentTitle($type, $textContentTitle)
    {
        if (!empty($textContentTitle)) {
            return $textContentTitle;
        }

        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $default_key = 'payment_made';
                break;
            default:
                $default_key = 'revenue_received';
                break;
        }

        $translation = $this->getTextFromConfig($type, $type . '_made', $default_key);

        if (!empty($translation)) {
            return $translation;
        }

        return 'revenues.revenue_received';
    }

    protected function getTextPaidAt($type, $textPaidAt)
    {
        if (!empty($textPaidAt)) {
            return $textPaidAt;
        }

        $translation = $this->getTextFromConfig($type, 'paid_at', 'date');

        if (!empty($translation)) {
            return $translation;
        }

        return 'general.date';
    }

    protected function getTextAccount($type, $textAccount)
    {
        if (!empty($textAccount)) {
            return $textAccount;
        }

        $translation = $this->getTextFromConfig($type, 'accounts', 'accounts', 'trans_choice');

        if (!empty($translation)) {
            return $translation;
        }

        return 'general.accounts';
    }

    protected function getTextCategory($type, $textCategory)
    {
        if (!empty($textCategory)) {
            return $textCategory;
        }

        $translation = $this->getTextFromConfig($type, 'categories', 'categories', 'trans_choice');

        if (!empty($translation)) {
            return $translation;
        }

        return 'general.categories';
    }

    protected function getTextPaymentMethods($type, $textPaymentMethods)
    {
        if (!empty($textPaymentMethods)) {
            return $textPaymentMethods;
        }

        $translation = $this->getTextFromConfig($type, 'payment_methods', 'payment_methods', 'trans_choice');

        if (!empty($translation)) {
            return $translation;
        }

        return 'general.payment_methods';
    }

    protected function getTextReference($type, $textReference)
    {
        if (!empty($textReference)) {
            return $textReference;
        }

        $translation = $this->getTextFromConfig($type, 'reference', 'reference');

        if (!empty($translation)) {
            return $translation;
        }

        return 'general.reference';
    }

    protected function getTextDescription($type, $textDescription)
    {
        if (!empty($textDescription)) {
            return $textDescription;
        }

        $translation = $this->getTextFromConfig($type, 'description', 'description');

        if (!empty($translation)) {
            return $translation;
        }

        return 'general.description';
    }

    protected function getTextAmount($type, $textAmount)
    {
        if (!empty($textAmount)) {
            return $textAmount;
        }

        $translation = $this->getTextFromConfig($type, 'amount', 'amount');

        if (!empty($translation)) {
            return $translation;
        }

        return 'general.amount';
    }

    protected function getTextPaidBy($type, $textPaidBy)
    {
        if (!empty($textPaidBy)) {
            return $textPaidBy;
        }

        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $default_key = 'paid_to';
                break;
            default:
                $default_key = 'paid_by';
                break;
        }

        $translation = $this->getTextFromConfig($type, 'paid_to_by', $default_key);

        if (!empty($translation)) {
            return $translation;
        }

        return 'revenues.paid_by';
    }

    protected function getTextReleatedTransansaction($type, $textReleatedTransansaction)
    {
        if (!empty($textReleatedTransansaction)) {
            return $textReleatedTransansaction;
        }

        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $default_key = 'related_bill';
                break;
            default:
                $default_key = 'related_invoice';
                break;
        }

        $translation = $this->getTextFromConfig($type, 'related_type', $default_key);

        if (!empty($translation)) {
            return $translation;
        }

        return 'revenues.related_invoice';
    }

    protected function getTextReleatedDocumentNumber($type, $textReleatedDocumentNumber)
    {
        if (!empty($textReleatedDocumentNumber)) {
            return $textReleatedDocumentNumber;
        }

        $translation = $this->getTextFromConfig($type, 'related_document_number', 'numbers');

        if (!empty($translation)) {
            return $translation;
        }

        return 'general.numbers';
    }

    protected function getTextReleatedContact($type, $textReleatedContact)
    {
        if (!empty($textReleatedContact)) {
            return $textReleatedContact;
        }

        $default_key = Str::plural(config('type.' . $type . '.contact_type'), 2);

        $translation = $this->getTextFromConfig($type, 'related_contact', $default_key, 'trans_choice');

        if (!empty($translation)) {
            return $translation;
        }

        return 'general.customers';
    }

    protected function getTextReleatedDocumentDate($type, $textReleatedDocumentDate)
    {
        if (!empty($textReleatedDocumentDate)) {
            return $textReleatedDocumentDate;
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

        $translation = $this->getTextFromConfig($type, 'related_document_date', $default_key);

        if (!empty($translation)) {
            return $translation;
        }

        return 'invoices.invoice_date';
    }

    protected function getTextReleatedDocumentAmount($type, $textReleatedDocumentAmount)
    {
        if (!empty($textReleatedDocumentAmount)) {
            return $textReleatedDocumentAmount;
        }

        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $default_key = 'bill_amount';
                break;
            default:
                $default_key = 'invoice_amount';
                break;
        }

        $translation = $this->getTextFromConfig($type, 'related_document_amount', $default_key);

        if (!empty($translation)) {
            return $translation;
        }

        return 'general.amount';
    }

    protected function getTextReleatedAmount($type, $textReleatedAmount)
    {
        if (!empty($textReleatedAmount)) {
            return $textReleatedAmount;
        }

        $translation = $this->getTextFromConfig($type, 'related_amount', 'amount');

        if (!empty($translation)) {
            return $translation;
        }

        return 'general.amount';
    }

    protected function routeDocumentShow($type, $routeDocumentShow)
    {
        if (!empty($routeDocumentShow)) {
            return $routeDocumentShow;
        }

        if (!$this->transaction->document) {
            return $routeDocumentShow;
        }

        //example route parameter.
        $parameter = 1;

        $route = $this->getRouteFromConfig($this->transaction->document->type, 'show', $parameter);

        if (!empty($route)) {
            return $route;
        }

        return 'invoices.show';
    }

    protected function getHistories($histories)
    {
        if (!empty($histories)) {
            return $histories;
        }

        $histories[] = $this->transaction;

        return $histories;
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
}
