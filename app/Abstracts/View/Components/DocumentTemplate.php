<?php

namespace App\Abstracts\View\Components;

use App\Abstracts\View\Components\Document as Base;
use App\Models\Common\Media;
use App\Traits\DateTime;
use App\Traits\Documents;
use File;
use Illuminate\Support\Facades\Log;
use Image;
use Intervention\Image\Exception\NotReadableException;
use Storage;
use Illuminate\Support\Str;

abstract class DocumentTemplate extends Base
{
    use DateTime;
    use Documents;

    public $type;

    public $item;

    public $document;

    /** @var string */
    public $documentTemplate;

    public $date_format;

    public $logo;

    public $backgroundColor;

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

    /** @var string */
    public $textDocumentTitle;

    /** @var string */
    public $textDocumentSubheading;

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
        $type, $document, $item = false,  $documentTemplate = '', $logo = '', $backgroundColor = '',
        bool $hideFooter = false, bool $hideCompanyLogo = false, bool $hideCompanyDetails = false,
        bool $hideCompanyName = false, bool $hideCompanyAddress = false, bool $hideCompanyTaxNumber = false, bool $hideCompanyPhone = false, bool $hideCompanyEmail = false, bool $hideContactInfo = false,
        bool $hideContactName = false, bool $hideContactAddress = false, bool $hideContactTaxNumber = false, bool $hideContactPhone = false, bool $hideContactEmail = false,
        bool $hideOrderNumber = false, bool $hideDocumentNumber = false, bool $hideIssuedAt = false, bool $hideDueAt = false,
        string $textDocumentTitle = '', string $textDocumentSubheading = '',
        string $textContactInfo = '', string $textDocumentNumber = '', string $textOrderNumber = '', string $textIssuedAt = '', string $textDueAt = '',
        bool $hideItems = false, bool $hideName = false, bool $hideDescription = false, bool $hideQuantity = false, bool $hidePrice = false, bool $hideDiscount = false, bool $hideAmount = false, bool $hideNote = false,
        string $textItems = '', string $textQuantity = '', string $textPrice = '', string $textAmount = ''
    ) {
        $this->type = $type;
        $this->item = $item;
        $this->document = $document;
        $this->documentTemplate = $this->getDocumentTemplate($type, $documentTemplate);
        $this->logo = $this->getLogo($logo);
        $this->backgroundColor = $this->getBackgroundColor($type, $backgroundColor);

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

        $this->textDocumentTitle = $this->getTextDocumentTitle($type, $textDocumentTitle);
        $this->textDocumentSubheading = $this->gettextDocumentSubheading($type, $textDocumentSubheading);
        $this->textContactInfo = $this->getTextContactInfo($type, $textContactInfo);
        $this->textIssuedAt = $this->getTextIssuedAt($type, $textIssuedAt);
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

        if ($template = config('type.' . $type . 'template', false)) {
            return $template;
        }

        $documentTemplate =  setting($this->getSettingKey($type, 'template'), 'default');

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

    protected function getBackgroundColor($type, $backgroundColor)
    {
        if (!empty($backgroundColor)) {
            return $backgroundColor;
        }

        if ($background_color = config('type.' . $type . 'color', false)) {
            return $background_color;
        }


        if (!empty($alias = config('type.' . $type . '.alias'))) {
            $type = $alias . '.' . str_replace('-', '_', $type);
        }

        $backgroundColor = setting($this->getSettingKey($type, 'color'), '#55588b');

        return $backgroundColor;
    }

    protected function getTextDocumentTitle($type, $textDocumentTitle)
    {
        if (!empty($textDocumentTitle)) {
            return $textDocumentTitle;
        }

        if (!empty(setting($type . '.title'))) {
            return setting($type . '.title');
        }

        $translation = $this->getTextFromConfig($type, 'document_title', Str::plural($type));

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

        return false;
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

        return 'general.numbers';
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
        if (setting($this->getSettingKey($type, 'item_name'), 'items') == 'custom') {
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
}
