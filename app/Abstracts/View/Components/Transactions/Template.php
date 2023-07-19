<?php

namespace App\Abstracts\View\Components\Transactions;

use App\Abstracts\View\Component;
use App\Models\Common\Media;
use App\Traits\DateTime;
use App\Traits\Transactions;
use App\Utilities\Modules;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Intervention\Image\Exception\NotReadableException;
use App\Traits\ViewComponents;

abstract class Template extends Component
{
    use DateTime, Transactions, ViewComponents;

    public const OBJECT_TYPE = 'transaction';
    public const DEFAULT_TYPE = 'transaction';
    public const DEFAULT_PLURAL_TYPE = 'transactions';

    /** @var string */
    public $type;

    public $transaction;

    public $logo;

    /** @var array */
    public $payment_methods;

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
    public $hideNumber;

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
    public $textNumber;

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

    /** @var string */
    public $textContactInfo;

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
    public $hideRelated;

    /** @var bool */
    public $hideRelatedDocumentNumber;

    /** @var bool */
    public $hideRelatedContact;

    /** @var bool */
    public $hideRelatedDocumentDate;

    /** @var bool */
    public $hideRelatedDocumentAmount;

    /** @var bool */
    public $hideRelatedAmount;

    /** @var string */
    public $textRelatedTransansaction;

    /** @var string */
    public $textRelatedDocumentNumber;

    /** @var string */
    public $textRelatedContact;

    /** @var string */
    public $textRelatedDocumentDate;

    /** @var string */
    public $textRelatedDocumentAmount;

    /** @var string */
    public $textRelatedAmount;

