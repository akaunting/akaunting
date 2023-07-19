<?php

namespace App\Abstracts\View\Components\Documents;

use App\Abstracts\View\Component;
use App\Models\Common\Media;
use App\Traits\DateTime;
use App\Traits\Documents;
use App\Traits\Tailwind;
use App\Traits\ViewComponents;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Exception\NotReadableException;
use Image;
use ReflectionProperty;

abstract class Template extends Component
{
    use DateTime, Documents, Tailwind, ViewComponents;

    public const OBJECT_TYPE = 'document';
    public const DEFAULT_TYPE = 'invoice';
    public const DEFAULT_PLURAL_TYPE = 'invoices';

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

    /** @var string */
    public $showContactRoute;

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

    /** @var bool */
    public $print;

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
        string $textContactInfo = '', string $textDocumentNumber = '', string $textOrderNumber = '', string $textIssuedAt = '', string $textDueAt = '', string $showContactRoute = '',
        bool $hideItems = false, bool $hideName = false, bool $hideDescription = false, bool $hideQuantity = false, bool $hidePrice = false, bool $hideDiscount = false, bool $hideAmount = false, bool $hideNote = false,
        string $textItems = '', string $textQuantity = '', string $textPrice = '', string $textAmount = '', bool $print = false
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
        $this->textDocumentSubheading = $this->getTextDocumentSubheading($type, $textDocumentSubheading);
        $this->textContactInfo = $this->getTextContactInfo($type, $textContactInfo);
        $this->showContactRoute = $this->getShowContactRoute($type, $showContactRoute);
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

        $this->print = $this->getPrint($print);

