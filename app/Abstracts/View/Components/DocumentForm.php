<?php

namespace App\Abstracts\View\Components;

use App\Models\Common\Contact;
use App\Models\Document\Document;
use App\Traits\Documents;
use Date;
use Illuminate\View\Component;
use Illuminate\Support\Str;
abstract class DocumentForm extends Component
{
    use Documents;

    public $type;

    public $document;

    /** Advanced Component Start */
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
    /** Company Component End */

    /** Content Component Start */
    /** @var string */
    public $formRoute;

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
    /** Items Component End */

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $type, $document = false,
        /** Advanced Component Start */
        bool $hideRecurring = false, bool $hideCategory = false, bool $hideAttachment = false,
        /** Advanced Component End */
        /** Company Component Start */
        bool $hideLogo = false, bool $hideDocumentTitle = false, bool $hideDocumentSubheading = false, bool $hideCompanyEdit = false,
        /** Company Component End */
        /** Content Component Start */
        string $formRoute = '', string $formId = 'document', string $formSubmit = 'onSubmit',
        bool $hideCompany = false, bool $hideAdvanced = false, bool $hideFooter = false, bool $hideButtons = false,
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
        bool $isSalePrice = false, bool $isPurchasePrice = false
        /** Items Component End */
    ) {
        $this->type = $type;
        $this->document = $document;

        /** Advanced Component Start */
        $this->hideRecurring = $hideRecurring;
        $this->hideCategory = $hideCategory;
        $this->hideAttachment = $hideAttachment;
        /** Advanced Component End */

        /** Company Component Start */
        $this->hideLogo = $hideLogo;
        $this->hideDocumentTitle = $hideDocumentTitle;
        $this->hideDocumentSubheading = $hideDocumentSubheading;
        $this->hideCompanyEdit = $hideCompanyEdit;
        /** Company Component End */

        /** Content Component Start */
        $this->formRoute = ($formRoute) ? $formRoute : $this->getRoute($type, $document);
        $this->formId = $formId;
        $this->formSubmit = $formSubmit;

        $this->hideCompany = $hideCompany;
        $this->hideAdvanced = $hideAdvanced;
        $this->hideFooter = $hideFooter;
        $this->hideButtons = $hideButtons;
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
        /** Items Component End */
    }

    protected function getRoute($type, $document, $parameters = [])
    {
        $page = Str::plural($type, 2);

        $route = $page . '.store';

        if ($document) {
            $parameters = [
                Str::replaceFirst('-', '_', $type) => $document->id
            ];

            $route = $page . '.update';
        }

        try {
            route($route, $parameters);
        } catch (\Exception $e) {
            $route = '';
        }

        return $route;
    }

    protected function getContacts($type, $contacts)
    {
        if (!empty($contacts)) {
            return $contacts;
        }

        $contact_type = $this->getContactType($type, null);

        if ($contact_type) {
            $contacts = Contact::$contact_type()->enabled()->orderBy('name')->take(setting('default.select_limit'))->pluck('name', 'id');
        } else {
            $contacts = Contact::enabled()->orderBy('name')->take(setting('default.select_limit'))->pluck('name', 'id');
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

        return $contact;
    }

    protected function getContactType($type, $contact_type)
    {
        if (!empty($contact_type)) {
            return $contact_type;
        }

        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $contact_type = 'vendor';
                break;
            default:
                $contact_type = 'customer';
                break;
        }

        return $contact_type;
    }

    protected function getTextAddContact($type, $textAddContact)
    {
        if (!empty($textAddContact)) {
            return $textAddContact;
        }

        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $textAddContact = [
                    'general.form.add',
                    'general.vendors'
                ];
                break;
            default:
                $textAddContact = [
                    'general.form.add',
                    'general.customers'
                ];
                break;
        }

        return $textAddContact;
    }

    protected function getTextCreateNewContact($type, $textCreateNewContact)
    {
        if (!empty($textCreateNewContact)) {
            return $textCreateNewContact;
        }

        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $textCreateNewContact = [
                    'general.form.add_new',
                    'general.vendors'
                ];
                break;
            default:
                $textCreateNewContact = [
                    'general.form.add_new',
                    'general.customers'
                ];
                break;
        }

        return $textCreateNewContact;
    }

    protected function getTextEditContact($type, $textEditContact)
    {
        if (!empty($textEditContact)) {
            return $textEditContact;
        }

        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $textEditContact = [
                    'general.form.contact_edit',
                    'general.vendors'
                ];
                break;
            default:
                $textEditContact = [
                    'general.form.contact_edit',
                    'general.customers'
                ];
                break;
        }

        return $textEditContact;
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

    protected function getTextChooseDifferentContact($type, $textChooseDifferentContact)
    {
        if (!empty($textChooseDifferentContact)) {
            return $textChooseDifferentContact;
        }

        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $textChooseDifferentContact = [
                    'general.form.choose_different',
                    'general.vendors'
                ];
                break;
            default:
                $textChooseDifferentContact = [
                    'general.form.choose_different',
                    'general.customers'
                ];
                break;
        }

        return $textChooseDifferentContact;
    }

    protected function getIssuedAt($type, $document, $issued_at)
    {
        if (!empty($issued_at)) {
            return $issued_at;
        }

        if ($document) {
            return $document->issued_at;
        }

        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $issued_at = request()->get('billed_at', Date::now()->toDateString());
                break;
            default:
                $issued_at = request()->get('invoiced_at', Date::now()->toDateString());
                break;
        }

        return $issued_at;
    }

    protected function getDocumentNumber($type, $document, $document_number)
    {
        if (!empty($document_number)) {
            return $document_number;
        }

        if ($document) {
            return $document->document_number;
        }

        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $document_number = $this->getNextDocumentNumber(Document::BILL_TYPE);
                break;
            default:
                $document_number = $this->getNextDocumentNumber(Document::INVOICE_TYPE);
                break;
        }

        return $document_number;
    }

    protected function getDueAt($type, $document, $due_at)
    {
        if (!empty($due_at)) {
            return $due_at;
        }

        if ($document) {
            return $document->due_at;
        }

        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $due_at = request()->get('billed_at', Date::now()->toDateString());
                break;
            default:
                $due_at = Date::parse(request()->get('invoiced_at', Date::now()->toDateString()))->addDays(setting('invoice.payment_terms', 0))->toDateString();
                break;
        }

        return $due_at;
    }

    protected function getOrderNumber($type, $document, $order_number)
    {
        if (!empty($order_number)) {
            return $order_number;
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
                $textDocumentNumber = 'bills.bill_number';
                break;
            default:
                $textDocumentNumber = 'invoices.invoice_number';
                break;
        }

        return $textDocumentNumber;
    }

    protected function getTextOrderNumber($type, $textOrderNumber)
    {
        if (!empty($textOrderNumber)) {
            return $textOrderNumber;
        }

        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $textOrderNumber = 'bills.order_number';
                break;
            default:
                $textOrderNumber = 'invoices.order_number';
                break;
        }

        return $textOrderNumber;
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
                $textIssuedAt = 'bills.bill_date';
                break;
            default:
                $textIssuedAt = 'invoices.invoice_date';
                break;
        }

        return $textIssuedAt;
    }

    protected function getTextDueAt($type, $textDueAt)
    {
        if (!empty($textDueAt)) {
            return $textDueAt;
        }

        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $textDueAt = 'bills.due_date';
                break;
            default:
                $textDueAt = 'invoices.due_date';
                break;
        }

        return $textDueAt;
    }

    protected function getTextItems($type, $text_items)
    {
        if (!empty($text_items)) {
            return $text_items;
        }

        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $text_items = 'general.items';
                break;
            default:
                $text_items = setting('invoice.item_name', 'general.items');

                if ($text_items == 'custom') {
                    $text_items = setting('invoice.item_name_input');
                }
                break;
        }

        return $text_items;
    }

    protected function getTextQuantity($type, $text_quantity)
    {
        if (!empty($text_quantity)) {
            return $text_quantity;
        }

        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $text_quantity = 'bills.quantity';
                break;
            default:
                $text_quantity = setting('invoice.quantity_name', 'invoices.quantity');

                if ($text_quantity == 'custom') {
                    $text_quantity = setting('invoice.quantity_name_input');
                }
                break;
        }

        return $text_quantity;
    }

    protected function getTextPrice($type, $text_price)
    {
        if (!empty($text_price)) {
            return $text_price;
        }

        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $text_price = 'bills.price';
                break;
            default:
                $text_price = setting('invoice.price_name', 'invoices.price');

                if ($text_price == 'custom') {
                    $text_price = setting('invoice.price_name_input');
                }
                break;
        }

        return $text_price;
    }

    protected function getTextAmount($type, $text_amount)
    {
        if (!empty($text_amount)) {
            return $text_amount;
        }

        $text_amount = 'general.amount';

        return $text_amount;
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
