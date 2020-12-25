<?php

namespace App\Abstracts\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Str;
use App\Traits\DateTime;
use App\Models\Common\Media;
use File;
use Image;
use Storage;

abstract class DocumentTemplate extends Component
{
    use DateTime;

    public $type;

    public $document;

    /** @var string */
    public $documentTemplate;

    public $date_format;

    public $logo;

    public $backGroundColor;

    public $hideFooter;

    public $hideCompanyLogo;

    public $hideCompanyDetails;

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

    public $hideNote;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $type, $document, $documentTemplate = '', $logo = '', $backgroundColor = '',
        bool $hideFooter = false, bool $hideCompanyLogo = false, bool $hideCompanyDetails = false,
        bool $hideCompanyName = false, bool $hideCompanyAddress = false, bool $hideCompanyTaxNumber = false, bool $hideCompanyPhone = false, bool $hideCompanyEmail = false, bool $hideContactInfo = false,
        bool $hideContactName = false, bool $hideContactAddress = false, bool $hideContactTaxNumber = false, bool $hideContactPhone = false, bool $hideContactEmail = false,
        bool $hideOrderNumber = false, bool $hideDocumentNumber = false, bool $hideIssuedAt = false, bool $hideDueAt = false,
        string $textContactInfo = '', string $textDocumentNumber = '', string $textOrderNumber = '', string $textIssuedAt = '', string $textDueAt = '',
        bool $hideItems = false, bool $hideName = false, bool $hideDescription = false, bool $hideQuantity = false, bool $hidePrice = false, bool $hideDiscount = false, bool $hideAmount = false, bool $hideNote = false,
        string $textItems = '', string $textQuantity = '', string $textPrice = '', string $textAmount = ''
    ) {
        $this->type = $type;
        $this->document = $document;
        $this->documentTemplate = $this->getDocumentTemplate($type, $documentTemplate);
        $this->logo = $this->getLogo($logo);
        $this->backGroundColor = $this->getBackgroundColor($type, $backgroundColor);

        $this->hideFooter = $hideFooter;
        $this->hideCompanyLogo = $hideCompanyLogo;
        $this->hideCompanyDetails = $hideCompanyDetails;
        $this->hideCompanyName = $hideCompanyName;
        $this->hideCompanyAddress = $hideCompanyAddress;
        $this->hideCompanyTaxNumber = $hideCompanyTaxNumber;
        $this->hideCompanyPhone = $hideCompanyPhone;
        $this->hideCompanyEmail = $hideCompanyEmail;
        $this->hideContactInfo = $hideContactInfo;
        $this->hideContactName = $hideContactName;
        $this->hideContactAddress = $hideContactAddress;
        $this->hideContactTaxNumber = $hideContactTaxNumber;
        $this->hideContactPhone = $hideContactPhone;
        $this->hideContactEmail = $hideContactEmail;
        $this->hideOrderNumber = $hideOrderNumber;
        $this->hideDocumentNumber = $hideDocumentNumber;
        $this->hideIssuedAt = $hideIssuedAt;
        $this->hideDueAt = $hideDueAt;

        $this->textContactInfo = $this->getTextContactInfo($type, $textContactInfo);
        $this->textIssuedAt = $this->gettextIssuedAt($type, $textIssuedAt);
        $this->textDocumentNumber = $this->getTextDocumentNumber($type, $textDocumentNumber);
        $this->textDueAt = $this->getTextDueAt($type, $textDueAt);
        $this->textOrderNumber = $this->getTextOrderNumber($type, $textOrderNumber);

        $this->hideItems = $this->getHideItems($type, $hideItems, $hideName, $hideDescription);
        $this->hideName = $this->getHideName($type, $hideName);
        $this->hideDescription = $this->getHideDescription($type, $hideDescription);
        $this->hideQuantity = $this->getHideQuantity($type, $hideQuantity);
        $this->hidePrice = $this->getHidePrice($type, $hidePrice);
        $this->hideDiscount = $this->getHideDiscount($type, $hideDiscount);
        $this->hideAmount = $this->getHideAmount($type, $hideAmount);
        $this->hideNote = $hideNote;

        $this->textItems = $this->getTextItems($type, $textItems);
        $this->textQuantity = $this->getTextQuantity($type, $textQuantity);
        $this->textPrice = $this->getTextPrice($type, $textPrice);
        $this->textAmount = $this->getTextAmount($type, $textAmount);
    }

    protected function getDocumentTemplate($type, $documentTemplate)
    {
        if (!empty($documentTemplate)) {
            return $documentTemplate;
        }

        // $documentTemplate = 'components.documents.template.default';
        $documentTemplate = 'default';

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                // $documentTemplate =  'components.documents.template.' . setting('invoice.template', 'default');
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

    protected function getBackgroundColor($type, $backgroundColor)
    {
        if (!empty($backgroundColor)) {
            return $backgroundColor;
        }

        $backgroundColor = '#55588b';

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $backgroundColor = setting('invoice.color');
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $backgroundColor = setting('bill.color');
                break;
        }

        return $backgroundColor;
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
                $textDocumentNumber = 'invoices.invoice_number';
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $textDocumentNumber = 'bills.bill_number';
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
                $textOrderNumber = 'invoices.order_number';
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $textOrderNumber = 'bills.order_number';
                break;
        }

        return $textOrderNumber;
    }

    protected function getTextContactInfo($type, $textContactInfo)
    {
        if (!empty($textContactInfo)) {
            return $textContactInfo;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $textContactInfo = 'invoices.bill_to';
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $textContactInfo = 'bills.bill_from';
                break;
        }

        return $textContactInfo;
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
                $textIssuedAt = 'invoices.invoice_date';
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $textIssuedAt = 'bills.bill_date';
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
                $textDueAt = 'invoices.due_date';
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $textDueAt = 'bills.due_date';
                break;
        }

        return $textDueAt;
    }

    protected function getTextItems($type, $textItems)
    {
        if (!empty($textItems)) {
            return $textItems;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $textItems = setting('invoice.item_name', 'general.items');

                if ($textItems == 'custom') {
                    $textItems = setting('invoice.item_name_input');
                }
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $textItems = 'general.items';
                break;
        }

        return $textItems;
    }

    protected function getTextQuantity($type, $textQuantity)
    {
        if (!empty($textQuantity)) {
            return $textQuantity;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $textQuantity = setting('invoice.quantity_name', 'invoices.quantity');

                if ($textQuantity == 'custom') {
                    $textQuantity = setting('invoice.quantity_name_input');
                }
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $textQuantity = 'bills.quantity';
                break;
        }

        return $textQuantity;
    }

    protected function getTextPrice($type, $text_price)
    {
        if (!empty($text_price)) {
            return $text_price;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $text_price = setting('invoice.price_name', 'invoices.price');

                if ($text_price == 'custom') {
                    $text_price = setting('invoice.price_name_input');
                }
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $text_price = 'bills.price';
                break;
        }

        return $text_price;
    }

    protected function getTextAmount($type, $textAmount)
    {
        if (!empty($textAmount)) {
            return $textAmount;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
            case 'bill':
            case 'expense':
            case 'purchase':
                $textAmount = 'general.amount';
                break;
        }

        return $textAmount;
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