        // Set Parent data
        //$this->setParentData();
    }

    protected function getDocumentTemplate($type, $documentTemplate)
    {
        if (! empty($documentTemplate)) {
            return $documentTemplate;
        }

        if ($template = config('type.document.' . $type . '.template', false)) {
            return $template;
        }

        $documentTemplate =  setting($this->getDocumentSettingKey($type, 'template'), 'default');

        return $documentTemplate;
    }

    protected function getLogo($logo)
    {
        if (! empty($logo)) {
            return $logo;
        }

        $media_id = (! empty($this->document->contact->logo) && ! empty($this->document->contact->logo->id)) ? $this->document->contact->logo->id : setting('company.logo');

        $media = Media::find($media_id);

        if (! empty($media)) {
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
        if (! empty($backgroundColor)) {
            return $backgroundColor;
        }

        // checking setting color
        $key = $this->getDocumentSettingKey($type, 'color');

        if (! empty(setting($key))) {
            $backgroundColor = setting($key);
        }

        // checking config color
        if (empty($backgroundColor) && $background_color = config('type.document.' . $type . '.color', false)) {
            $backgroundColor = $background_color;
        }

        // set default color
        if (empty($backgroundColor)) {
            $backgroundColor = '#55588b';
        }

        return $this->getHexCodeOfTailwindClass($backgroundColor);
    }

    protected function getTextDocumentTitle($type, $textDocumentTitle)
    {
        if (! empty($textDocumentTitle)) {
            return $textDocumentTitle;
        }

        if (! empty($this->document) && $this->document->title !== '') {
            return $this->document->title;
        }

        $key = $this->getDocumentSettingKey($type, 'title');

        if (! empty(setting($key))) {
            return setting($key);
        }

        $translation = $this->getTextFromConfig($type, 'document_title', Str::plural($type));

        if (! empty($translation)) {
            return trans_choice($translation, 1);
        }

        return setting('invoice.title');
    }

    protected function getTextDocumentSubheading($type, $textDocumentSubheading)
    {
        if (! empty($textDocumentSubheading)) {
            return $textDocumentSubheading;
        }

        if (! empty($this->document) && $this->document->subheading !== '') {
            return $this->document->subheading;
        }

        $key = $this->getDocumentSettingKey($type, 'subheading');

        if (! empty(setting($key))) {
            return setting($key);
        }

        $translation = $this->getTextFromConfig($type, 'document_subheading', 'subheading');

        if (! empty($translation)) {
            return trans($translation);
        }

        return false;
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

        return 'general.numbers';
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

    protected function getTextContactInfo($type, $textContactInfo)
    {
        if (! empty($textContactInfo)) {
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

        if (! empty($translation)) {
            return $translation;
        }

        return 'invoices.bill_to';
    }

    protected function getTextIssuedAt($type, $textIssuedAt)
    {
        if (! empty($textIssuedAt)) {
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

        if (! empty($translation)) {
            return $translation;
        }

        return 'invoices.invoice_date';
    }

    protected function getTextDueAt($type, $textDueAt)
    {
        if (! empty($textDueAt)) {
            return $textDueAt;
        }

        $translation = $this->getTextFromConfig($type, 'due_at', 'due_date');

        if (! empty($translation)) {
            return $translation;
        }

        return 'invoices.due_date';
    }

    protected function getTextItems($type, $textItems)
    {
        if (! empty($textItems)) {
            return $textItems;
        }

        // if you use settting translation
        if (setting($this->getDocumentSettingKey($type, 'item_name'), 'items') === 'custom') {
            if (empty($textItems = setting($this->getDocumentSettingKey($type, 'item_name_input')))) {
                $textItems = 'general.items';
            }

            return $textItems;
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

    protected function getTextQuantity($type, $textQuantity)
    {
        if (! empty($textQuantity)) {
            return $textQuantity;
        }

        // if you use settting translation
        if (setting($this->getDocumentSettingKey($type, 'quantity_name'), 'quantity') === 'custom') {
            if (empty($textQuantity = setting($this->getDocumentSettingKey($type, 'quantity_name_input')))) {
                $textQuantity = 'invoices.quantity';
            }

            return $textQuantity;
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

    protected function getTextPrice($type, $textPrice)
    {
        if (! empty($textPrice)) {
            return $textPrice;
        }

        // if you use settting translation
        if (setting($this->getDocumentSettingKey($type, 'price_name'), 'price') === 'custom') {
            if (empty($textPrice = setting($this->getDocumentSettingKey($type, 'price_name_input')))) {
                $textPrice = 'invoices.price';
            }

            return $textPrice;
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

    protected function getTextAmount($type, $textAmount)
    {
        if (! empty($textAmount)) {
            return $textAmount;
        }

        $translation = $this->getTextFromConfig($type, 'amount');

        if (! empty($translation)) {
            return $translation;
        }

        return 'general.amount';
    }

    protected function getShowContactRoute($type, $showContactRoute)
    {
        if (! empty($showContactRoute)) {
            return $showContactRoute;
        }

        $route = $this->getRouteFromConfig($type, 'contact.show', 1);

        if (!empty($route)) {
            return $route;
        }

        $default_key = Str::plural(config('type.' . static::OBJECT_TYPE . '.' . $type . '.contact_type'), 2);

        return $default_key . '.show';
    }

    protected function getHideItems($type, $hideItems, $hideName, $hideDescription)
    {
        if (! empty($hideItems)) {
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
        if (! empty($hideName)) {
            return $hideName;
        }

        $hideName = setting($this->getDocumentSettingKey($type, 'item_name'), false);

        // if you use settting translation
        if ($hideName === 'hide') {
            return true;
        }

        $hide = $this->getHideFromConfig($type, 'name');

        if ($hide) {
            return $hide;
        }

        return false;
    }

    protected function getHideDescription($type, $hideDescription)
    {
        if (! empty($hideDescription)) {
            return $hideDescription;
        }

        // if you use settting translation
        if (setting($this->getDocumentSettingKey($type, 'hide_item_description'), false)) {
            return true;
        }

        $hide = $this->getHideFromConfig($type, 'description');

        if ($hide) {
            return $hide;
        }

        return false;
    }

    protected function getHideQuantity($type, $hideQuantity)
    {
        if (! empty($hideQuantity)) {
            return $hideQuantity;
        }

        $hideQuantity = setting($this->getDocumentSettingKey($type, 'quantity_name'), false);

        // if you use settting translation
        if ($hideQuantity === 'hide') {
            return true;
        }

        $hide = $this->getHideFromConfig($type, 'quantity');

        if ($hide) {
            return $hide;
        }

        return false;
    }

    protected function getHidePrice($type, $hidePrice)
    {
        if (! empty($hidePrice)) {
            return $hidePrice;
        }

        $hidePrice = setting($this->getDocumentSettingKey($type, 'price_name'), false);

        // if you use settting translation
        if ($hidePrice === 'hide') {
            return true;
        }

        $hide = $this->getHideFromConfig($type, 'price');

        if ($hide) {
            return $hide;
        }

        return false;
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

    protected function getHideAmount($type, $hideAmount)
    {
        if (! empty($hideAmount)) {
            return $hideAmount;
        }

        // if you use settting translation
        if (setting($this->getDocumentSettingKey($type, 'hide_amount'), false)) {
            return true;
        }

        $hide = $this->getHideFromConfig($type, 'amount');

        if ($hide) {
            return $hide;
        }

        return false;
    }

    protected function getPrint($print)
    {
        if (! empty($print)) {
            return $print;
        }

        $self = new ReflectionProperty($this::class, 'methodCache');
        $self->setAccessible(true);

        $values = $self->getValue();

        if (array_key_exists('App\View\Components\Layouts\Admin', $values)) {
            return false;
        }

        return true;
    }
}
