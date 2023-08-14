<?php

namespace App\Abstracts\View\Components\Documents;

use App\Abstracts\View\Component;
use App\Interfaces\Utility\DocumentNumber;
use App\Models\Common\Contact;
use App\Models\Document\Document;
use App\Models\Setting\Currency;
use App\Models\Setting\Tax;
use App\Traits\Documents;
use App\Traits\ViewComponents;
use App\Utilities\Date;
use Illuminate\Support\Str;

abstract class Form extends Component
{
    use Documents, ViewComponents;

    public const OBJECT_TYPE = 'document';
    public const DEFAULT_TYPE = 'invoice';
    public const DEFAULT_PLURAL_TYPE = 'invoices';

    /* -- Main Start -- */
    public $type;

    public $document;

    public $model;

    public $currencies;

    public $currency;

    public $currency_code;

    public $taxes;
    /* -- Main End -- */

    /* -- Content Start -- */
    /* -- Form Start -- */
    public $formId;

    public $formRoute;

    public $formMethod;
    /* -- Form End -- */

    /* -- Company Start -- */
    /** @var bool */
    public $hideCompany;

    /** @var string */
    public $textSectionCompaniesTitle;

    /** @var string */
    public $textSectionCompaniesDescription;

    /** @var bool */
    public $hideLogo;

    /** @var bool */
    public $hideCompanyEdit;
    /* -- Company End -- */

    /* -- Main Start -- */
    /** @var string */
    public $textSectionMainTitle;

    /** @var string */
    public $textSectionMainDescription;

    /* -- Metadata Start -- */
    /** @var string */
    public $typeContact;

    /** @var string */
    public $textContact;

    public $contact;

    public $contacts;

    /** @var string */
    public $searchContactRoute;

    /** @var string */
    public $createContactRoute;

    /** @var string */
    public $textAddContact;

    /** @var string */
    public $textCreateNewContact;

    /** @var string */
    public $textEditContact;

    /** @var string */
    public $textContactInfo;

    /** @var string */
    public $textChooseDifferentContact;

    /** @var bool */
    public $hideDocumentTitle;

    /** @var bool */
    public $hideDocumentSubheading;

    /** @var string */
    public $title;

    /** @var string */
    public $subheading;

    /** @var bool */
    public $hideIssuedAt;

    /** @var string */
    public $textIssuedAt;

    /** @var string */
    public $issuedAt;

    /** @var bool */
    public $hideDueAt;

    /** @var string */
    public $textDueAt;

    /** @var string */
    public $dueAt;

    /** @var string */
    public $periodDueAt;

    /** @var bool */
    public $hideDocumentNumber;

    /** @var string */
    public $textDocumentNumber;

    /** @var string */
    public $documentNumber;

    /** @var bool */
    public $hideOrderNumber;

    /** @var string */
    public $textOrderNumber;

    /** @var string */
    public $orderNumber;
    /* -- Metadata End -- */

    /* -- Items Start -- */
    /** @var bool */
    public $hideEditItemColumns;

    /** @var bool */
    public $hideItems;

    /** @var bool */
    public $hideItemName;

    /** @var bool */
    public $hideSettingItemName;

    /** @var string */
    public $textItemName;

    /** @var bool */
    public $hideItemDescription;

    /** @var bool */
    public $hideSettingItemDescription;

    /** @var string */
    public $textItemDescription;

    /** @var bool */
    public $hideItemQuantity;

    /** @var bool */
    public $hideSettingItemQuantity;

    /** @var string */
    public $textItemQuantity;

    /** @var bool */
    public $hideItemPrice;

    /** @var bool */
    public $hideSettingItemPrice;

    /** @var string */
    public $textItemPrice;

    /** @var bool */
    public $hideItemAmount;

    /** @var bool */
    public $hideSettingItemAmount;

    /** @var string */
    public $textItemAmount;

    /** @var bool */
    public $hideDiscount;

    /** @var bool */
    public $isSalePrice;

    /** @var bool */
    public $isPurchasePrice;

    /** @var int */
    public $searchCharLimit;
    /* -- Items End -- */

    /** @var string */
    public $notes;
    /* -- Main End -- */

    /* -- Recurring Start -- */
    /** @var bool */
    public $showRecurring;
    /* -- End Start -- */

