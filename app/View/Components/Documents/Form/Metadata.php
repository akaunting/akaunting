<?php

namespace App\View\Components\Documents\Form;

use App\Models\Common\Contact;
use App\Models\Document\Document;
use App\Traits\Documents;
use Date;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class Metadata extends Component
{
    use Documents;

    /** @var string */
    public $type;

    public $document;

    public $contacts;

    /** @var string */
    public $contactType;

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

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $type, $document = false, $contacts = [], string $contactType = '',
        bool $hideContact = false, bool $hideIssuedAt = false, bool $hideDocumentNumber = false, bool $hideDueAt = false, bool $hideOrderNumber = false,
        string $textDocumentNumber = '', string $textOrderNumber = '', string $textIssuedAt = '', string $textDueAt = '',
        string $issuedAt = '', string $documentNumber = '', string $dueAt = '', string $orderNumber = ''
    ) {
        $this->type = $type;
        $this->document = $document;
        $this->contacts = $this->getContacts($type, $contacts);
        $this->contactType = $this->getContactType($type, $contactType);

        $this->hideContact = $hideContact;
        $this->hideIssuedAt = $hideIssuedAt;
        $this->hideDocumentNumber = $hideDocumentNumber;
        $this->hideDueAt = $hideDueAt;
        $this->hideOrderNumber = $hideOrderNumber;
        $this->issuedAt = $this->getissuedAt($type, $document, $issuedAt);
        $this->documentNumber = $this->getDocumentNumber($type, $document, $documentNumber);
        $this->dueAt = $this->getDueAt($type, $document, $dueAt);
        $this->orderNumber = $this->getOrderNumber($type, $document, $orderNumber);

        $this->textIssuedAt = $this->gettextIssuedAt($type, $textIssuedAt);
        $this->textDocumentNumber = $this->getTextDocumentNumber($type, $textDocumentNumber);
        $this->textDueAt = $this->getTextDueAt($type, $textDueAt);
        $this->textOrderNumber = $this->getTextOrderNumber($type, $textOrderNumber);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.documents.form.metadata');
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

    protected function getContactType($type, $contact_type)
    {
        if (!empty($contact_type)) {
            return $contact_type;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $contact_type = 'customer';
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $contact_type = 'vendor';
                break;
        }

        return $contact_type;
    }

    protected function getissuedAt($type, $document, $issued_at)
    {
        if (!empty($issued_at)) {
            return $issued_at;
        }

        if ($document) {
            return $document->issued_at;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $issued_at = request()->get('invoiced_at', Date::now()->toDateString());
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $issued_at = request()->get('billed_at', Date::now()->toDateString());
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
            case 'sale':
            case 'income':
            case 'invoice':
                $document_number = $this->getNextDocumentNumber(Document::INVOICE_TYPE);
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $document_number = $this->getNextDocumentNumber(Document::BILL_TYPE);
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
            case 'sale':
            case 'income':
            case 'invoice':
                $due_at = Date::parse(request()->get('invoiced_at', Date::now()->toDateString()))->addDays(setting('invoice.payment_terms', 0))->toDateString();
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $due_at = request()->get('billed_at', Date::now()->toDateString());
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
            case 'sale':
            case 'income':
            case 'invoice':
                $textDocumentNumber = trans('invoices.invoice_number');
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $textDocumentNumber = trans('bills.bill_number');
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
            case 'sale':
            case 'income':
            case 'invoice':
                $textOrderNumber = trans('invoices.order_number');
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $textOrderNumber = trans('bills.order_number');
                break;
        }

        return $textOrderNumber;
    }

    protected function gettextIssuedAt($type, $textIssuedAt)
    {
        if (!empty($textIssuedAt)) {
            return $textIssuedAt;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $textIssuedAt = trans('invoices.invoice_date');
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $textIssuedAt = trans('bills.bill_date');
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
            case 'sale':
            case 'income':
            case 'invoice':
                $textDueAt = trans('invoices.due_date');
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $textDueAt = trans('bills.due_date');
                break;
        }

        return $textDueAt;
    }
}
