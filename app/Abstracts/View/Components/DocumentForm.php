<?php

namespace App\Abstracts\View\Components;

use App\Abstracts\View\Components\Document as Base;
use App\Models\Common\Contact;
use App\Models\Document\Document;
use App\Models\Setting\Currency;
use App\Traits\Documents;
use Date;
use Illuminate\Support\Str;

abstract class DocumentForm extends Base
{
    use Documents;

    public $type;

    public $document;

    public $currencies;

    public $currency;

    public $currency_code;

    /** Advanced Component Start */
    /** @var string */
    public $categoryType;

    /** @var string */
    public $textAdvancedAccordion;

    /** @var bool */
    public $hideRecurring;

    /** @var bool */
    public $hideCategory;

    /** @var bool */
    public $hideAttachment;
    /** Advanced Component End */

    /** Company Component Start */
    /** @var bool */
    public $hideLogo;

    /** @var bool */
    public $hideDocumentTitle;

    /** @var bool */
    public $hideDocumentSubheading;

    /** @var bool */
    public $hideCompanyEdit;

    /** @var string */
    public $titleSetting;

    /** @var string */
    public $subheadingSetting;
    /** Company Component End */

    /** Content Component Start */
    /** @var string */
    public $routeStore;

    /** @var string */
    public $routeUpdate;

    /** @var string */
    public $routeCancel;

    /** @var string */
    public $formId;

    /** @var string */
    public $formSubmit;

    /** @var bool */
    public $hideCompany;

    /** @var bool */
    public $hideAdvanced;

    /** @var bool */
    public $hideFooter;

    /** @var bool */
    public $hideButtons;

    /** @var string */
    public $footerSetting;

    /** @var string */
    public $notesSetting;
    /** Content Component End */

    /** Metadata Component Start */
    public $contacts;

    public $contact;

    /** @var string */
    public $contactType;

    /** @var string */
    public $contactSearchRoute;

    /** @var string */
    public $contactCreateRoute;

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
    public $hideContact;

    /** @var bool */
    public $hideIssuedAt;

    /** @var bool */
    public $hideDocumentNumber;

    /** @var bool */
    public $hideDueAt;

    /** @var bool */
    public $hideOrderNumber;

    /** @var string */
    public $issuedAt;

    /** @var string */
    public $documentNumber;

    /** @var string */
    public $dueAt;

    /** @var string */
    public $orderNumber;

    /** @var string */
    public $textIssuedAt;

    /** @var string */
    public $textDueAt;

    /** @var string */
    public $textDocumentNumber;

    /** @var string */
    public $textOrderNumber;
    /** Metadata Component End */

    /** Items Component Start */
    /** @var bool */
    public $hideEditItemColumns;

    /** @var string */
    public $textItems;

    /** @var string */
    public $textQuantity;

    /** @var string */
    public $textPrice;

    /** @var string */
    public $textAmount;

    /** @var bool */
    public $hideItems;

    /** @var bool */
    public $hideName;

    /** @var bool */
    public $hideDescription;

    /** @var bool */
    public $hideQuantity;

    /** @var bool */
    public $hidePrice;

    /** @var bool */
    public $hideDiscount;

    /** @var bool */
    public $hideAmount;

    /** @var bool */
    public $isSalePrice;

    /** @var bool */
    public $isPurchasePrice;