    /* -- Advanced Start -- */
    /** @var bool */
    public $hideAdvanced;

    /** @var string */
    public $textSectionAdvancedTitle;

    /** @var string */
    public $textSectionAdvancedDescription;

    /** @var bool */
    public $hideFooter;

    /** @var string */
    public $classFooter;

    /** @var string */
    public $footer;

    /** @var bool */
    public $hideCategory;

    /** @var string */
    public $classCategory;

    /** @var string */
    public $typeCategory;

    public $categoryId;

    /** @var bool */
    public $hideAttachment;

    /** @var string */
    public $classAttachment;
    /* -- Advanced End -- */

    /* -- Buttons End -- */
    /** @var bool */
    public $hideButtons;

    /** @var string */
    public $cancelRoute;

    /** @var bool */
    public $hideSendTo;
    /* -- Buttons End -- */

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $type, $model = false, $document = false, $currencies = false, $currency = false, $currency_code = false,
        string $formId = 'document', $formRoute = '', $formMethod = '',
        bool $hideCompany = false, string $textSectionCompaniesTitle = '', string $textSectionCompaniesDescription = '',
        bool $hideLogo = false, bool $hideCompanyEdit = false,
        string $textSectionMainTitle = '', string $textSectionMainDescription = '',
        string $typeContact = '', string $textContact = '', $contacts = [], $contact = false, string $searchContactRoute = '', string $createContactRoute = '',
        string $textAddContact = '', string $textCreateNewContact = '', string $textEditContact = '', string $textContactInfo = '', string $textChooseDifferentContact = '',
        bool $hideDocumentTitle = false, bool $hideDocumentSubheading = false, string $title = '', string $subheading = '',
        bool $hideIssuedAt = false, string $textIssuedAt = '', string $issuedAt = '', bool $hideDueAt = false, string $textDueAt = '', string $dueAt = '', $periodDueAt = '',
        bool $hideDocumentNumber = false, string $textDocumentNumber = '', string $documentNumber = '', bool $hideOrderNumber = false, string $textOrderNumber = '', string $orderNumber = '',
        bool $hideEditItemColumns = false, bool $hideItems = false, bool $hideItemName = false, bool $hideSettingItemName = false, string $textItemName = '', bool $hideItemDescription = false, bool $hideSettingItemDescription = false, string $textItemDescription = '',
        bool $hideItemQuantity = false, bool $hideSettingItemQuantity = false, string $textItemQuantity = '', bool $hideItemPrice = false, bool $hideSettingItemPrice = false, string $textItemPrice = '', bool $hideItemAmount = false, bool $hideSettingItemAmount = false, string $textItemAmount = '',
        bool $hideDiscount = false, bool $isSalePrice = false, bool $isPurchasePrice = false, int $searchCharLimit = 0, string $notes = '',
        bool $showRecurring = false,
        bool $hideAdvanced = false, string $textSectionAdvancedTitle = '', string $textSectionAdvancedDescription = '',
        bool $hideFooter = false, string $classFooter = '', string $footer = '',
        bool $hideCategory = false, string $classCategory = '', string $typeCategory = '', $categoryId = '',
        bool $hideAttachment = false, string $classAttachment = '',
        bool $hideButtons = false, string $cancelRoute = '', $hideSendTo = false
    ) {
        $this->type = $type;

        $this->model = ! empty($model) ? $model : $document;
        $this->document = $this->model;

        $this->currency_code = ! empty($this->currency) ? $this->currency->code : default_currency();
        $this->currency = $this->getCurrency($document, $currency, $currency_code);
        $this->currencies = $this->getCurrencies($currencies);

        $this->taxes = Tax::enabled()->orderBy('name')->get()->pluck('title', 'id');

        /* -- Content Start -- */
        /* -- Form Start -- */
        $this->formId = $formId;
        $this->formRoute = $this->getFormRoute($type, $formRoute, $this->model);
        $this->formMethod = $this->getFormMethod($type, $formMethod, $this->model);
        /* -- Form End -- */

        /* -- Company Start -- */
        $this->hideCompany = $hideCompany;
        $this->textSectionCompaniesTitle = $this->getTextSectionCompaniesTitle($type, $textSectionCompaniesTitle);
        $this->textSectionCompaniesDescription = $this->getTextSectionCompaniesDescription($type, $textSectionCompaniesDescription);
        $this->hideLogo = $hideLogo;
        $this->hideCompanyEdit = $hideCompanyEdit;
        /** Company End */

        /* -- Main Start -- */
        $this->textSectionMainTitle = $this->getTextSectionMainTitle($type, $textSectionMainTitle);
        $this->textSectionMainDescription = $this->getTextSectionMainDescription($type, $textSectionMainDescription);

        /* -- Metadata Start -- */
        $this->typeContact = $this->getTypeContact($type, $typeContact);
        $this->contact = $this->getContact($contact, $document);
        $this->contacts = $this->getContacts($type, $document, $contacts);

        $this->searchContactRoute = $this->getSearchContactRoute($type, $searchContactRoute);
        $this->createContactRoute = $this->getCreateContactRoute($type, $createContactRoute);

        $this->textContact = $this->getTextContact($type, $textContact);
        $this->textAddContact = $this->getTextAddContact($type, $textAddContact);
        $this->textCreateNewContact = $this->getTextCreateNewContact($type, $textCreateNewContact);
        $this->textEditContact = $this->getTextEditContact($type, $textEditContact);
        $this->textContactInfo = $this->getTextContactInfo($type, $textContactInfo);
        $this->textChooseDifferentContact = $this->getTextChooseDifferentContact($type, $textChooseDifferentContact);

        $this->hideDocumentTitle = $hideDocumentTitle;
        $this->hideDocumentSubheading = $hideDocumentSubheading;
        $this->title = $this->getTitleValue($type, $title);
        $this->subheading = $this->getSubheadingValue($type, $subheading);

        $this->hideIssuedAt = $hideIssuedAt;
        $this->textIssuedAt = $this->getTextIssuedAt($type, $textIssuedAt);
        $this->issuedAt = $this->getIssuedAt($type, $document, $issuedAt);

        $this->hideDueAt = $hideDueAt;
        $this->textDueAt = $this->getTextDueAt($type, $textDueAt);
        $this->dueAt = $this->getDueAt($type, $document, $dueAt);
        $this->periodDueAt = $this->getPeriodDueAt($type, $periodDueAt);

        $this->hideDocumentNumber = $hideDocumentNumber;
        $this->textDocumentNumber = $this->getTextDocumentNumber($type, $textDocumentNumber);
        $this->documentNumber = $this->getDocumentNumber($type, $document, $documentNumber);

        $this->hideOrderNumber = $hideOrderNumber;
        $this->textOrderNumber = $this->getTextOrderNumber($type, $textOrderNumber);
        $this->orderNumber = $this->getOrderNumber($type, $document, $orderNumber);
        /* -- Metadata End -- */

        /** Items Start */
        $this->hideEditItemColumns = $hideEditItemColumns;

        $this->hideItems = $this->getHideItems($type, $hideItems, $hideItemName, $hideItemDescription);
        $this->hideItemName = $this->getHideItemName($type, $hideItemName);
        $this->hideSettingItemName = $this->getHideSettingItemName($type, $hideSettingItemName);
        $this->textItemName = $this->getTextItemName($type, $textItemName);

        $this->hideItemDescription = $this->getHideItemDescription($type, $hideItemDescription);
        $this->hideSettingItemDescription = $this->getHideSettingItemDescription($type, $hideSettingItemDescription);
        $this->textItemDescription = $this->getTextItemDescription($type, $textItemDescription);

        $this->hideItemQuantity = $this->getHideItemQuantity($type, $hideItemQuantity);
        $this->hideSettingItemQuantity = $this->getHideSettingItemQuantity($type, $hideSettingItemQuantity);
        $this->textItemQuantity = $this->getTextItemQuantity($type, $textItemQuantity);

        $this->hideItemPrice = $this->getHideItemPrice($type, $hideItemPrice);
        $this->hideSettingItemPrice = $this->getHideSettingItemPrice($type, $hideSettingItemPrice);
        $this->textItemPrice = $this->getTextItemPrice($type, $textItemPrice);

        $this->hideItemAmount = $this->getHideItemAmount($type, $hideItemAmount);
        $this->hideSettingItemAmount = $this->getHideSettingItemAmount($type, $hideSettingItemAmount);
        $this->textItemAmount = $this->getTextItemAmount($type, $textItemAmount);

        $this->hideDiscount = $this->getHideDiscount($type, $hideDiscount);

        $this->isSalePrice = $isSalePrice;
        $this->isPurchasePrice = $isPurchasePrice;
        $this->searchCharLimit = $this->getSearchCharLimit($type, $searchCharLimit);
        /** Items End */

        /** Notes Start */
        $this->notes = $this->getNotesValue($notes);
        /** Notes End */
        /** Main End */

        /* -- Recurring Start -- */
        $this->showRecurring = $showRecurring;
        /* -- Recurring End -- */

        /* -- Advanced Start -- */
        $this->hideAdvanced = $hideAdvanced;
        $this->textSectionAdvancedTitle = $this->getTextSectionAdvancedTitle($type, $textSectionAdvancedTitle);
        $this->textSectionAdvancedDescription = $this->getTextSectionAdvancedDescription($type, $textSectionAdvancedDescription);

        $this->hideFooter = $hideFooter;
        $this->classFooter = !empty($classFooter) ? $classFooter : 'sm:col-span-3';
        $this->footer = $this->getFooterValue($footer);

        $this->hideCategory = $hideCategory;
        $this->classCategory = !empty($classCategory) ? $classCategory : 'sm:col-span-4 grid gap-x-8 gap-y-3';
        $this->typeCategory = $this->getTypeCategory($type, $typeCategory);
        $this->categoryId = $this->getCategoryId($type, $categoryId);

        $this->hideAttachment = $hideAttachment;
        $this->classAttachment = !empty($classAttachment) ? $classAttachment : 'sm:col-span-4';
        /** Advanced End */

        /** Buttons Start */
        $this->hideButtons = $hideButtons;
        $this->cancelRoute = $this->getCancelRoute($type, $cancelRoute);
        $this->hideSendTo = $hideSendTo;
        /** Buttons End */
        /* -- Content End -- */

        // Set Parent data
        $this->setParentData();
    }

    protected function getCurrencies($currencies)
    {
        if (!empty($currencies)) {
            return $currencies;
        }

        return Currency::enabled()->pluck('name', 'code');
    }

    protected function getCurrency($document, $currency, $currency_code)
    {
        if (! empty($currency)) {
            return $currency;
        }

        if (! empty($currency_code)) {
            $currency = Currency::where('code', $currency_code)->first();
        }

        if (empty($currency) && ! empty($document)) {
            $currency = Currency::where('code', $document->currency_code)->first();
        }

        if (empty($currency)) {
            $currency = Currency::where('code', default_currency())->first();
        }

        return $currency;
    }

    protected function getTextSectionCompaniesTitle($type, $textSectionCompaniesTitle)
    {
        if (! empty($textSectionCompaniesTitle)) {
            return $textSectionCompaniesTitle;
        }

        return $this->getTextSectionTitle($type, 'companies', 'general.companies');
    }

    protected function getTextSectionCompaniesDescription($type, $textSectionCompaniesDescription)
    {
        if (! empty($textSectionCompaniesDescription)) {
            return $textSectionCompaniesDescription;
        }

        return $this->getTextSectionDescription($type, 'companies', 'documents.form_description.companies');
    }

    protected function getTextSectionMainTitle($type, $textSectionMainTitle)
    {
        if (! empty($textSectionMainTitle)) {
            return $textSectionMainTitle;
        }

        return $this->getTextSectionTitle($type, 'main', 'documents.billing');
    }

    protected function getTextSectionMainDescription($type, $textSectionMainDescription)
    {
        if (! empty($textSectionMainDescription)) {
            return $textSectionMainDescription;
        }

        return $this->getTextSectionDescription($type, 'billing', 'documents.form_description.billing');
    }

    protected function getTypeContact($type, $typeContact)
    {
        if (! empty($typeContact)) {
            return $typeContact;
        }

        return config('type.' . static::OBJECT_TYPE . '.' . $type . '.contact_type', 'customer');
    }

    protected function getTextContact($type, $textContact)
    {
        if (! empty($textContact)) {
            return $textContact;
        }

        $contact_type = config('type.' . static::OBJECT_TYPE . '.' . $type . '.contact_type');

        $default_key = config('type.contact.' . $contact_type . '.translation.prefix');

        $translation = $this->getTextFromConfig($type, 'contact', $default_key);

        if (!empty($translation)) {
            return $translation;
        }

        return 'general.customers';
    }

    protected function getContact($contact, $document)
    {
        if (!empty($contact)) {
            return $contact;
        }

        $contact = new \stdClass();

        if (!empty($document) && !empty($document->contact)) {
            $contact = $document->contact;
        }

        if (request()->old('contact', false)) {
            $contact = request()->old('contact');
        }

        return $contact;
    }

    protected function getContacts($type, $document, $contacts)
    {
        if (!empty($contacts)) {
            return $contacts;
        }

        $contact_type = $this->getTypeContact($type, null);

        if ($contact_type) {
            $contacts = Contact::$contact_type()->enabled()->orderBy('name')->take(setting('default.select_limit'))->get();
        } else {
            $contacts = Contact::enabled()->orderBy('name')->take(setting('default.select_limit'))->get();
        }

        if (!empty($document) && ($document->contact && !$contacts->contains('id', $document->contact_id))) {
            $contacts->push($document->contact);
        }

        return $contacts;
    }

    protected function getSearchContactRoute($type, $searchContactRoute)
    {
        if (! empty($searchContactRoute)) {
            return $searchContactRoute;
        }

        $contact_type = config('type.' . static::OBJECT_TYPE . '.' . $type . '.contact_type');

        $default_key = config('type.contact.' . $contact_type . '.route.prefix');

        return route($default_key . '.index');
    }

    protected function getCreateContactRoute($type, $createContactRoute)
    {
        if (! empty($createContactRoute)) {
            return $createContactRoute;
        }

        $contact_type = config('type.' . static::OBJECT_TYPE . '.' . $type . '.contact_type');

        $default_key = config('type.contact.' . $contact_type . '.route.prefix');

        return route('modals.' . $default_key . '.create');
    }

    protected function getTextAddContact($type, $textAddContact)
    {
        if (!empty($textAddContact)) {
            return $textAddContact;
        }

        $default_key = Str::plural(config('type.document.' . $type . '.contact_type'), 2);

        $translation = $this->getTextFromConfig($type, 'add_contact', $default_key, 'trans_choice');

        if (!empty($translation)) {
            return [
                'general.form.add',
                $translation,
            ];
        }

        return [
            'general.form.add',
            'general.customers',
        ];
    }

    protected function getTextCreateNewContact($type, $textCreateNewContact)
    {
        if (!empty($textCreateNewContact)) {
            return $textCreateNewContact;
        }

        $default_key = Str::plural(config('type.document.' . $type . '.contact_type'), 2);

        $translation = $this->getTextFromConfig($type, 'create_new_contact', $default_key, 'trans_choice');

        if (!empty($translation)) {
            return [
                'general.title.new',
                $translation,
            ];
        }

        return 'general.title.add';
    }

    protected function getTextEditContact($type, $textEditContact)
    {
        if (!empty($textEditContact)) {
            return $textEditContact;
        }

        $translation = $this->getTextFromConfig($type, 'edit_contact', 'form.edit');

        if (!empty($translation)) {
            return $translation;
        }

        return 'general.form.edit';
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
                $default_key = 'bill_from';
                break;
            default:
                $default_key = 'bill_to';
                break;
        }

        $translation = $this->getTextFromConfig($type, 'contact_info', $default_key);

        if (!empty($translation)) {
            return $translation;
        }

        return 'invoices.bill_to';
    }

    protected function getTextChooseDifferentContact($type, $textChooseDifferentContact)
    {
        if (!empty($textChooseDifferentContact)) {
            return $textChooseDifferentContact;
        }

        $default_key = Str::plural(config('type.document.' . $type . '.contact_type'), 2);

        $translation = $this->getTextFromConfig($type, 'choose_different_contact', $default_key, 'trans_choice');

        if (!empty($translation)) {
            return [
                'general.form.choose_different',
                $translation,
            ];
        }

        return [
            'general.form.choose_different',
            'general.customers',
        ];
    }

    protected function getTextIssuedAt($type, $textIssuedAt)
    {
        if (!empty($textIssuedAt)) {
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

        if (!empty($translation)) {
            return $translation;
        }

        return 'invoices.invoice_date';
    }

    protected function getIssuedAt($type, $document, $issuedAt)
    {
        if (!empty($issuedAt)) {
            return $issuedAt;
        }

        if ($document) {
            return $document->issued_at;
        }

        $issued_at = $type . '_at';

        if (request()->has($issued_at)) {
            $issuedAt = request()->get($issued_at);
        } else {
            $issuedAt = request()->get('invoice_at', Date::now()->toDateString());
        }

        return $issuedAt;
    }

    protected function getTextDueAt($type, $textDueAt)
    {
        if (!empty($textDueAt)) {
            return $textDueAt;
        }

        $translation = $this->getTextFromConfig($type, 'due_at', 'due_date');

        if (!empty($translation)) {
            return $translation;
        }

        return 'invoices.due_date';
    }

    protected function getDueAt($type, $document, $dueAt)
    {
        if (!empty($dueAt)) {
            return $dueAt;
        }

        if ($document) {
            return $document->due_at;
        }

        $issued_at = $type . '_at';

        if (request()->has($issued_at)) {
            $issuedAt = request()->get($issued_at);
        } else {
            $issuedAt = Date::now()->toDateString();
        }

        $addDays = setting($this->getDocumentSettingKey($type, 'payment_terms'), 0) ?: 0;

        $dueAt = Date::parse($issuedAt)->addDays($addDays)->toDateString();

        return $dueAt;
    }

    protected function getPeriodDueAt($type, $periodDueAt)
    {
        if (! empty($periodDueAt)) {
            return $periodDueAt;
        }

        return setting($type. '.payment_terms', 0);
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

        return 'invoices.invoice_number';
    }

    protected function getDocumentNumber($type, $document, $documentNumber)
    {
        if (! empty($documentNumber)) {
            return $documentNumber;
        }

        if ($document) {
            return $document->document_number;
        }

        $contact = ($this->contact instanceof Contact) ? $this->contact : null;

        $utility = app(DocumentNumber::class);

        $document_number = $utility->getNextNumber($type, $contact);

        if (empty($document_number)) {
            $document_number = $utility->getNextNumber(Document::INVOICE_TYPE, $contact);
        }

        return $document_number;
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

    protected function getOrderNumber($type, $document, $orderNumber)
    {
        if (! empty($orderNumber)) {
            return $orderNumber;
        }

        if ($document) {
            return $document->order_number;
        }

        $order_number = null;
    }

    protected function getHideItems($type, $hideItems, $hideItemName, $hideItemDescription)
    {
        if (! empty($hideItems)) {
            return $hideItems;
        }

        $hide = $this->getHideFromConfig($type, 'items');

        if ($hide) {
            return $hide;
        }

        $hideItems = ($this->getHideItemName($type, $hideItemName) & $this->getHideItemDescription($type, $hideItemDescription)) ? true  : false;

        return $hideItems;
    }

    protected function getHideItemName($type, $hideItemName): bool
    {
        if (! empty($hideItemName)) {
            return $hideItemName;
        }

        $hide = $this->getHideFromConfig($type, 'name');

        if ($hide) {
            return $hide;
        }

        return false;
    }

    protected function getHideSettingItemName($type, $hideSettingItemName): bool
    {
        if (! empty($hideSettingItemName)) {
            return $hideSettingItemName;
        }

        $hideItemName = setting($this->getDocumentSettingKey($type, 'item_name'), false);

        // if you use settting translation
        if ($hideItemName === 'hide') {
            return true;
        }

        return false;
    }

    protected function getTextItemName($type, $textItemName)
    {
        if (! empty($textItemName)) {
            return $textItemName;
        }

        // if you use settting translation
        if (setting($this->getDocumentSettingKey($type, 'item_name'), 'items') === 'custom') {
            if (empty($textItemName = setting($this->getDocumentSettingKey($type, 'item_name_input')))) {
                $textItemName = 'general.items';
            }

            return $textItemName;
        }

        if (setting($this->getDocumentSettingKey($type, 'item_name')) !== null
            && (trans(setting($this->getDocumentSettingKey($type, 'item_name'))) != setting($this->getDocumentSettingKey($type, 'item_name')))
        ) {
            return setting($this->getDocumentSettingKey($type, 'item_name'));
        }

        $translation = $this->getTextFromConfig($type, 'items');

        if (! empty($translation)) {
            return $translation;
        }

        return 'general.items';
    }

    protected function getHideItemDescription($type, $hideItemDescription): bool
    {
        if (! empty($hideItemDescription)) {
            return $hideItemDescription;
        }

        $hide = $this->getHideFromConfig($type, 'description');

        if ($hide) {
            return $hide;
        }

        return false;
    }

    protected function getHideSettingItemDescription($type, $hideSettingItemDescription): bool
    {
        if (! empty($hideSettingItemDescription)) {
            return $hideSettingItemDescription;
        }

        // if you use settting translation
        if (setting($this->getDocumentSettingKey($type, 'hide_item_description'), false)) {
            return true;
        }

        return false;
    }

    protected function getTextItemDescription($type, $textItemDescription)
    {
        if (! empty($textItemDescription)) {
            return $textItemDescription;
        }

        $translation = $this->getTextFromConfig($type, 'description');

        if (! empty($translation)) {
            return $translation;
        }

        return 'general.description';
    }

    protected function getHideItemQuantity($type, $hideItemQuantity): bool
    {
        if (! empty($hideItemQuantity)) {
            return $hideItemQuantity;
        }

        $hide = $this->getHideFromConfig($type, 'quantity');

        if ($hide) {
            return $hide;
        }

        return false;
    }

    protected function getHideSettingItemQuantity($type, $hideSettingItemQuantity): bool
    {
        if (! empty($hideSettingItemQuantity)) {
            return $hideSettingItemQuantity;
        }

        $hideItemQuantity = setting($this->getDocumentSettingKey($type, 'quantity_name'), false);

        // if you use settting translation
        if ($hideItemQuantity === 'hide') {
            return true;
        }

        return false;
    }

    protected function getTextItemQuantity($type, $textItemQuantity)
    {
        if (! empty($textItemQuantity)) {
            return $textItemQuantity;
        }

        // if you use settting translation
        if (setting($this->getDocumentSettingKey($type, 'quantity_name'), 'quantity') === 'custom') {
            if (empty($textItemQuantity = setting($this->getDocumentSettingKey($type, 'quantity_name_input')))) {
                $textItemQuantity = 'invoices.quantity';
            }

            return $textItemQuantity;
        }

        if (setting($this->getDocumentSettingKey($type, 'quantity_name')) !== null
            && (trans(setting($this->getDocumentSettingKey($type, 'quantity_name'))) != setting($this->getDocumentSettingKey($type, 'quantity_name')))
        ) {
            return setting($this->getDocumentSettingKey($type, 'quantity_name'));
        }

        $translation = $this->getTextFromConfig($type, 'quantity');

        if (! empty($translation)) {
            return $translation;
        }

        return 'invoices.quantity';
    }

    protected function getHideItemPrice($type, $hideItemPrice): bool
    {
        if (! empty($hideItemPrice)) {
            return $hideItemPrice;
        }

        $hide = $this->getHideFromConfig($type, 'price');

        if ($hide) {
            return $hide;
        }

        return false;
    }

    protected function getHideSettingItemPrice($type, $hideSettingItemPrice): bool
    {
        if (! empty($hideSettingItemPrice)) {
            return $hideSettingItemPrice;
        }

        $hideItemPrice = setting($this->getDocumentSettingKey($type, 'price_name'), false);

        // if you use settting translation
        if ($hideItemPrice === 'hide') {
            return true;
        }

        return false;
    }

    protected function getTextItemPrice($type, $textItemPrice)
    {
        if (! empty($textItemPrice)) {
            return $textItemPrice;
        }

        // if you use settting translation
        if (setting($this->getDocumentSettingKey($type, 'price_name'), 'price') === 'custom') {
            if (empty($textItemPrice = setting($this->getDocumentSettingKey($type, 'price_name_input')))) {
                $textItemPrice = 'invoices.price';
            }

            return $textItemPrice;
        }

        if (setting($this->getDocumentSettingKey($type, 'price_name')) !== null
            && (trans(setting($this->getDocumentSettingKey($type, 'price_name'))) != setting($this->getDocumentSettingKey($type, 'price_name')))
        ) {
            return setting($this->getDocumentSettingKey($type, 'price_name'));
        }

        $translation = $this->getTextFromConfig($type, 'price');

        if (! empty($translation)) {
            return $translation;
        }

        return 'invoices.price';
    }

    protected function getHideItemAmount($type, $hideItemAmount): bool
    {
        if (! empty($hideItemAmount)) {
            return $hideItemAmount;
        }

        $hide = $this->getHideFromConfig($type, 'amount');

        if ($hide) {
            return $hide;
        }

        return false;
    }

    protected function getHideSettingItemAmount($type, $hideSettingItemAmount): bool
    {
        if (! empty($hideSettingItemAmount)) {
            return $hideSettingItemAmount;
        }

        // if you use settting translation
        if (setting($this->getDocumentSettingKey($type, 'hide_amount'), false)) {
            return true;
        }

        return false;
    }

    protected function getTextItemAmount($type, $textItemAmount)
    {
        if (!empty($textItemAmount)) {
            return $textItemAmount;
        }

        $translation = $this->getTextFromConfig($type, 'amount');

        if (!empty($translation)) {
            return $translation;
        }

        return 'general.amount';
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

    protected function getSearchCharLimit($type, $searchCharLimit)
    {
        if (! empty($searchCharLimit)) {
            return $searchCharLimit;
        }

        // if you use settting translation
        if ($settingCharLimit = setting($this->getDocumentSettingKey($type, 'item_search_chart_limit'), false)) {
            return $settingCharLimit;
        }

        $hide = $this->getHideFromConfig($type, 'item_search_char_limit');

        if ($hide) {
            return $hide;
        }

        // @todo what return value invoice or always false??
        return setting('invoice.item_search_char_limit', $searchCharLimit);
    }

    protected function getNotesValue($notes)
    {
        if (! empty($notes)) {
            return $notes;
        }

        if (! empty($this->document)) {
            return $this->document->notes;
        }

        return setting($this->getDocumentSettingKey($this->type, 'notes'));
    }

    protected function getTextSectionAdvancedTitle($type, $textSectionAdvancedTitle)
    {
        if (! empty($textSectionAdvancedTitle)) {
            return $textSectionAdvancedTitle;
        }

        return $this->getTextSectionTitle($type, 'advanced', 'documents.advanced');
    }

    protected function getTextSectionAdvancedDescription($type, $textSectionAdvancedDescription)
    {
        if (! empty($textSectionAdvancedDescription)) {
            return $textSectionAdvancedDescription;
        }

        return $this->getTextSectionDescription($type, 'advanced', 'documents.form_description.advanced');
    }

    protected function getTitleValue($type, $title)
    {
        if (! empty($title)) {
            return $title;
        }

        if (! empty($this->document) && $this->document->title !== '') {
            return $this->document->title;
        }

        return setting($this->getDocumentSettingKey($type, 'title'));
    }

    protected function getSubheadingValue($type, $subheading)
    {
        if (! empty($subheading)) {
            return $subheading;
        }

        if (! empty($this->document) && $this->document->title !== '') {
            return $this->document->subheading;
        }

        return setting($this->getDocumentSettingKey($type, 'subheading'));
    }

    protected function getFooterValue($footer)
    {
        if (! empty($footer)) {
            return $footer;
        }

        if (! empty($this->document)) {
            return $this->document->footer;
        }

        return setting($this->getDocumentSettingKey($this->type, 'footer'));
    }

    protected function getTypeCategory($type, $typeCategory)
    {
        if (!empty($typeCategory)) {
            return $typeCategory;
        }

        if ($category_type = config('type.' . static::OBJECT_TYPE . '.' . $type . '.category_type')) {
            return $category_type;
        }

        // set default type
        $type = Document::INVOICE_TYPE;

        return config('type.' . static::OBJECT_TYPE .'.' . $type . '.category_type');
    }

    protected function getCategoryId($type, $categoryId)
    {
        if (!empty($categoryId)) {
            return $categoryId;
        }

        if (! empty($this->document) && ! empty($this->document->category_id)) {
            return $this->document->category_id;
        }

        return setting('default.' . $this->typeCategory . '_category');
    }
}