    /** @var string */
    public $routeDocumentShow;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $type, $transaction, $logo = '', array $payment_methods = [],
        bool $hideCompany = false, bool $hideCompanyLogo = false, bool $hideCompanyDetails = false, bool $hideCompanyName = false, bool $hideCompanyAddress = false,
        bool $hideCompanyTaxNumber = false, bool $hideCompanyPhone = false, bool $hideCompanyEmail = false,
        bool $hideContentTitle = false, bool $hideNumber = false, bool $hidePaidAt = false, bool $hideAccount = false, bool $hideCategory = false, bool $hidePaymentMethods = false, bool $hideReference = false,
        bool $hideDescription = false, bool $hideAmount = false,
        string $textContentTitle = '', string $textNumber = '', string $textPaidAt = '', string $textAccount = '', string $textCategory = '', string $textPaymentMethods = '', string $textReference = '',
        string $textDescription = '', string $textAmount = '', string $textPaidBy = '', string $textContactInfo = '',
        bool $hideContact = false, bool $hideContactInfo = false, bool $hideContactName = false, bool $hideContactAddress = false, bool $hideContactTaxNumber = false,
        bool $hideContactPhone = false, bool $hideContactEmail = false,
        bool $hideRelated = false, bool $hideRelatedDocumentNumber = false, bool $hideRelatedContact = false, bool $hideRelatedDocumentDate = false, bool $hideRelatedDocumentAmount = false, bool $hideRelatedAmount = false,
        string $textRelatedTransansaction = '', string $textRelatedDocumentNumber = '', string $textRelatedContact = '', string $textRelatedDocumentDate = '', string $textRelatedDocumentAmount = '', string $textRelatedAmount = '',
        string $routeDocumentShow = ''
        ) {
        $this->type = $type;
        $this->transaction = $transaction;

        $this->logo = $this->getLogo($logo);
        $this->payment_methods = ($payment_methods) ?: Modules::getPaymentMethods('all');

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
        $this->hideNumber = $hideNumber;
        $this->hidePaidAt = $hidePaidAt;
        $this->hideAccount = $hideAccount;
        $this->hideCategory = $hideCategory;
        $this->hidePaymentMethods = $hidePaymentMethods;
        $this->hideReference = $hideReference;
        $this->hideDescription = $hideDescription;
        $this->hideAmount = $hideAmount;

        // Transaction Information Text
        $this->textContentTitle = $this->getTextContentTitle($type, $textContentTitle);
        $this->textNumber = $this->getTextNumber($type, $textNumber);
        $this->textPaidAt = $this->getTextPaidAt($type, $textPaidAt);
        $this->textAccount = $this->getTextAccount($type, $textAccount);
        $this->textCategory = $this->getTextCategory($type, $textCategory);
        $this->textPaymentMethods = $this->getTextPaymentMethods($type, $textPaymentMethods);
        $this->textReference = $this->getTextReference($type, $textReference);
        $this->textDescription = $this->getTextDescription($type, $textDescription);
        $this->textAmount = $this->getTextAmount($type, $textAmount);
        $this->textPaidBy = $this->getTextPaidBy($type, $textPaidBy);
        $this->textContactInfo = $this->getTextContactInfo($type, $textContactInfo);

        // Contact Information Hide checker
        $this->hideContact = $hideContact;
        $this->hideContactInfo = $hideContactInfo;
        $this->hideContactName = $hideContactName;
        $this->hideContactAddress = $hideContactAddress;
        $this->hideContactTaxNumber = $hideContactTaxNumber;
        $this->hideContactPhone = $hideContactPhone;
        $this->hideContactEmail = $hideContactEmail;

        // Related Information Hide checker
        $this->hideRelated = $hideRelated;
        $this->hideRelatedDocumentNumber = $hideRelatedDocumentNumber;
        $this->hideRelatedContact = $hideRelatedContact;
        $this->hideRelatedDocumentDate = $hideRelatedDocumentDate;
        $this->hideRelatedDocumentAmount = $hideRelatedDocumentAmount;
        $this->hideRelatedAmount = $hideRelatedAmount;

        // Related Information Text
        $this->textRelatedTransansaction = $this->getTextRelatedTransansaction($type, $textRelatedTransansaction);
        $this->textRelatedDocumentNumber = $this->getTextRelatedDocumentNumber($type, $textRelatedDocumentNumber);
        $this->textRelatedContact = $this->getTextRelatedContact($type, $textRelatedContact);
        $this->textRelatedDocumentDate = $this->getTextRelatedDocumentDate($type, $textRelatedDocumentDate);
        $this->textRelatedDocumentAmount = $this->getTextRelatedDocumentAmount($type, $textRelatedDocumentAmount);
        $this->textRelatedAmount = $this->getTextRelatedAmount($type, $textRelatedAmount);

        $this->routeDocumentShow = $this->routeDocumentShow($type, $routeDocumentShow);
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
                $default_key = 'receipts';
                break;
        }

        $translation = $this->getTextFromConfig($type, $type . '_made', $default_key);

        if (!empty($translation)) {
            return $translation;
        }

        return 'general.receipts';
    }

    protected function getTextNumber($type, $textNumber)
    {
        if (!empty($textNumber)) {
            return $textNumber;
        }

        return 'general.numbers';
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

        return 'transactions.paid_by';
    }

    protected function getTextContactInfo($type, $textContactInfo)
    {
        if (!empty($textContactInfo)) {
            return $textContactInfo;
        }

        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $textContactInfo = 'bills.bill_from';
                break;
            default:
                $textContactInfo = 'invoices.bill_to';
                break;
        }

        return $textContactInfo;
    }

    protected function getTextRelatedTransansaction($type, $textRelatedTransansaction)
    {
        if (!empty($textRelatedTransansaction)) {
            return $textRelatedTransansaction;
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

        return 'transactions.related_invoice';
    }

    protected function getTextRelatedDocumentNumber($type, $textRelatedDocumentNumber)
    {
        if (!empty($textRelatedDocumentNumber)) {
            return $textRelatedDocumentNumber;
        }

        $translation = $this->getTextFromConfig($type, 'related_document_number', 'numbers');

        if (!empty($translation)) {
            return $translation;
        }

        return 'general.numbers';
    }

    protected function getTextRelatedContact($type, $textRelatedContact)
    {
        if (!empty($textRelatedContact)) {
            return $textRelatedContact;
        }

        $default_key = Str::plural(config('type.transaction.' . $type . '.contact_type'), 2);

        $translation = $this->getTextFromConfig($type, 'related_contact', $default_key, 'trans_choice');

        if (!empty($translation)) {
            return $translation;
        }

        return 'general.customers';
    }

    protected function getTextRelatedDocumentDate($type, $textRelatedDocumentDate)
    {
        if (!empty($textRelatedDocumentDate)) {
            return $textRelatedDocumentDate;
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

    protected function getTextRelatedDocumentAmount($type, $textRelatedDocumentAmount)
    {
        if (!empty($textRelatedDocumentAmount)) {
            return $textRelatedDocumentAmount;
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

    protected function getTextRelatedAmount($type, $textRelatedAmount)
    {
        if (!empty($textRelatedAmount)) {
            return $textRelatedAmount;
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
}