    /** @var int */
    public $searchCharLimit;
    /** Items Component End */

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $type, $document = false, $currencies = false, $currency = false, $currency_code = false,
        /** Advanced Component Start */
        string $categoryType = '', string $textAdvancedAccordion = '', bool $hideRecurring = false, bool $hideCategory = false, bool $hideAttachment = false,
        /** Advanced Component End */
        /** Company Component Start */
        bool $hideLogo = false, bool $hideDocumentTitle = false, bool $hideDocumentSubheading = false, bool $hideCompanyEdit = false,
        string $titleSetting = '', string $subheadingSetting = '',
        /** Company Component End */
        /** Content Component Start */
        string $routeStore = '', string $routeUpdate = '', string $formId = 'document', string $formSubmit = 'onSubmit', string $routeCancel = '',
        bool $hideCompany = false, bool $hideAdvanced = false, bool $hideFooter = false, bool $hideButtons = false,
        string $footerSetting = '', string $notesSetting = '',
        /** Content Component End */
        /** Metadata Component Start */
        $contacts = [], $contact = false, string $contactType = '', string $contactSearchRoute = '', string $contactCreateRoute = '',
        string $textAddContact = '', string $textCreateNewContact = '', string $textEditContact = '', string $textContactInfo = '', string $textChooseDifferentContact = '',
        bool $hideContact = false, bool $hideIssuedAt = false, bool $hideDocumentNumber = false, bool $hideDueAt = false, bool $hideOrderNumber = false,
        string $textDocumentNumber = '', string $textOrderNumber = '', string $textIssuedAt = '', string $textDueAt = '',
        string $issuedAt = '', string $documentNumber = '', string $dueAt = '', string $orderNumber = '',
        /** Metadata Component End */
        /** Items Component Start */
        string $textItems = '', string $textQuantity = '', string $textPrice = '', string $textAmount = '',
        bool $hideItems = false, bool $hideName = false, bool $hideDescription = false, bool $hideQuantity = false,
        bool $hidePrice = false, bool $hideDiscount = false, bool $hideAmount = false,
        bool $hideEditItemColumns = false,
        bool $isSalePrice = false, bool $isPurchasePrice = false, int $searchCharLimit = 0
        /** Items Component End */
    ) {
        $this->type = $type;
        $this->document = $document;
        $this->currencies = $this->getCurrencies($currencies);
        $this->currency = $this->getCurrency($document, $currency, $currency_code);
        $this->currency_code = $this->currency->code;

        /** Advanced Component Start */
        $this->categoryType = $this->getCategoryType($type, $categoryType);
        $this->textAdvancedAccordion = $this->getTextAdvancedAccordion($type, $textAdvancedAccordion);
        $this->hideRecurring = $hideRecurring;
        $this->hideCategory = $hideCategory;
        $this->hideAttachment = $hideAttachment;
        /** Advanced Component End */

        /** Company Component Start */
        $this->hideLogo = $hideLogo;
        $this->hideDocumentTitle = $hideDocumentTitle;
        $this->hideDocumentSubheading = $hideDocumentSubheading;
        $this->hideCompanyEdit = $hideCompanyEdit;
        $this->titleSetting = $this->getTitleSettingValue($titleSetting);
        $this->subheadingSetting = $this->getSubheadingSettingValue($subheadingSetting);
        /** Company Component End */

        /** Content Component Start */
        $this->routeStore = $this->getRouteStore($type, $routeStore);
        $this->routeUpdate = $this->getRouteUpdate($type, $routeUpdate, $document);
        $this->routeCancel = $this->getRouteCancel($type, $routeCancel);
        $this->formId = $formId;
        $this->formSubmit = $formSubmit;

        $this->hideCompany = $hideCompany;
        $this->hideAdvanced = $hideAdvanced;
        $this->hideFooter = $hideFooter;
        $this->hideButtons = $hideButtons;
        $this->footerSetting = $this->getFooterSettingValue($footerSetting);
        $this->notesSetting = $this->getNotesSettingValue($notesSetting);
        /** Content Component End */

        /** Metadata Component Start */
        $this->contacts = $this->getContacts($type, $contacts);
        $this->contact = $this->getContact($contact, $document);
        $this->contactType = $this->getContactType($type, $contactType);

        $this->textAddContact = $this->getTextAddContact($type, $textAddContact);
        $this->textCreateNewContact = $this->getTextCreateNewContact($type, $textCreateNewContact);
        $this->textEditContact = $this->getTextEditContact($type, $textEditContact);
        $this->textContactInfo = $this->getTextContactInfo($type, $textContactInfo);
        $this->textChooseDifferentContact = $this->getTextChooseDifferentContact($type, $textChooseDifferentContact);

        $this->hideContact = $hideContact;
        $this->hideIssuedAt = $hideIssuedAt;
        $this->hideDocumentNumber = $hideDocumentNumber;
        $this->hideDueAt = $hideDueAt;
        $this->hideOrderNumber = $hideOrderNumber;
        $this->issuedAt = $this->getIssuedAt($type, $document, $issuedAt);
        $this->documentNumber = $this->getDocumentNumber($type, $document, $documentNumber);
        $this->dueAt = $this->getDueAt($type, $document, $dueAt);
        $this->orderNumber = $this->getOrderNumber($type, $document, $orderNumber);

        $this->textIssuedAt = $this->getTextIssuedAt($type, $textIssuedAt);
        $this->textDocumentNumber = $this->getTextDocumentNumber($type, $textDocumentNumber);
        $this->textDueAt = $this->getTextDueAt($type, $textDueAt);
        $this->textOrderNumber = $this->getTextOrderNumber($type, $textOrderNumber);
        /** Metadata Component End */

        /** Items Component Start */
        $this->textItems = $this->getTextItems($type, $textItems);
        $this->textQuantity = $this->getTextQuantity($type, $textQuantity);
        $this->textPrice = $this->getTextPrice($type, $textPrice);
        $this->textAmount = $this->getTextAmount($type, $textAmount);

        $this->hideItems = $this->getHideItems($type, $hideItems, $hideName, $hideDescription);
        $this->hideName = $this->getHideName($type, $hideName);
        $this->hideDescription = $this->getHideDescription($type, $hideDescription);
        $this->hideQuantity = $this->getHideQuantity($type, $hideQuantity);
        $this->hidePrice = $this->getHidePrice($type, $hidePrice);
        $this->hideDiscount = $this->getHideDiscount($type, $hideDiscount);
        $this->hideAmount = $this->getHideAmount($type, $hideAmount);

        $this->hideEditItemColumns = $hideEditItemColumns;
        $this->isSalePrice = $isSalePrice;
        $this->isPurchasePrice = $isPurchasePrice;
        $this->searchCharLimit = $this->getSearchCharLimit($type, $searchCharLimit);
        /** Items Component End */
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
        if (!empty($currency)) {
            return $currency;
        }

        if (!empty($currency_code)) {
            return Currency::where('code', $currency_code)->first();
        }

        if (!empty($document)) {
            return Currency::where('code', $document->currency_code)->first();
        }

        return Currency::where('code', setting('default.currency'))->first();
    }

    protected function getRouteStore($type, $routeStore)
    {
        if (!empty($routeStore)) {
            return $routeStore;
        }

        $route = $this->getRouteFromConfig($type, 'store');

        if (!empty($route)) {
            return $route;
        }

        return 'invoices.store';
    }

    protected function getRouteUpdate($type, $routeUpdate, $document, $parameters = [])
    {
        if (!empty($routeUpdate)) {
            return $routeUpdate;
        }

        $parameters = [
            config('type.' . $type. '.route.parameter') => ($document) ? $document->id : 1,
        ];

        $route = $this->getRouteFromConfig($type, 'update', $parameters);

        if (!empty($route)) {
            return $route;
        }

        return 'invoices.update';
    }

    protected function getRouteCancel($type, $routeCancel)
    {
        if (!empty($routeCancel)) {
            return $routeCancel;
        }

        $route = $this->getRouteFromConfig($type, 'index');

        if (!empty($route)) {
            return $route;
        }

        return 'invoices.index';
    }

    protected function getCategoryType($type, $categoryType)
    {
        if (!empty($categoryType)) {
            return $categoryType;
        }

        if ($category_type = config('type.' . $type . '.category_type')) {
            return $category_type;
        }

        // set default type
        $type = Document::INVOICE_TYPE;

        return config('type.' . $type . '.category_type');
    }

    protected function getTextAdvancedAccordion($type, $textAdvancedAccordion)
    {
        if (!empty($textAdvancedAccordion)) {
            return $textAdvancedAccordion;
        }

        $translation = $this->getTextFromConfig($type, 'advanced_accordion');

        if (!empty($translation)) {
            return $translation;
        }

        return 'general.recurring_and_more';
    }

    protected function getContacts($type, $contacts)
    {
        if (!empty($contacts)) {
            return $contacts;
        }

        $contact_type = $this->getContactType($type, null);

        if ($contact_type) {
            $contacts = Contact::$contact_type()->enabled()->orderBy('name')->take(setting('default.select_limit'))->get();
        } else {
            $contacts = Contact::enabled()->orderBy('name')->take(setting('default.select_limit'))->get();
        }

        return $contacts;
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

    protected function getContactType($type, $contactType)
    {
        if (!empty($contactType)) {
            return $contactType;
        }

        if ($contact_type = config('type.' . $type . '.contact_type')) {
            return $contact_type;
        }

        // set default type
        $type = Document::INVOICE_TYPE;

        return config('type.' . $type . '.contact_type');
    }

    protected function getTextAddContact($type, $textAddContact)
    {
        if (!empty($textAddContact)) {
            return $textAddContact;
        }

        $default_key = Str::plural(config('type.' . $type . '.contact_type'), 2);

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

        /*
        $default_key = Str::plural(config('type.' . $type . '.contact_type'), 2);

        $translation = $this->getTextFromConfig($type, 'create_new_contact', $default_key, 'trans_choice');

        if (!empty($translation)) {
            return [
                'general.form.add_new',
                $translation,
            ];
        }
        */

        return 'general.add_new';
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

        $default_key = Str::plural(config('type.' . $type . '.contact_type'), 2);

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

    protected function getDocumentNumber($type, $document, $documentNumber)
    {
        if (!empty($documentNumber)) {
            return $documentNumber;
        }

        if ($document) {
            return $document->document_number;
        }

        $document_number = $this->getNextDocumentNumber($type);

        if (empty($document_number)) {
            $document_number = $this->getNextDocumentNumber(Document::INVOICE_TYPE);
        }

        return $document_number;
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

        $addDays = setting($this->getSettingKey($type, 'payment_terms'), 0) ?: 0;

        $dueAt = Date::parse($issuedAt)->addDays($addDays)->toDateString();

        return $dueAt;
    }

    protected function getOrderNumber($type, $document, $orderNumber)
    {
        if (!empty($orderNumber)) {
            return $orderNumber;
        }

        if ($document) {
            return $document->order_number;
        }

        $order_number = null;
    }

    protected function getTextDocumentNumber($type, $textDocumentNumber)
    {
        if (!empty($textDocumentNumber)) {
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

        if (!empty($translation)) {
            return $translation;
        }

        return 'invoices.invoice_number';
    }

    protected function getTextOrderNumber($type, $textOrderNumber)
    {
        if (!empty($textOrderNumber)) {
            return $textOrderNumber;
        }

        $translation = $this->getTextFromConfig($type, 'order_number');

        if (!empty($translation)) {
            return $translation;
        }

        return 'invoices.order_number';
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

    protected function getTextItems($type, $textItems)
    {
        if (!empty($textItems)) {
            return $textItems;
        }

        // if you use settting translation
        if (setting($this->getSettingKey($type, 'item_name'), 'items') === 'custom') {
            if (empty($textItems = setting($this->getSettingKey($type, 'item_name_input')))) {
                $textItems = 'general.items';
            }

            return $textItems;
        }

        $translation = $this->getTextFromConfig($type, 'items');

        if (!empty($translation)) {
            return $translation;
        }

        return 'general.items';
    }

    protected function getTextQuantity($type, $textQuantity)
    {
        if (!empty($textQuantity)) {
            return $textQuantity;
        }

        // if you use settting translation
        if (setting($this->getSettingKey($type, 'quantity_name'), 'quantity') === 'custom') {
            if (empty($textQuantity = setting($this->getSettingKey($type, 'quantity_name_input')))) {
                $textQuantity = 'invoices.quantity';
            }

            return $textQuantity;
        }

        $translation = $this->getTextFromConfig($type, 'quantity');

        if (!empty($translation)) {
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
        if (setting($this->getSettingKey($type, 'price_name'), 'price') === 'custom') {
            if (empty($textPrice = setting($this->getSettingKey($type, 'price_name_input')))) {
                $textPrice = 'invoices.price';
            }

            return $textPrice;
        }

        $translation = $this->getTextFromConfig($type, 'price');

        if (!empty($translation)) {
            return $translation;
        }

        return 'invoices.price';
    }

    protected function getTextAmount($type, $textAmount)
    {
        if (!empty($textAmount)) {
            return $textAmount;
        }

        $translation = $this->getTextFromConfig($type, 'amount');

        if (!empty($translation)) {
            return $translation;
        }

        return 'general.amount';
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

    protected function getTitleSettingValue($titleSetting)
    {
        if (!empty($titleSetting)) {
            return $titleSetting;
        }

        return setting($this->getSettingKey($this->type, 'title'));
    }

    protected function getSubheadingSettingValue($subheadingSetting)
    {
        if (!empty($subheadingSetting)) {
            return $subheadingSetting;
        }

        return setting($this->getSettingKey($this->type, 'subheading'));
    }

    protected function getFooterSettingValue($footerSetting)
    {
        if (!empty($footerSetting)) {
            return $footerSetting;
        }

        return setting($this->getSettingKey($this->type, 'footer'));
    }

    protected function getNotesSettingValue($notesSetting)
    {
        if (!empty($notesSetting)) {
            return $notesSetting;
        }

        return setting($this->getSettingKey($this->type, 'notes'));
    }

    protected function getSearchCharLimit($type, $searchCharLimit)
    {
        if (!empty($searchCharLimit)) {
            return $searchCharLimit;
        }

        // if you use settting translation
        if ($settingCharLimit = setting($this->getSettingKey($type, 'item_search_chart_limit'), false)) {
            return $settingCharLimit;
        }

        $hide = $this->getHideFromConfig($type, 'item_search_char_limit');

        if ($hide) {
            return $hide;
        }

        // @todo what return value invoice or always false??
        return setting('invoice.item_search_char_limit', $searchCharLimit);
    }
}
