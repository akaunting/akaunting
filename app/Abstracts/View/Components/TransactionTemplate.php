<?php

namespace App\Abstracts\View\Components;

use App\Abstracts\View\Components\Transaction as Base;
use App\Models\Common\Media;
use App\Traits\DateTime;
use App\Traits\Transactions;
use App\Utilities\Modules;
use File;
use Illuminate\Support\Facades\Log;
use Image;
use Intervention\Image\Exception\NotReadableException;
use Storage;
use Illuminate\Support\Str;

abstract class TransactionTemplate extends Base
{
    use DateTime;
    use Transactions;

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

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $type, $transaction, $logo = '', array $payment_methods = [],
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
}
